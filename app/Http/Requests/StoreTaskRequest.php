<?php

namespace App\Http\Requests;

use App\Models\Task;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Ticket $ticket */
        $ticket = $this->route('ticket');

        return $this->user()->can('create', [Task::class, $ticket]);
    }

    public function rules(): array
    {
        /** @var Ticket $ticket */
        $ticket = $this->route('ticket');

        return [
            'titulo' => ['required', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
            'ordem' => ['nullable', 'integer', 'min:1'],
            'estado_id' => ['nullable', 'exists:statuses,id'],
            'prazo' => ['nullable', 'date'],
            'data_ini' => ['nullable', 'date'],
            'disponivel' => ['boolean'],
            'un_id' => ['nullable', 'integer', 'exists:unidades,id'],

            // Operário a quem a task fica atribuída (validamos que é mesmo
            // um técnico/admin no withValidator() abaixo — evita fazer isso
            // aqui com sub-queries manuais às tabelas do Spatie).
            'user_id' => ['nullable', 'exists:users,id'],

            // Uma task só pode depender de outra task do MESMO pedido.
            'dependencia' => [
                'nullable',
                'integer',
                Rule::exists('tasks', 'id')->where('ticket_id', $ticket?->id),
            ],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->filled('user_id')) {
                $operario = User::find($this->input('user_id'));
                if (!$operario || !$operario->hasAnyRole(['tecnico', 'admin'])) {
                    $validator->errors()->add('user_id', 'Só podes atribuir a task a um técnico ou administrador.');
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'dependencia.exists' => 'A task da qual esta depende tem de pertencer ao mesmo pedido.',
        ];
    }
}
