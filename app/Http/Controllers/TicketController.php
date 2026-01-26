<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Category;
use App\Models\Urgency;
use App\Models\Status;
use App\Models\File;
use App\Models\Message;
use App\Models\Grupo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    /**
     * Exibe uma lista de tickets.
     */
        public function index(Request $request)
        {
            // Recebe os filtros da query string
            $estado = $request->input('estado');
            $categoria = $request->input('categoria');
            $search = $request->input('search');

            // Query base com relações carregadas
            $query = Ticket::with(['categoria', 'urgencia', 'estado', 'user']);

            // Aplica filtros se houver
            if ($estado) {
                $query->where('idestado', $estado);
            }

            if ($categoria) {
                $query->where('idcategoria', $categoria);
            }

            if ($search) {
                $query->where('titulo', 'like', '%' . $search . '%');
            }

            // Ordena por mais recente e pagina
            $tickets = $query->latest()->paginate(10)->withQueryString();

            // Também precisamos passar os estados e categorias para os selects do filtro
            $estados = Status::all();
            $categorias = Category::all();

            //dd($tickets);
            return view('tickets.index', compact('tickets', 'estados', 'categorias'));
        }


    /**
     * Exibe o formulário para criar um novo ticket.
     */
    public function create()
    {
        // Pega os dados das tabelas relacionadas para preencher os dropdowns
        $categorias = Category::orderBy('ordem', 'asc')->get();
        $urgencias = Urgency::orderBy('ordem', 'asc')->get();
        $estados = Status::orderBy('ordem', 'asc')->get();


        return view('tickets.create', compact('categorias', 'urgencias', 'estados'));
    }


    /**
     * Armazena um novo ticket no banco de dados.
     */
    public function store(Request $request)
    {
        // Regras de validação para os dados do formulário
        $validatedData = $request->validate([
            'num_operario' => 'nullable|string',
            'idcategoria' => 'required|exists:categories,id',
            'grupo_id' => 'nullable|exists:grupos,id',
            'idurgencia' => 'required|exists:urgencies,id',
            'prazo' => 'nullable|date',
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'code' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'datafecho' => 'nullable|date',
            'prazoini' => 'nullable|date',
            'prazotmp' => 'nullable|date',
            'resolvido' => 'nullable|boolean',
            'aprovado' => 'nullable|boolean',
            'idestado' => 'nullable|exists:statuses,id',
            'area' => 'nullable|string|max:255',
            'idsubcategoria' => 'nullable|exists:subcategories,id',
            'pcnumber' => 'nullable|string|max:255',
            'peso' => 'nullable|integer',
            'files.*' => 'nullable|file|max:2048', // Regra para múltiplos ficheiros
        ]);
        
        $validatedData['userid'] = Auth::id();
        $validatedData['email'] = Auth::user()->email;
        $validatedData['num_operario'] = Auth::id();
        $validatedData['idestado'] = 1;

        // Cria o ticket com os dados validados
        $ticket = Ticket::create($validatedData);

        // Lógica para salvar múltiplos ficheiros
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('tickets/files', 'public');
                $ticket->files()->create([
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                ]);
            }
        }

        return redirect()->route('tickets.index')->with('success', 'Ticket criado com sucesso!');
    }

    /**
     * Exibe os detalhes de um ticket específico.
     */
    public function show(Ticket $ticket)
    {
        return view('tickets.show', compact('ticket'));
    }

    /**
     * Exibe o formulário para editar um ticket existente.
     */
    public function edit(Ticket $ticket)
    {
        // Pega os dados das tabelas relacionadas para preencher os dropdowns
        $categorias = Category::orderBy('ordem', 'asc')->get();
        $urgencias = Urgency::orderBy('ordem', 'asc')->get();
        $estados = Status::orderBy('ordem', 'asc')->get();

        return view('tickets.edit', compact('ticket', 'categorias', 'urgencias', 'estados'));
    }

    /**
     * Atualiza um ticket no banco de dados.
     */
    public function update(Request $request, Ticket $ticket)
    {
        $validatedData = $request->validate([
            'num_operario' => 'nullable|string',
            'idcategoria' => 'required|exists:categories,id',
            'grupo_id' => 'nullable|exists:grupos,id',
            'idurgencia' => 'required|exists:urgencies,id',
            'prazo' => 'nullable|date',
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'code' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'datafecho' => 'nullable|date',
            'prazoini' => 'nullable|date',
            'prazotmp' => 'nullable|date',
            'resolvido' => 'nullable|boolean',
            'aprovado' => 'nullable|boolean',
            'idestado' => 'required|exists:statuses,id',
            'area' => 'nullable|string|max:255',
            'idsubcategoria' => 'nullable|exists:subcategories,id',
            'pcnumber' => 'nullable|string|max:255',
            'peso' => 'nullable|integer',
            'numero_fatura' => 'nullable|string|max:255',
        ]);
        
        $validatedData['userid'] = Auth::id();

        $ticket->update($validatedData);

        return redirect()->route('tickets.index')->with('success', 'Ticket atualizado com sucesso!');
    }

    /**
     * Remove um ticket do banco de dados.
     */
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();

        return redirect()->route('tickets.index')->with('success', 'Ticket excluído com sucesso!');
    }
    
    /**
     * Armazena uma nova mensagem no banco de dados.
     */
    public function storeMessage(Request $request, Ticket $ticket)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $ticket->messages()->create([
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        return redirect()->route('tickets.show', $ticket)->with('success', 'Mensagem enviada com sucesso!');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

}
