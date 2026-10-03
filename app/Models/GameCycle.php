<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GameCycle extends Model
{
    protected $fillable = [
        'pay_type',
        'start_date',
        'end_date',
        'contribution_amount',
        'status',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'contribution_amount' => 'decimal:2',
    ];

    public function collections(): HasMany
    {
        return $this->hasMany(Collection::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}