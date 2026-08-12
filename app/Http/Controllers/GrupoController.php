<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\User;
use App\Models\Tipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class GrupoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $grupos = Grupo::with('colaborador')->orderBy('ordem')->paginate(10);
        return view('grupos.index', compact('grupos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $colaboradores = $this->colaboradores();

        return view('grupos.create', compact('colaboradores'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'ordem' => 'nullable|string|max:4',
            'colaborador_id' => 'nullable|exists:users,id',
        ]);

        $this->validarColaborador($validatedData['colaborador_id'] ?? null, $request);


        Grupo::create($validatedData);

        return redirect()->route('grupos.index')->with('success', 'Grupo criado com sucesso!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Grupo $grupo)
    {
        $colaboradores = $this->colaboradores();

        return view('grupos.edit', compact('grupo', 'colaboradores'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Grupo $grupo)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'ordem' => 'nullable|string|max:4',
            'activo' => 'nullable|integer',
            'colaborador_id' => 'nullable|exists:users,id',
        ]);

        $this->validarColaborador($validatedData['colaborador_id'] ?? null, $request);

       // 
        $validatedData['activo'] = $request->has('activo') ? 1 : 0;

      

        $grupo->update($validatedData);

        return redirect()->route('grupos.index')->with('success', 'Grupo atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Grupo $grupo)
    {
        $grupo->delete();

        return redirect()->route('grupos.index')->with('success', 'Grupo removido com sucesso!');
    }

    private function colaboradores()
    {
        return User::where('tipo_id', Tipo::colaboradorId())->orderBy('name')->get(['id', 'name', 'email']);
    }

    private function validarColaborador(?int $colaboradorId, Request $request): void
    {
        if ($colaboradorId && ! User::whereKey($colaboradorId)->where('tipo_id', Tipo::colaboradorId())->exists()) {
            throw ValidationException::withMessages([
                'colaborador_id' => 'O responsável selecionado tem de ser um utilizador do tipo colaborador.',
            ]);
        }
    }
}
