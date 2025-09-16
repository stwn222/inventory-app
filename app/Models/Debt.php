<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Debt extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tanggal_utang',
        'tanggal_jatuh_tempo',
        'pemberi_utang',
        'catatan',
        'jumlah',
        'user_id',
        'status',
    ];

    /**
     * Get the user that recorded the debt.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}