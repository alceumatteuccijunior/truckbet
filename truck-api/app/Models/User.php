<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'cpf',
        'saldo',
        'status',
        'role', // Já existe no seu código, mantido.
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'saldo' => 'decimal:2', // Adicionando cast para decimal:2
    ];

    /**
     * Get the deposits for the user.
     */
    public function deposits() // NOVA RELAÇÃO: Depósitos
    {
        return $this->hasMany(Deposit::class);
    }

    /**
     * Get the user's bets.
     */
    public function userApostas() // Já existe no seu código, mantido.
    {
        return $this->hasMany(UserAposta::class);
    }
}
