<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tipos = ['cliente', 'fornecedor', 'colaborador']; // para select box
        return view('users.create', compact('tipos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'numero' => ['nullable', 'string', 'max:4'],
            'tipo' => ['required', 'in:cliente,fornecedor,colaborador'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'numero' => $request->numero,
            'numero' => $request->numero,
            'tipo' => $request->tipo,
        ]);

        return redirect()->route('users.index')->with('success', 'Utilizador criado com sucesso!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $empresas = Empresa::all();
       // return view('users.edit', compact('user', 'tipos', 'empresas'));


        $roles = Role::all();
        $tipos = ['cliente', 'fornecedor', 'colaborador']; // para select box
        return view('users.edit', compact('user', 'roles', 'tipos', 'empresas'));
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'numero' => ['nullable', 'string', 'max:4'],
            'tipo' => ['required', 'in:cliente,fornecedor,colaborador'],
            'empresa_id' => ['nullable', 'exists:empresas,id'], // validação do id da empresa
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->numero = $request->numero;
        $user->tipo = $request->tipo;
        $user->empresa_id = $request->empresa_id; // salva a empresa associada

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'Utilizador atualizado com sucesso!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Utilizador excluído com sucesso!');
    }


    public function history(User $user)
    {
        $history = $user->loginHistory()->latest()->get();

        return view('users.history', compact('user', 'history'));
    }
}
