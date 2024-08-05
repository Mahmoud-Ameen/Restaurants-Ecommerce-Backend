<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'restaurant_id',
        'address_id'
    ];

    protected $hidden = [
        'password'
        ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
