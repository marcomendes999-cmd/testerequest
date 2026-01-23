<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Morada extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_externo',
        'tipo',
        'rua',
        'numero',
        'cidade',
        'codigo_postal',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class,'id_externo');
    }

}
