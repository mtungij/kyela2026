<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Payment;
use App\Models\Collection;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $payType = $request->session()->get('pay_type');

        if ($payType && !in_array($payType, ['mchango_mdogo', 'mchango_mkubwa'], true)) {
            $payType = null;
        }

        $payTypeLabel = $payType ? ($payType === 'mchango_mdogo' ? 'Mchango Mdogo' : 'Mchango Mkubwa') : 'Wote';

        // Total Members
        $totalMembers = Member::when($payType, function ($query, $payType) {
            return $query->where('pay_type', $payType);
        })->count();

        // Expected Collection Today (sum of amounts for members who should pay today AND haven't paid yet)
        $today = Carbon::today();
        
        // Get member IDs who have already paid today
        $paidMemberIdsToday = Payment::whereDate('payment_date', $today)
            ->where('payment_type', 'collection')
            ->when($payType, function ($query, $payType) {
                return $query->whereHas('member', function ($q) use ($payType) {
                    $q->where('pay_type', $payType);
                });
            })
            ->pluck('member_id')
            ->unique()
            ->toArray();
        
        $members = Member::where('type', 'daily')
            ->whereNotNull('start_date')
            ->whereNotNull('end_date')
            ->when($payType, function ($query, $payType) {
                return $query->where('pay_type', $payType);
            })
            ->whereNotIn('id', $paidMemberIdsToday)
            ->get();
        
        $expectedCollectionToday = $members->filter(function($member) use ($today) {
            return $member->start_date <= $today && $member->end_date >= $today;
        })->sum('amount');

        // dd($expectedCollectionToday);

        // Collection collected Today
        $collectionCollectedToday = Payment::whereDate('payment_date', Carbon::today())
            ->when($payType, function ($query, $payType) {
                return $query->whereHas('member', function ($q) use ($payType) {
                    $q->where('pay_type', $payType);
                });
            })
            ->sum('amount');

        // Penalties Paid Today
        $penaltiesPaidToday = Payment::whereDate('payment_date', Carbon::today())
            ->where('payment_type', 'penalty')
            ->when($payType, function ($query, $payType) {
                return $query->whereHas('member', function ($q) use ($payType) {
                    $q->where('pay_type', $payType);
                });
            })
            ->sum('amount');

        // Collections collected this week
        $collectionsThisWeek = Payment::whereBetween('payment_date', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ])
            ->when($payType, function ($query, $payType) {
                return $query->whereHas('member', function ($q) use ($payType) {
                    $q->where('pay_type', $payType);
                });
            })
            ->sum('amount');

        // Payments Collected This Month
   $now = Carbon::now();

$paymentsCollectedThisMonth = Payment::whereBetween('payment_date', [
    $now->copy()->startOfMonth(),
    $now->copy()->endOfMonth(),
])
    ->when($payType, function ($query, $payType) {
        return $query->whereHas('member', function ($q) use ($payType) {
            $q->where('pay_type', $payType);
        });
    })
    ->sum('amount');


        // Penalty Fees Collected This Month
        $penaltyFeesCollectedThisMonth = Payment::where('payment_type', 'penalty')
            ->whereBetween('payment_date', [
                $now->copy()->startOfMonth(),
                $now->copy()->endOfMonth(),
            ])
            ->when($payType, function ($query, $payType) {
                return $query->whereHas('member', function ($q) use ($payType) {
                    $q->where('pay_type', $payType);
                });
            })
            ->sum('amount');

        // Payments Needed to collected this Month (unpaid collections)
        $paymentsNeededThisMonth = Collection::where(function ($query) {
            $query->where('status', 'pending')
                ->orWhere('balance', '>', 0);
        })
            ->when($payType, function ($query, $payType) {
                return $query->whereHas('member', function ($q) use ($payType) {
                    $q->where('pay_type', $payType);
                });
            })
            ->count();

        // Payments Needed to collected this Week (expected amount - paid amount)
        // Get members who should pay during this week based on their date range
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        
        $dailyMembers = Member::where('type', 'daily')
            ->whereNotNull('start_date')
            ->whereNotNull('end_date')
            ->when($payType, function ($query, $payType) {
                return $query->where('pay_type', $payType);
            })
            ->get();
            
        $weeklyMembers = Member::where('type', 'weekly')
            ->whereNotNull('start_date')
            ->whereNotNull('end_date')
            ->when($payType, function ($query, $payType) {
                return $query->where('pay_type', $payType);
            })
            ->get();
        
        // Calculate days in the week that members should pay
        $expectedThisWeek = 0;
        foreach ($dailyMembers as $member) {
            $memberStart = $member->start_date->startOfDay();
            $memberEnd = $member->end_date->startOfDay();
            
            // Count how many days this week overlap with member's payment period
            $daysInWeek = 0;
            for ($date = $startOfWeek->copy(); $date <= $endOfWeek; $date->addDay()) {
                if ($date >= $memberStart && $date <= $memberEnd) {
                    $daysInWeek++;
                }
            }
            $expectedThisWeek += $member->amount * $daysInWeek;
        }
        
        // Add weekly members (if their payment period includes this week)
        foreach ($weeklyMembers as $member) {
            if ($member->start_date <= $endOfWeek && $member->end_date >= $startOfWeek) {
                $expectedThisWeek += $member->amount;
            }
        }
        
        $paidThisWeek = Payment::whereBetween('payment_date', [
            $startOfWeek,
            $endOfWeek
        ])
            ->when($payType, function ($query, $payType) {
                return $query->whereHas('member', function ($q) use ($payType) {
                    $q->where('pay_type', $payType);
                });
            })
            ->sum('amount');
        $paymentsNeededThisWeek = max(0, $expectedThisWeek - $paidThisWeek);

        // Expenses
        $expensesToday = Expense::whereDate('expense_date', Carbon::today())->sum('amount');
        $expensesThisMonth = Expense::whereMonth('expense_date', $now->month)
            ->whereYear('expense_date', $now->year)
            ->sum('amount');

        return view('dashboard', [
            'totalMembers' => $totalMembers,
            'expectedCollectionToday' => $expectedCollectionToday,
            'collectionCollectedToday' => $collectionCollectedToday,
            'penaltiesPaidToday' => $penaltiesPaidToday,
            'collectionsThisWeek' => $collectionsThisWeek,
            'paymentsCollectedThisMonth' => $paymentsCollectedThisMonth,
            'paymentsNeededThisMonth' => $paymentsNeededThisMonth,
            'paymentsNeededThisWeek' => $paymentsNeededThisWeek,
            'expensesToday' => $expensesToday,
            'expensesThisMonth' => $expensesThisMonth,
            'payType' => $payType,
            'payTypeLabel' => $payTypeLabel,
        ]);
    }

    public function penaltyPaymentsList()
    {
        $today = Carbon::today();
        
        // Get all penalty payments for today with member details
        $penaltyPayments = Payment::with(['member', 'user'])
            ->whereDate('payment_date', $today)
            ->where('payment_type', 'penalty')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $totalPenaltiesPaid = $penaltyPayments->sum('amount');
        
        return view('penalties.list', compact('penaltyPayments', 'totalPenaltiesPaid', 'today'));
    }
}
