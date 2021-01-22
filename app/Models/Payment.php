<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $fillable = [
        'transaction_id',
        'payment_type_id',
        'user_id',
        'amount',
        'booking_id',
        'merch_id',
    ];

    public function paymentType() {
        return $this->belongsTo(PaymentType::class,  'payment_type_id', 'id');
    }

    public function user() {
        return $this->belongsTo(User::class,  'user_id', 'id');
    }
}
