<?php

namespace App\Http\Requests;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Task $task */
        $task = $this->route('task');

        return $this->user()->can('update', $task);
    }

    public function rules(): array
    {
        /** @var Task $task */
        $task = $this->route('task');

        return [
            'titulo' => ['required', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
            'ordem' => ['nullable', 'integer', 'min:1'],
            'estado_id' => ['nullable', 'exists:statuses,id'],
            'prazo' => ['nullable', 'date'],
            'data_ini' => ['nullable', 'date'],
            'disponivel' => ['boolean'],
            'un_id' => ['nullable', 'integer', 'exists:unidades,id'],
            'user_id' => ['nullable', 'exists:users,id'],

            // Depende de outra task do mesmo pedido, mas nunca de si própria.
            'dependencia' => [
                'nullable',
                'integer',
                Rule::exists('tasks', 'id')->where('ticket_id', $task?->ticket_id),
                Rule::notIn([$task?->id]),
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
            'dependencia.not_in' => 'Uma task não pode depender de si própria.',
        ];
    }
}
