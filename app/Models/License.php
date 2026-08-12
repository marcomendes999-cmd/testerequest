<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'max_users',
        'expires_at',
        'ativo',
    ];

    protected $casts = [
        'expires_at' => 'date',
        'ativo' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query
            ->where('ativo', true)
            ->whereDate('expires_at', '>=', today());
    }

    /**
     * Get the users for the license.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
