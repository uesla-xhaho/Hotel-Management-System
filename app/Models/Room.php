<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    protected $guarded=[];

    protected $fillable = [
        'room_number',
        'room_floor',
        'room_position',
        'hotel_id',
        'category',
        'capacity',
        'price',
        'nrofbeds',
        'aircondition',
        'balcony',
        'description',
        'image',
    ];


    public function hotel(){
        return $this->belongsTo(Hotel::class);
    }
    public function booking(){
        return $this->hasMany(Booking::class);
    }

    public function getDisplayRoomNumberAttribute(): string
    {
        return (string) ($this->room_number ?? '');
    }
}
