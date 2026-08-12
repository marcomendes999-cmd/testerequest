<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Urgency;
use App\Models\Status;
use App\Models\File;
use App\Models\Message;
use App\Models\Grupo;
use App\Models\User;
use App\Models\Tipo;
use App\Notifications\NewTicketForGroup;
use App\Notifications\NewTicketSubmitted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            $grupoId = $request->input('grupo_id');
            $search = $request->input('search');
            $aprovado = $request->input('aprovado');
            $submetidoPor = $request->input('submetido_por');

            // Query base com relaÃ§Ãµes carregadas
            $query = Ticket::with(['grupo', 'urgencia', 'estado', 'user']);

            // Um utilizador so ve pedidos que submeteu, pedidos do grupo pelo
            // qual e responsavel, ou pedidos com tasks atribuidas a si.
            $query->where(function ($tickets) {
                $tickets->where('userid', Auth::id())
                    ->orWhereHas('grupo', fn ($grupo) => $grupo->where('colaborador_id', Auth::id()))
                    ->orWhereHas('tasks', fn ($task) => $task->where('user_id', Auth::id()));
            });
            // Aplica filtros se houver
            if ($estado) {
                $query->where('idestado', $estado);
            }

            if ($grupoId) {
                $query->where('grupo_id', $grupoId);
            }

            if ($search) {
                $query->where('titulo', 'like', '%' . $search . '%');
            }

            if (in_array($aprovado, ['1', '2'], true)) {
                $query->where('aprovado', $aprovado);
            }

            if ($submetidoPor) {
                $query->whereHas('user', fn ($user) => $user->where('name', 'like', '%'.$submetidoPor.'%'));
            }

            // Ordena por mais recente e pagina
            $tickets = $query->latest()->paginate(10)->withQueryString();

            // TambÃ©m precisamos passar os estados e categorias para os selects do filtro
            $estados = Status::all();
            $grupos = Grupo::orderBy('name')->get();

            //dd($tickets);
            return view('tickets.index', compact('tickets', 'estados', 'grupos'));
        }


    /**
     * Exibe o formulÃ¡rio para criar um novo ticket.
     */
    public function create()
    {
        // Pega os dados das tabelas relacionadas para preencher os dropdowns
        $grupos = Grupo::orderBy('name')->get();
        $urgencias = Urgency::orderBy('ordem', 'asc')->get();
        $estados = Status::orderBy('ordem', 'asc')->get();


        return view('tickets.create', compact('grupos', 'urgencias', 'estados'));
    }


    /**
     * Armazena um novo ticket no banco de dados.
     */
    public function store(Request $request)
    {
        // Regras de validaÃ§Ã£o para os dados do formulÃ¡rio
        $validatedData = $request->validate([
            'num_operario' => 'nullable|string',
            'grupo_id' => 'required|exists:grupos,id',
            'idurgencia' => 'required|exists:urgencies,id',
            'prazo' => 'nullable|date',
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
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
            'files.*' => 'nullable|file|max:2048', // Regra para mÃºltiplos ficheiros
        ]);
        
        $validatedData['userid'] = Auth::id();
        $validatedData['email'] = Auth::user()->email;
        $validatedData['num_operario'] = Auth::id();
        $validatedData['idestado'] = 1;
        $validatedData['idcategoria'] = null;
        $validatedData['aprovado'] = 1;

        $ticket = DB::transaction(function () use ($request, $validatedData) {
            $validatedData['code'] = $this->proximoCodigoDePedido();

            // Cria o ticket com os dados validados
            $ticket = Ticket::create($validatedData);

            // LÃ³gica para salvar mÃºltiplos ficheiros
            // (guardados no disco 'local', que nÃ£o Ã© acessÃ­vel diretamente por URL â€”
            // o download passa sempre pela rota tickets.files.download, que verifica a Policy)
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $path = $file->store('tickets/' . $ticket->id, 'local');
                    $ticket->files()->create([
                        'file_name' => $file->getClientOriginalName(),
                        'file_path' => $path,
                    ]);
                }
            }

            return $ticket;
        });

        $ticket->load('grupo.colaborador.tipo');

        $responsavelDoGrupo = $ticket->grupo?->colaborador;
        if ($responsavelDoGrupo && (int) $responsavelDoGrupo->tipo_id === Tipo::colaboradorId()) {
            $responsavelDoGrupo->notify(new NewTicketForGroup($ticket));
        }

        Auth::user()->notify(new NewTicketSubmitted($ticket));

        return redirect()->route('tickets.show', $ticket)->with('success', 'Ticket criado com sucesso!');
    }

    /**
     * Exibe os detalhes de um ticket especÃ­fico.
     */
    public function show(Ticket $ticket)
    {
        $this->authorize('view', $ticket);

        $ticket->load(['tasks.user', 'tasks.estado']);

        // SÃ³ interessa Ã  equipa tÃ©cnica, mas carregar Ã© barato e simplifica a view
        $tecnicos = User::role(['tecnico', 'admin'])->orderBy('name')->get();
        $estados = Status::orderBy('ordem')->get();

        return view('tickets.show', compact('ticket', 'tecnicos', 'estados'));
    }

    /**
     * Exibe o formulÃ¡rio para editar um ticket existente.
     */
    public function edit(Ticket $ticket)
    {
        $this->authorize('update', $ticket);

        // Pega os dados das tabelas relacionadas para preencher os dropdowns
        $grupos = Grupo::orderBy('name')->get();
        $urgencias = Urgency::orderBy('ordem', 'asc')->get();
        $estados = Status::orderBy('ordem', 'asc')->get();
        $tecnicos = User::role(['tecnico', 'admin'])->orderBy('name')->get();
        $ticket->load(['tasks.user', 'tasks.estado']);

        return view('tickets.edit', compact('ticket', 'grupos', 'urgencias', 'estados', 'tecnicos'));
    }

    /**
     * Atualiza um ticket no banco de dados.
     */
    public function update(Request $request, Ticket $ticket)
    {
        $this->authorize('update', $ticket);

        $validatedData = $request->validate([
            'prazo' => 'nullable|date',
            'descricao' => 'required|string',
        ]);

        $ticket->update($validatedData);

        return redirect()->route('tickets.index')->with('success', 'Ticket atualizado com sucesso!');
    }

    public function updateApproval(Request $request, Ticket $ticket)
    {
        $this->authorize('approve', $ticket);

        if ((int) $ticket->aprovado === 2) {
            return back()->with('error', 'Este pedido já foi aprovado e a aprovação não pode ser retirada.');
        }

        $request->validate([
            'aprovado' => 'required|integer|in:2',
        ]);

        $ticket->update(['aprovado' => 2]);

        $momento = ucfirst(now()->locale('pt_PT')->translatedFormat('d \d\e F \d\e Y \a\s H:i'));
        $ticket->messages()->create([
            'user_id' => Auth::id(),
            'content' => 'Pedido aprovado em '.$momento.'.',
        ]);

        return back()->with('success', 'Pedido aprovado com sucesso.');
    }

    private function proximoCodigoDePedido(): string
    {
        $prefixo = 'PS'.now()->format('Y');
        $ultimoCodigo = Ticket::where('code', 'like', $prefixo.'%')
            ->lockForUpdate()
            ->orderByDesc('code')
            ->value('code');

        $sequencia = $ultimoCodigo ? ((int) substr($ultimoCodigo, -4)) + 1 : 1;

        if ($sequencia > 9999) {
            throw new \RuntimeException('Foi atingido o limite anual de cÃ³digos de pedidos.');
        }

        return $prefixo.str_pad((string) $sequencia, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Remove um ticket do banco de dados.
     */
    public function destroy(Ticket $ticket)
    {
        $this->authorize('delete', $ticket);

        $ticket->delete();

        return redirect()->route('tickets.index')->with('success', 'Ticket excluÃ­do com sucesso!');
    }
    
    /**
     * Armazena uma nova mensagem no banco de dados.
     */
    public function storeMessage(Request $request, Ticket $ticket)
    {
        $this->authorize('addMessage', $ticket);

        $request->validate([
            'content' => 'required|string',
        ]);

        $ticket->messages()->create([
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        return redirect()->route('tickets.show', $ticket)->with('success', 'Mensagem enviada com sucesso!');
    }

    /**
     * Anexa um ou mais ficheiros a um ticket jÃ¡ existente.
     */
    public function storeFile(Request $request, Ticket $ticket)
    {
        $this->authorize('manageFiles', $ticket);

        $request->validate([
            'files' => 'required|array',
            'files.*' => [
                'required',
                'file',
                'max:10240', // 10 MB
                'mimes:pdf,doc,docx,xls,xlsx,csv,jpg,jpeg,png,zip,txt',
            ],
        ]);

        DB::transaction(function () use ($request, $ticket) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('tickets/' . $ticket->id, 'local');
                $ticket->files()->create([
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                ]);
            }
        });

        return redirect()->route('tickets.show', $ticket)->with('success', 'Ficheiro(s) anexado(s) com sucesso!');
    }

    /**
     * Remove um ficheiro de um ticket (disco + registo na base de dados).
     */
    public function deleteFile(Ticket $ticket, File $file)
    {
        $this->authorize('manageFiles', $ticket);

        // Garante que o ficheiro pertence mesmo a este ticket
        // (evita que alguÃ©m apague um ficheiro de outro ticket trocando o id na rota)
        abort_unless($file->ticket_id === $ticket->id, 404);

        Storage::disk('local')->delete($file->file_path);
        $file->delete();

        return redirect()->route('tickets.show', $ticket)->with('success', 'Ficheiro removido com sucesso!');
    }

    /**
     * Faz o download de um ficheiro de um ticket, sempre passando pela Policy
     * (o disco 'local' nÃ£o Ã© acessÃ­vel diretamente por URL).
     */
    public function downloadFile(Ticket $ticket, File $file)
    {
        $this->authorize('manageFiles', $ticket);

        abort_unless($file->ticket_id === $ticket->id, 404);

        return Storage::disk('local')->download($file->file_path, $file->file_name);
    }
}
