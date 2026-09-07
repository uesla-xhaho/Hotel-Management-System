<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $guarded=[];

    protected $fillable = [
        'id',
        'personal_id',
        'name',
        'birthdate',
        'phone',
        'gender',
    ];

    public function booking(){
        return $this->hasMany(Booking::class);
    }
    
    public function payment(){
        return $this->hasMany(Payment::class);
    }
}
