<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Collection extends Model
{
    protected $fillable = [
        'member_id',
        'total_amount',
        'amount_paid',
        'balance',
        'total_penalty',
        'penalty_paid',
        'penalty_balance',
        'last_payment_date',
        'status',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'balance' => 'decimal:2',
        'total_penalty' => 'decimal:2',
        'penalty_paid' => 'decimal:2',
        'penalty_balance' => 'decimal:2',
        'last_payment_date' => 'date',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Calculate missed days and penalty based on member type
     */
    public function calculatePenalty()
    {
        $member = $this->member;
        
        if (!$member || $member->penalty_per_day <= 0) {
            return 0;
        }

        $lastPaymentDate = $this->last_payment_date ?? $this->created_at;
        $today = now();
        
        // Calculate days passed based on member type
        $daysPassed = $lastPaymentDate->diffInDays($today);
        
        // Start counting from second day (first day is grace period)
        if ($daysPassed <= 1) {
            return 0;
        }
        
        $missedDays = 0;
        
        switch ($member->type) {
            case 'daily':
                // Every day after first day
                $missedDays = $daysPassed - 1;
                break;
                
            case 'weekly':
                // Every 7 days
                $weeksPassed = floor($daysPassed / 7);
                if ($weeksPassed > 0) {
                    $missedDays = $weeksPassed;
                }
                break;
                
            case 'monthly':
                // Every 30 days
                $monthsPassed = floor($daysPassed / 30);
                if ($monthsPassed > 0) {
                    $missedDays = $monthsPassed;
                }
                break;
        }
        
        return $missedDays * $member->penalty_per_day;
    }

    /**
     * Get current total penalty (accumulated + unpaid)
     */
    public function getCurrentPenaltyBalance()
    {
        $this->total_penalty = $this->calculatePenalty();
        $this->penalty_balance = $this->total_penalty - $this->penalty_paid;
        $this->save();
        
        return $this->penalty_balance;
    }
}
