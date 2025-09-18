<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Receivable extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tanggal_piutang',
        'tanggal_jatuh_tempo',
        'peminjam',
        'catatan',
        'jumlah',
        'user_id',
        'status',
    ];

    /**
     * Get the user that recorded the receivable.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}