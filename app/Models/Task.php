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

    /**
     * Relação: Task pertence a um Ticket
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Relação: Task pertence a um User (operário/técnico)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relação: Task pertence a um Estado
     */
    public function estado()
    {
        return $this->belongsTo(Status::class, 'estado_id');
    }

    /**
     * Relação opcional: Task pertence a um Posto/Unidade (un_id)
     * — troca 'Un' pelo nome real do teu model/tabela se existir
     */
    public function un()
    {
        return $this->belongsTo(Un::class, 'un_id');
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
