<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ticket_id',
        'un_id',
        'titulo',
        'descricao',
        'ordem',
        'estado_id',
        'prazo',
        'data_ini',
        'disponivel',
        'dependencia',
        'time',
    ];

    protected $casts = [
        'prazo' => 'date',
        'data_ini' => 'datetime',
        'disponivel' => 'boolean',
    ];

    /**
     * Relação: Task pertence a um Ticket
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Relação: Task pertence a um User (o operário a quem foi atribuída)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Alias mais legível para user() — é o operário responsável por executar a task.
     */
    public function operario()
    {
        return $this->user();
    }

    /**
     * Scope: tasks atribuídas a um determinado utilizador/operário.
     */
    public function scopeAtribuidasA($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Relação: Task pertence a um Estado
     */
    public function estado()
    {
        return $this->belongsTo(Status::class, 'estado_id');
    }

    /**
     * Relação: Task pertence a uma Unidade (un_id)
     */
    public function un()
    {
        return $this->belongsTo(Unidade::class, 'un_id');
    }

    /**
     * Relação: Task pode depender de outra Task
     */
    public function dependenciaTask()
    {
        return $this->belongsTo(Task::class, 'dependencia');
    }

    /**
     * Relação inversa: Tasks que dependem desta
     */
    public function dependentes()
    {
        return $this->hasMany(Task::class, 'dependencia');
    }

    /**
     * Accessor: formata a data do prazo (opcional)
     */
    public function getPrazoFormatadoAttribute()
    {
        return $this->prazo ? Carbon::parse($this->prazo)->format('d/m/Y') : null;
    }

    /**
     * Accessor: formata a data inicial (opcional)
     */
    public function getDataIniFormatadaAttribute()
    {
        return $this->data_ini ? Carbon::parse($this->data_ini)->format('d/m/Y H:i') : null;
    }
}
