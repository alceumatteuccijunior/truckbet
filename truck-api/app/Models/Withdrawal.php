<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'status',
        'payment_details',
        'processed_at',
    ];

    protected $casts = [
        'processed_at' => 'datetime',
        'amount' => 'decimal:2',
        'payment_details' => 'array', // Adicionado para que os detalhes do pagamento sejam tratados como array/JSON
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
