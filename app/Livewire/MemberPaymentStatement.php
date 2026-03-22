<?php

namespace App\Livewire;

use App\Models\Collection;
use App\Models\Member;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class MemberPaymentStatement extends Component
{
    public Member $member;
    public ?Collection $collection = null;
    public string $paymentDate;
    public string $dateSearch = '';
    public bool $showPaymentModal = false;
    public ?array $selectedPayment = null;
    public string $modalAmount = '';
    public string $modalPaymentDate = '';

    public function mount(Member $member): void
    {
        $this->member = $member;
        $this->collection = $member->collections()->orderByDesc('id')->first();
        $this->paymentDate = now()->toDateString();
    }

    public function openPaymentModal(array $payment): void
    {
        $this->selectedPayment = $payment;
        $this->modalAmount = '';
        $this->modalPaymentDate = $payment['date_string'] ?? now()->toDateString();
        $this->showPaymentModal = true;
    }

    public function closePaymentModal(): void
    {
        $this->showPaymentModal = false;
        $this->selectedPayment = null;
        $this->modalAmount = '';
    }

    public function submitPaymentFromModal(): void
    {
        $this->validate([
            'modalAmount' => 'required|numeric|min:0.01',
            'modalPaymentDate' => 'required|date',
        ]);

        if (!$this->collection || $this->modalAmount <= 0) {
            return;
        }

        \DB::transaction(function () {
            Payment::create([
                'member_id' => $this->member->id,
                'collection_id' => $this->collection->id,
                'user_id' => auth()->id(),
                'amount' => $this->modalAmount,
                'payment_type' => 'regular',
                'payment_date' => $this->modalPaymentDate,
                'notes' => null,
            ]);

            // Update collection balance using raw calculation
            $newAmountPaid = (float) $this->collection->amount_paid + (float) $this->modalAmount;
            $newBalance = (float) $this->collection->total_amount - $newAmountPaid;

            Collection::where('id', $this->collection->id)->update([
                'amount_paid' => $newAmountPaid,
                'balance' => $newBalance,
                'status' => $newBalance <= 0 ? 'completed' : ($newAmountPaid > 0 ? 'partial' : 'pending'),
            ]);
        });

        $this->collection = $this->collection->fresh();
        $this->closePaymentModal();
        session()->flash('success', 'Malipo yamefanikiwa kurekodiwa!');
    }

    /**
     * Generate payment schedule based on member type, start_date, and current date/overpayment
     */
    public function getPaymentScheduleProperty()
    {
        if (!$this->member->start_date || !$this->member->type || !$this->member->amount) {
            return [];
        }

        $schedule = [];
        $currentDate = Carbon::parse($this->member->start_date)->startOfDay();
        $today = now()->endOfDay();
        $expectedAmount = $this->member->amount;

        // Use total accumulated paid amount and fill from start_date forward
        $remainingPaid = (float) Payment::where('member_id', $this->member->id)
            ->where('payment_type', 'regular')
            ->sum('amount');

        $closedDates = DB::table('closed_accounts')
            ->whereDate('date', '>=', Carbon::parse($this->member->start_date)->toDateString())
            ->pluck('date')
            ->map(fn ($date) => Carbon::parse($date)->toDateString())
            ->flip();

        // First pass: build schedule from start_date to today
        while ($currentDate <= $today) {
            $scheduleDate = $currentDate->toDateString();
            $closedOnDate = isset($closedDates[$scheduleDate]);

            if ($remainingPaid >= $expectedAmount) {
                $displayAmount = $expectedAmount;
                $remainingPaid -= $expectedAmount;
                $isPaid = true;
            } elseif ($remainingPaid > 0) {
                $displayAmount = $remainingPaid;
                $remainingPaid = 0;
                $isPaid = false; // partial — not fully covered
            } else {
                $displayAmount = null;
                $isPaid = false;
            }

            $schedule[] = [
                'date' => $currentDate->format('d/m/Y'),
                'date_string' => $scheduleDate,
                'amount' => $displayAmount,
                'is_paid' => $isPaid,
                'actual_payment' => null,
                'user' => null,
                'is_closed' => $closedOnDate,
                'penalty_charged' => $closedOnDate && !$isPaid,
            ];

            match ($this->member->type) {
                'daily' => $currentDate->addDay(),
                'weekly' => $currentDate->addWeek(),
                'monthly' => $currentDate->addMonth(),
                default => $currentDate->addDay(),
            };
        }

        // Second pass: extend schedule for future periods if overpayment exists
        while ($remainingPaid > 0) {
            $scheduleDate = $currentDate->toDateString();

            if ($remainingPaid >= $expectedAmount) {
                $displayAmount = $expectedAmount;
                $remainingPaid -= $expectedAmount;
            } else {
                $displayAmount = $remainingPaid;
                $remainingPaid = 0;
            }

            $schedule[] = [
                'date' => $currentDate->format('d/m/Y'),
                'date_string' => $scheduleDate,
                'amount' => $displayAmount,
                'is_paid' => true,
                'actual_payment' => null,
                'user' => null,
                'is_closed' => false,
                'penalty_charged' => false,
            ];

            match ($this->member->type) {
                'daily' => $currentDate->addDay(),
                'weekly' => $currentDate->addWeek(),
                'monthly' => $currentDate->addMonth(),
                default => $currentDate->addDay(),
            };
        }

        return $schedule;
    }

    public function getSummaryProperty(): array
    {
        $regularPayments = Payment::where('member_id', $this->member->id)
            ->where('payment_type', 'regular');

        return [
            'total_regular' => $regularPayments->count(),
            'total_amount' => $regularPayments->sum('amount'),
            'expected_periods' => count($this->paymentSchedule),
            'paid_periods' => collect($this->paymentSchedule)->filter(fn($p) => $p['is_paid'])->count(),
        ];
    }

    public function getFilteredPaymentScheduleProperty()
    {
        $schedule = collect($this->paymentSchedule);

        if (trim($this->dateSearch) === '') {
            return $schedule;
        }

        $searchDate = trim($this->dateSearch);

        return $schedule->filter(function ($item) use ($searchDate) {
            return ($item['date_string'] ?? null) === $searchDate;
        })->values();
    }

    public function getCollectionPenaltyStatusProperty(): array
    {
        if (!$this->collection || !$this->collection->last_payment_date) {
            return [
                'date' => null,
                'charged' => false,
                'closed' => false,
                'has_payment' => false,
            ];
        }

        $collectionDate = Carbon::parse($this->collection->last_payment_date)->toDateString();

        $closed = DB::table('closed_accounts')
            ->whereDate('date', $collectionDate)
            ->exists();

        $hasPayment = Payment::where('member_id', $this->member->id)
            ->whereDate('payment_date', $collectionDate)
            ->exists();

        return [
            'date' => Carbon::parse($collectionDate)->format('d/m/Y'),
            'charged' => $closed && !$hasPayment,
            'closed' => $closed,
            'has_payment' => $hasPayment,
        ];
    }

    public function getPenaltyChargeHistoryProperty()
    {
        if (!$this->member->start_date) {
            return collect();
        }

        $startDate = Carbon::parse($this->member->start_date)->toDateString();
        $today = now()->toDateString();

        $closedDates = DB::table('closed_accounts')
            ->whereDate('date', '>=', $startDate)
            ->whereDate('date', '<=', $today)
            ->orderByDesc('date')
            ->pluck('date')
            ->map(fn ($date) => Carbon::parse($date)->toDateString());

        $allPaymentDates = Payment::where('member_id', $this->member->id)
            ->pluck('payment_date')
            ->map(fn ($date) => Carbon::parse($date)->toDateString())
            ->flip();

        return $closedDates->map(function ($date) use ($allPaymentDates) {
            $charged = !isset($allPaymentDates[$date]);

            return [
                'date' => Carbon::parse($date)->format('d/m/Y'),
                'date_string' => $date,
                'charged' => $charged,
                'penalty_amount' => $charged ? (float) $this->member->penalty_per_day : 0,
            ];
        });
    }

    public function render()
    {
        return view('livewire.member-payment-statement');
    }
}
