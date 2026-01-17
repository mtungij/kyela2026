<?php

namespace App\Console\Commands;

use App\Models\Collection;
use App\Models\Member;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AddPenalts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'penalties:add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add penalty amount to members who did not make payment today';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting penalty calculation for members without payment today...');
        
        $today = Carbon::today();
        $penalizedCount = 0;
        
        // Get all pending or active collections
        $collections = Collection::whereIn('status', ['pending', 'active', 'partial'])
            ->where('balance', '>', 0)
            ->with('member')
            ->get();
        
        foreach ($collections as $collection) {
            $member = $collection->member;
            
            // Skip if member has no penalty per day set
            if (!$member || $member->penalty_per_day <= 0) {
                continue;
            }
            
            // Check if member made a payment today
            $hasPaymentToday = Payment::where('member_id', $member->id)
                ->whereDate('payment_date', $today)
                ->exists();
            
            // If no payment today, calculate and add penalty
            if (!$hasPaymentToday) {
                $lastPaymentDate = $collection->last_payment_date ?? $collection->created_at;
                
                // Only add penalty if at least one day has passed since last payment
                if ($lastPaymentDate->lt($today)) {
                    // Check if member should pay today based on their type
                    $shouldPayToday = $this->shouldPayToday($member, $lastPaymentDate, $today);
                    
                    if ($shouldPayToday) {
                        // Add penalty
                        $collection->total_penalty += $member->penalty_per_day;
                        $collection->penalty_balance += $member->penalty_per_day;
                        $collection->save();
                        
                        $penalizedCount++;
                        
                        $this->line("Added penalty of {$member->penalty_per_day} to {$member->name}");
                    }
                }
            }
        }
        
        $this->info("Penalty calculation completed. {$penalizedCount} members penalized.");
        
        return Command::SUCCESS;
    }
    
    /**
     * Determine if member should pay today based on their payment type
     */
    private function shouldPayToday(Member $member, Carbon $lastPaymentDate, Carbon $today): bool
    {
        switch ($member->type) {
            case 'daily':
                // Should pay every day
                return true;
                
            case 'weekly':
                // Should pay once per week (every 7 days)
                $daysSinceLastPayment = $lastPaymentDate->diffInDays($today);
                return $daysSinceLastPayment >= 7;
                
            case 'monthly':
                // Should pay once per month (every 30 days)
                $daysSinceLastPayment = $lastPaymentDate->diffInDays($today);
                return $daysSinceLastPayment >= 30;
                
            default:
                return false;
        }
    }
}
