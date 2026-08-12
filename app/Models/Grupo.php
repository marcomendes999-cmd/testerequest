<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grupo extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'ordem', 'activo', 'colaborador_id'];

    public function colaborador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'colaborador_id');
    }

    public function postos(): HasMany
    {
        return $this->hasMany(Posto::class, 'grupo_id');
    }
}
