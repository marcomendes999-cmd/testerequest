<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Grupo extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'ordem', 'activo'];

    public function postos(): HasMany
    {
        return $this->hasMany(PostoTrabalho::class, 'grupo_id');
    }
}
