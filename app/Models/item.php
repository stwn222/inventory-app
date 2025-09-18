<?php

namespace App\Models;

use App\Models\StockCard;
use Illuminate\Database\Eloquent\Model;

class item extends Model
{
    protected $fillable = [
        'name',
        'qty',
        'unit',
        'price'
    ];

        public function stockCards()
        {
        return $this->hasMany(StockCard::class, 'items_id');
        }

}

