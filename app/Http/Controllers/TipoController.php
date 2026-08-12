<?php

namespace App\Http\Controllers;

use App\Models\Tipo;
use Illuminate\Http\Request;

class TipoController extends Controller
{
    public function index()
    {
        $tipos = Tipo::withCount('users')->orderBy('name')->paginate(10);

        return view('tipos.index', compact('tipos'));
    }

    public function create()
    {
        return view('tipos.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:255', 'unique:tipos,name']]);
        Tipo::create($data);

        return redirect()->route('tipos.index')->with('success', 'Tipo criado com sucesso.');
    }

    public function edit(Tipo $tipo)
    {
        return view('tipos.edit', compact('tipo'));
    }

    public function update(Request $request, Tipo $tipo)
    {
        if (strtolower($tipo->name) === 'colaborador' && strtolower($request->input('name')) !== 'colaborador') {
            return back()->withErrors(['name' => 'O tipo colaborador é usado nas regras de atribuição de grupos e não pode ser renomeado.']);
        }

        $data = $request->validate(['name' => ['required', 'string', 'max:255', 'unique:tipos,name,'.$tipo->id]]);
        $tipo->update($data);

        return redirect()->route('tipos.index')->with('success', 'Tipo atualizado com sucesso.');
    }

    public function destroy(Tipo $tipo)
    {
        if (strtolower($tipo->name) === 'colaborador') {
            return back()->with('error', 'O tipo colaborador é necessário para a atribuição de responsáveis aos grupos.');
        }

        if ($tipo->users()->exists()) {
            return back()->with('error', 'Não é possível remover um tipo que está associado a utilizadores.');
        }

        $tipo->delete();

        return redirect()->route('tipos.index')->with('success', 'Tipo removido com sucesso.');
    }
}
