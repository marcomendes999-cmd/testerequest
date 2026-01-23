<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Grupo;
use App\Models\Unidade;

class Posto extends Model
{
    use HasFactory;

    protected $table = 'postos';

    protected $fillable = ['name', 'grupo_id', 'ordem', 'activo'];

    public function grupo(): BelongsTo
    {
        return $this->belongsTo(Grupo::class, 'grupo_id');
    }

    public function unidades(): HasMany
    {
        return $this->hasMany(Unidade::class, 'posto_id');
    }
}
