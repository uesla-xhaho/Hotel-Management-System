<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'room_id',
        'hotel_id',
        'guests',
        'checkin',
        'checkout',
        'status',
        'comment',
    ];

    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function room(){
        return $this->belongsTo(Room::class);
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function payment(){
        return $this->hasOne(Payment::class);
    }
}
