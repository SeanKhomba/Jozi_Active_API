<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $fillable = [
        'event_id',
        'user_id',
        'quantity',
        'food_option_id',
        'status',
        'password',
    ];

    public function user() {
        return $this->belongsTo(User::class,  'user_id', 'id');
    }
    public function event() {
        return $this->belongsTo(Event::class,  'event_id', 'id');
    }
}
