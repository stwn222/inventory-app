<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockCard extends Model
{
    protected $fillable = [
        'items_id',
        'qty',
        'note',
        'description',
        'vendor'
    ];


}
