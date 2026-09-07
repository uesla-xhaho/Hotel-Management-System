<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $guarded=[];

    protected $fillable = [
        'name',
        'birthdate',
        'gender',
        'phone',
        'email',
        'address',
        'role',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
}
