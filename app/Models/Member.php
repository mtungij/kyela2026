<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Member extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'address',
        'business_address',
        'amount',
        'type',
        'number_type',
        'penalty_per_day', 
        'start_date',
        'end_date',
        'pay_type'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function collections(): HasMany
    {
        return $this->hasMany(Collection::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function getTotalCollectionAmountAttribute()
    {
        return $this->amount * $this->number_type;
    }

    /**
     * Check if member should pay today based on start_date and end_date
     */
    public function shouldPayToday(): bool
    {
        if (!$this->start_date || !$this->end_date) {
            return false;
        }

        $today = now()->startOfDay();
        $startDate = $this->start_date->startOfDay();
        $endDate = $this->end_date->startOfDay();

        // Member should pay if today is between start_date and end_date (inclusive)
        return $today->between($startDate, $endDate);
    }

    /**
     * Check if payment period has started
     */
    public function hasPaymentStarted(): bool
    {
        if (!$this->start_date) {
            return false;
        }

        return now()->startOfDay()->greaterThanOrEqualTo($this->start_date->startOfDay());
    }

    /**
     * Check if payment period has ended
     */
    public function hasPaymentEnded(): bool
    {
        if (!$this->end_date) {
            return false;
        }

        return now()->startOfDay()->greaterThan($this->end_date->startOfDay());
    }
}
