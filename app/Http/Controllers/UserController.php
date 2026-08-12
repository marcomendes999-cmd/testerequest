<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\Posto;
use App\Models\Unidade;
use App\Models\User;
use App\Models\Tipo;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::with('tipo', 'posto.grupo', 'unidade')
            ->when($request->filled('nome'), fn ($query) => $query->where('name', 'like', '%'.$request->input('nome').'%'))
            ->when($request->filled('tipo_id'), fn ($query) => $query->where('tipo_id', $request->input('tipo_id')))
            ->when($request->filled('grupo_id'), fn ($query) => $query->whereHas('posto', fn ($posto) => $posto->where('grupo_id', $request->input('grupo_id'))))
            ->latest()
            ->paginate(10)
            ->withQueryString();
        $grupos = Grupo::orderBy('name')->get(['id', 'name']);
        $tipos = Tipo::orderBy('name')->get();

        return view('users.index', compact('users', 'grupos', 'tipos'));
    }

    public function create(Request $request)
    {
        $tipos = Tipo::orderBy('name')->get();
        $tipoPredefinido = $tipos->contains('id', (int) $request->input('tipo_id'))
            ? (int) $request->input('tipo_id')
            : Tipo::where('name', 'cliente')->value('id');
        $tipoColaboradorId = Tipo::colaboradorId();
        $roles = Role::orderBy('name')->get();
        $grupos = Grupo::orderBy('name')->get();
        $postosParaSelecao = $this->postosParaSelecao();
        $unidadesParaSelecao = $this->unidadesParaSelecao();

        return view('users.create', compact('tipos', 'tipoPredefinido', 'tipoColaboradorId', 'roles', 'grupos', 'postosParaSelecao', 'unidadesParaSelecao'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'numero' => ['nullable', 'string', 'max:4'],
            'tipo_id' => ['required', 'exists:tipos,id'],
            'grupo_id' => [Rule::requiredIf((int) $request->input('tipo_id') === Tipo::colaboradorId()), 'nullable', 'exists:grupos,id'],
            'posto_id' => [Rule::requiredIf((int) $request->input('tipo_id') === Tipo::colaboradorId()), 'nullable', 'exists:postos,id'],
            'unidade_id' => [Rule::requiredIf((int) $request->input('tipo_id') === Tipo::colaboradorId()), 'nullable', 'exists:unidades,id'],
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['integer', 'exists:roles,id'],
        ]);

        $this->validarEstruturaDeTrabalho($validated);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'numero' => $validated['numero'],
            'tipo_id' => $validated['tipo_id'],
            'posto_id' => (int) $validated['tipo_id'] === Tipo::colaboradorId() ? $validated['posto_id'] : null,
            'unidade_id' => (int) $validated['tipo_id'] === Tipo::colaboradorId() ? $validated['unidade_id'] : null,
        ]);

        $user->syncRoles(Role::whereIn('id', $validated['roles'])->pluck('name')->all());
        event(new Registered($user));

        return redirect()->route('users.index')->with('success', 'Utilizador criado com sucesso.');
    }

    public function edit(User $user)
    {
        $roles = Role::orderBy('name')->get();
        $tipos = Tipo::orderBy('name')->get();
        $tipoColaboradorId = Tipo::colaboradorId();
        $grupos = Grupo::orderBy('name')->get();
        $postosParaSelecao = $this->postosParaSelecao();
        $unidadesParaSelecao = $this->unidadesParaSelecao();

        return view('users.edit', compact('user', 'roles', 'tipos', 'tipoColaboradorId', 'grupos', 'postosParaSelecao', 'unidadesParaSelecao'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'numero' => ['nullable', 'string', 'max:4'],
            'tipo_id' => ['required', 'exists:tipos,id'],
            'grupo_id' => [Rule::requiredIf((int) $request->input('tipo_id') === Tipo::colaboradorId()), 'nullable', 'exists:grupos,id'],
            'posto_id' => [Rule::requiredIf((int) $request->input('tipo_id') === Tipo::colaboradorId()), 'nullable', 'exists:postos,id'],
            'unidade_id' => [Rule::requiredIf((int) $request->input('tipo_id') === Tipo::colaboradorId()), 'nullable', 'exists:unidades,id'],

            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['integer', 'exists:roles,id'],
        ]);

        $this->validarEstruturaDeTrabalho($validated);

        $emailChanged = $user->email !== $validated['email'];

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'numero' => $validated['numero'],
            'tipo_id' => $validated['tipo_id'],
            'posto_id' => (int) $validated['tipo_id'] === Tipo::colaboradorId() ? $validated['posto_id'] : null,
            'unidade_id' => (int) $validated['tipo_id'] === Tipo::colaboradorId() ? $validated['unidade_id'] : null,

        ]);

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();
        $user->syncRoles(Role::whereIn('id', $validated['roles'])->pluck('name')->all());

        if ($emailChanged) {
            $user->forceFill(['email_verified_at' => null])->save();
            $user->sendEmailVerificationNotification();
        }

        return redirect()->route('users.index')->with('success', 'Utilizador atualizado com sucesso.');
    }

    public function resendVerification(User $user)
    {
        abort_unless(auth()->user()->hasRole('admin'), 403);

        if (!$user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
        }

        return back()->with('success', $user->hasVerifiedEmail()
            ? 'O e-mail deste utilizador já está verificado.'
            : 'E-mail de verificação reenviado com sucesso.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Utilizador excluído com sucesso.');
    }

    public function history(User $user)
    {
        $history = $user->loginHistory()->latest()->get();

        return view('users.history', compact('user', 'history'));
    }

    private function postosParaSelecao()
    {
        return Posto::orderBy('name')->get(['id', 'name', 'grupo_id'])
            ->map(fn (Posto $posto) => [
                'id' => $posto->id,
                'name' => $posto->name,
                'grupo_id' => $posto->grupo_id,
            ])->values();
    }

    private function unidadesParaSelecao()
    {
        return Unidade::orderBy('name')->get(['id', 'name', 'posto_id'])
            ->map(fn (Unidade $unidade) => [
                'id' => $unidade->id,
                'name' => $unidade->name,
                'posto_id' => $unidade->posto_id,
            ])->values();
    }

    private function validarEstruturaDeTrabalho(array $validated): void
    {
        if ((int) $validated['tipo_id'] !== Tipo::colaboradorId()) {
            return;
        }

        $postoPertenceAoGrupo = Posto::whereKey($validated['posto_id'])
            ->where('grupo_id', $validated['grupo_id'])
            ->exists();

        if (!$postoPertenceAoGrupo) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'posto_id' => 'O posto de trabalho selecionado não pertence ao grupo indicado.',
            ]);
        }

        $unidadePertenceAoPosto = Unidade::whereKey($validated['unidade_id'])
            ->where('posto_id', $validated['posto_id'])
            ->exists();

        if (!$unidadePertenceAoPosto) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'unidade_id' => 'A unidade selecionada não pertence ao posto de trabalho indicado.',
            ]);
        }
    }
}
