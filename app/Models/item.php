<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class item extends Model
{
    protected $fillable = [
        'name',
        'qty',
        'unit',
        'price'
    ];
}
