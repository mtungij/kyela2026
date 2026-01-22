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
    public function index()
    {
        // Total Members
        $totalMembers = Member::count();

        // Expected Collection Today (sum of amounts for members who should pay today AND haven't paid yet)
        $today = Carbon::today();
        
        // Get member IDs who have already paid today
        $paidMemberIdsToday = Payment::whereDate('payment_date', $today)
            ->where('payment_type', 'collection')
            ->pluck('member_id')
            ->unique()
            ->toArray();
        
        $members = Member::where('type', 'daily')
            ->whereNotNull('start_date')
            ->whereNotNull('end_date')
            ->whereNotIn('id', $paidMemberIdsToday)
            ->get();
        
        $expectedCollectionToday = $members->filter(function($member) use ($today) {
            return $member->start_date <= $today && $member->end_date >= $today;
        })->sum('amount');

        // dd($expectedCollectionToday);

        // Collection collected Today
        $collectionCollectedToday = Payment::whereDate('payment_date', Carbon::today())->sum('amount');

        // Penalties Paid Today
        $penaltiesPaidToday = Payment::whereDate('payment_date', Carbon::today())
            ->where('payment_type', 'penalty')
            ->sum('amount');

        // Collections collected this week
        $collectionsThisWeek = Payment::whereBetween('payment_date', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ])->sum('amount');

        // Payments Collected This Month
   $now = Carbon::now();

$paymentsCollectedThisMonth = Payment::whereBetween('payment_date', [
    $now->copy()->startOfMonth(),
    $now->copy()->endOfMonth(),
])->sum('amount');


        // Penalty Fees Collected This Month
        $penaltyFeesCollectedThisMonth = Payment::where('payment_type', 'penalty')
            ->whereBetween('payment_date', [
                $now->copy()->startOfMonth(),
                $now->copy()->endOfMonth(),
            ])->sum('amount');

        // Payments Needed to collected this Month (unpaid collections)
        $paymentsNeededThisMonth = Collection::where('status', 'pending')
            ->orWhere('balance', '>', 0)
            ->count();

        // Payments Needed to collected this Week (expected amount - paid amount)
        // Get members who should pay during this week based on their date range
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        
        $dailyMembers = Member::where('type', 'daily')
            ->whereNotNull('start_date')
            ->whereNotNull('end_date')
            ->get();
            
        $weeklyMembers = Member::where('type', 'weekly')
            ->whereNotNull('start_date')
            ->whereNotNull('end_date')
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
        ])->sum('amount');
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
