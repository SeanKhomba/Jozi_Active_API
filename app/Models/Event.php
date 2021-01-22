<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'location',
        'date',
        'start_time',
        'end_time',
        'price',
        'avg_rating',
        'quantity_available',
        'active'
    ];

    public function category() {
        return $this->belongsTo(EventCategory::class,  'category_id', 'id');
    }

    public function eventMedia() {
        return $this->hasMany(EventMedia::class , 'event_id', 'id');
    }


}
