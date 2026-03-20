<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenaltyForgiveness extends Model
{
    protected $fillable = [
        'member_id',
        'user_id',
        'forgiven_date',
        'amount',
        'notes',
    ];

    protected $casts = [
        'forgiven_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
