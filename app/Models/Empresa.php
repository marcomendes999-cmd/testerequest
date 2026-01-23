<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'nif',
        'email',
        'telefone',
        'morada',
        'num_cliente',
    ];

    public function moradas()
    {
       return $this->hasMany(Morada::class,'id_externo');
    }
}
