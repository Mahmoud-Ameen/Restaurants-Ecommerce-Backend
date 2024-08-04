<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_id',
        'name',
        'description',
        'price'
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
