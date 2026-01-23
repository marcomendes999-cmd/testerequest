<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Unidade extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'capacidade', 'posto_id', 'ordem', 'activo'];

    public function posto(): BelongsTo
    {
        return $this->belongsTo(Posto::class, 'posto_id');
    }
}
