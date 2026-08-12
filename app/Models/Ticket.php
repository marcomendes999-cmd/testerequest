<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    protected $fillable = [
        'num_operario',
        'idcategoria',
        'grupo_id',
        'idurgencia',
        'prazo',
        'titulo',
        'descricao',
        'code',
        'userid',
        'email',
        'datafecho',
        'prazoini',
        'prazotmp',
        'resolvido',
        'aprovado',
        'idestado',
        'area',
        'idsubcategoria',
        'pcnumber',
        'peso',
        'numero_fatura'
    ];

    protected $casts = [
        'prazo' => 'datetime',
        'datafecho' => 'datetime',
        'prazoini' => 'datetime',
        'prazotmp' => 'datetime',
        'aprovado' => 'integer',
    ];

    public function categoria()
    {
        return $this->belongsTo(Category::class, 'idcategoria');
    }

    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'grupo_id');
    }
    public function subcategoria()
    {
        return $this->belongsTo(Subcategory::class, 'idsubcategoria');
    }

    public function urgencia()
    {
        return $this->belongsTo(Urgency::class, 'idurgencia');
    }

    public function estado()
    {
        return $this->belongsTo(Status::class, 'idestado');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userid', 'id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->latest();
    }

    public function tasks()
    {
        return $this->hasMany(Task::class)->orderBy('ordem');
    }

    /*public function messages()
    {
        return $this->hasMany(Message::class);
    }
    */
}
