<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserLoginHistory extends Model
{
    use HasFactory;

    protected $table = 'user_login_history';

    protected $fillable = [
        'user_id',
        'login_at',
        'logout_at',
        'duration',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'login_at' => 'datetime',
        'logout_at' => 'datetime',
    ];

    /**
     * Define a relação com o utilizador.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
