<?php

namespace App\Http\Controllers;

use App\Models\Posto;
use App\Models\Grupo;
use Illuminate\Http\Request;

class PostoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $postos = Posto::with('grupo')->orderBy('ordem')->paginate(10);
        return view('postos.index', compact('postos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $grupos = Grupo::all();
        return view('postos.create', compact('grupos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'grupo_id' => 'required|exists:grupos,id',
            'ordem' => 'nullable|string|max:4',
            
        ]);

     

        Posto::create($validatedData);

        return redirect()->route('postos.index')->with('success', 'Posto de trabalho criado com sucesso!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Posto $posto)
    {
        $grupos = Grupo::all();
        return view('postos.edit', compact('posto', 'grupos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Posto $posto)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'grupo_id' => 'required|exists:grupos,id',
            'ordem' => 'nullable|string|max:4',
            'activo' => 'nullable|integer',
        ]);

         $validatedData['activo'] = $request->has('activo') ? 1 : 0;

        $posto->update($validatedData);

        return redirect()->route('postos.index')->with('success', 'Posto de trabalho atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Posto $posto)
    {
        $posto->delete();
        return redirect()->route('postos.index')->with('success', 'Posto de trabalho removido com sucesso!');
    }
}
