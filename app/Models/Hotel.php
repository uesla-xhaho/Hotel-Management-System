<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;
    protected $guarded=[];
    protected $fillable = [
        'name',
        'code',
        'address',
        'phone',
        'email',
        'year',
        'image',
    ];

    public function room(){
        return $this->hasMany(Room::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
