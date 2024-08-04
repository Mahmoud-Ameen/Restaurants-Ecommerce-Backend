<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'phone',
        'email',
        'address_id'
    ];
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }
}
;
