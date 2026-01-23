<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GrupoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $grupos = Grupo::orderBy('ordem')->paginate(10);
        return view('grupos.index', compact('grupos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('grupos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'ordem' => 'nullable|string|max:4',
        ]);


        Grupo::create($validatedData);

        return redirect()->route('grupos.index')->with('success', 'Grupo criado com sucesso!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Grupo $grupo)
    {
        return view('grupos.edit', compact('grupo'));
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
        ]);

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
}
