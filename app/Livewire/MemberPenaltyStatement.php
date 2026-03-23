<?php

namespace App\Livewire;

use App\Models\Member;
use App\Models\PenaltyForgiveness;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class MemberPenaltyStatement extends Component
{
    public Member $member;

    public function mount(Member $member): void
    {
        $this->member = $member;
    }

    public function getPenaltyRowsProperty()
    {
        if (!$this->member->start_date) {
            return collect();
        }

        $startDate = Carbon::parse($this->member->start_date)->toDateString();
        $today = now()->toDateString();

        $closedDates = DB::table('closed_accounts')
            ->whereDate('date', '>=', $startDate)
            ->whereDate('date', '<=', $today)
            ->orderBy('date')
            ->pluck('date')
            ->map(fn ($date) => Carbon::parse($date)->toDateString());

        $allPaymentDates = Payment::where('member_id', $this->member->id)
            ->pluck('payment_date')
            ->map(fn ($date) => Carbon::parse($date)->toDateString())
            ->flip();

        $penaltyPaymentsByDate = Payment::where('member_id', $this->member->id)
            ->where('payment_type', 'penalty')
            ->get(['payment_date', 'amount'])
            ->groupBy(fn ($payment) => Carbon::parse($payment->payment_date)->toDateString())
            ->map(fn ($group) => (float) $group->sum('amount'));

        $chargedDates = $closedDates->filter(fn ($date) => !isset($allPaymentDates[$date]))->values();

        $forgivenDates = PenaltyForgiveness::where('member_id', $this->member->id)
            ->pluck('forgiven_date')
            ->map(fn ($date) => Carbon::parse($date)->toDateString())
            ->flip();

        $penaltyPerDay = max((float) ($this->member->penalty_per_day ?? 0), 0);

        return $closedDates
            ->map(function ($date) use ($allPaymentDates, $penaltyPaymentsByDate, $penaltyPerDay, $forgivenDates) {
                $charged = !isset($allPaymentDates[$date]);
                $paidAmount = (float) ($penaltyPaymentsByDate[$date] ?? 0);
                $paid = $charged ? $paidAmount > 0 : false;
                $forgiven = $charged ? isset($forgivenDates[$date]) : false;

                return [
                    'date' => Carbon::parse($date)->format('d/m/Y'),
                    'date_string' => $date,
                    'charged' => $charged,
                    'paid' => $paid,
                    'paid_amount' => $paidAmount,
                    'forgiven' => $forgiven,
                    'penalty_amount' => $charged ? $penaltyPerDay : 0,
                ];
            })
            ->filter(fn ($row) => $row['charged'] && $row['paid'])
            ->sortByDesc('date_string')
            ->values();
    }

    public function getPenaltySummaryProperty(): array
    {
        $rows = $this->penaltyRows;

        return [
            'paid_count' => $rows->count(),
            'total_paid_amount' => (float) $rows->sum('paid_amount'),
            'penalty_per_day' => max((float) ($this->member->penalty_per_day ?? 0), 0),
        ];
    }

    public function render()
    {
        return view('livewire.member-penalty-statement');
    }
}
