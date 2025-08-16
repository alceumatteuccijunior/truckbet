<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'pushinpay_transaction_id',
        'amount',
        'status',
        'pix_qrcode',
        'pix_qrcode_base64',
        'webhook_data',
    ];

    // Relação com o usuário que fez o depósito
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
