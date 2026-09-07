<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $guarded=[];

    protected $fillable = [
        'price',
        'method',
        'status',
        'duration',
    ];


    public function customer(){
        return $this->belongsTo(Customer::class);
    }
    public function booking(){
        return $this->belongsTo(Booking::class);
    }
}
