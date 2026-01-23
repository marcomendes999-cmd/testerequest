<?php
// app/Http/Controllers/EmpresaController.php
namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    public function index()
    {
        $empresas = Empresa::with('moradas')->orderBy('id','desc')->paginate(10);
        return view('empresas.index', compact('empresas'));
    }

    public function create()
    {
        return view('empresas.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string',
            'nif' => 'required|string',
            'email' => 'nullable|email',
            'telefone' => 'nullable|string',
            'num_cliente' => 'nullable|string',
            'moradas' => 'nullable|array',
            'moradas.*.rua' => 'required|string',
            'moradas.*.cidade' => 'required|string',
            'moradas.*.numero' => 'nullable|string',
            'moradas.*.codigo_postal' => 'nullable|string',
        ]);

        // Cria a empresa
        $empresa = Empresa::create([
            'nome' => $data['nome'],
            'nif' => $data['nif'],
            'num_cliente' => $data['num_cliente'],
            'email' => $data['email'] ?? null,
            'telefone' => $data['telefone'] ?? null,
        ]);

        // Cria moradas
        if(!empty($data['moradas'])){
            foreach($data['moradas'] as $morada){
              //  dd($morada);
                $empresa->moradas()->create([
                    'rua' => $morada['rua'],
                    'numero' => $morada['numero'] ?? null,
                    'cidade' => $morada['cidade'],
                    'codigo_postal' => $morada['codigo_postal'] ?? null,
                    'tipo' => 'empresa',
                ]);
            }
        }

        return redirect()->route('empresas.index')->with('success','Empresa criada com sucesso!');
    }

    public function show(Empresa $empresa)
    {
        $empresa->load('moradas');
        return view('empresas.show', compact('empresa'));
    }

    public function edit(Empresa $empresa)
    {
        return view('empresas.edit', compact('empresa'));
    }

    public function update(Request $request, Empresa $empresa)
    {
        $data = $request->validate([
            'nome' => 'required|string',
            'nif' => 'required|string|unique:empresas,nif,' . $empresa->id,
            'email' => 'nullable|email',
            'telefone' => 'nullable|string',
            'num_cliente' => 'nullable|string',
            'moradas' => 'nullable|array',
            'moradas.*.rua' => 'required|string',
            'moradas.*.cidade' => 'required|string',
            'moradas.*.numero' => 'nullable|string',
            'moradas.*.codigo_postal' => 'nullable|string',
        ]);

        // Atualiza dados da empresa
        $empresa->update([
            'nome' => $data['nome'],
            'nif' => $data['nif'],
            'num_cliente' => $data['num_cliente'],
            'email' => $data['email'] ?? null,
            'telefone' => $data['telefone'] ?? null,
        ]);

        // Atualiza moradas: apagar todas e recriar
        $empresa->moradas()->delete();
        if (!empty($data['moradas'])) {
            foreach ($data['moradas'] as $morada) {
                $empresa->moradas()->create([
                    'rua' => $morada['rua'],
                    'numero' => $morada['numero'] ?? null,
                    'cidade' => $morada['cidade'],
                    'codigo_postal' => $morada['codigo_postal'] ?? null,
                    'tipo' => 'empresa',
                ]);
            }
        }

        return redirect()->route('empresas.index')->with('success', 'Empresa atualizada com sucesso!');
    }

    public function destroy(Empresa $empresa)
    {
        $empresa->delete();
        return redirect()->route('empresas.index')->with('success', 'Empresa eliminada com sucesso!');
    }
}
