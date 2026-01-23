<?php

namespace App\Http\Controllers;

use App\Models\Unidade;
use App\Models\Posto;
use App\Models\Grupo;
use Illuminate\Http\Request;

class UnidadeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Obtém todos os grupos e postos para os dropdowns do filtro
        $grupos = Grupo::all();
        $postos = Posto::all();

        // Inicia a query para as unidades
        $query = Unidade::with(['posto.grupo'])->orderBy('ordem');

        // Aplica os filtros, se existirem
        if ($request->filled('grupo_id')) {
            $query->whereHas('posto', function($q) use ($request) {
                $q->where('grupo_id', $request->grupo_id);
            });
        }
        
        if ($request->filled('posto_id')) {
            $query->where('posto_id', $request->posto_id);
        }

        $unidades = $query->paginate(10)->appends($request->query());
        
        return view('unidades.index', compact('unidades', 'grupos', 'postos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $postos = Posto::orderBy('ordem')->get();
        return view('unidades.create', compact('postos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'posto_id' => 'required|exists:postos,id',
            'capacidade' => 'required|numeric',
            'ordem' => 'nullable|string|max:4',
          
        ]);

  
        Unidade::create($validatedData);

        return redirect()->route('unidades.index')->with('success', 'Unidade criada com sucesso!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Unidade $unidade)
    {
        $postos = Posto::orderBy('ordem')->get();
        return view('unidades.edit', compact('unidade', 'postos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Unidade $unidade)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'posto_id' => 'required|exists:postos,id',
            'capacidade' => 'required|numeric',
            'ordem' => 'nullable|string|max:4',
            'activo' => 'nullable|boolean',
        ]);

        $validatedData['activo'] = $request->has('activo') ? 1 : 0;

        $unidade->update($validatedData);

        return redirect()->route('unidades.index')->with('success', 'Unidade atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Unidade $unidade)
    {
        $unidade->delete();
        return redirect()->route('unidades.index')->with('success', 'Unidade removida com sucesso!');
    }
}
