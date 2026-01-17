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

        // Expected Collection Today (sum of all member amounts for daily type)
        $expectedCollectionToday = Member::where('type', 'daily')
            ->whereDate('created_at', '<', Carbon::today())
            ->sum('amount');

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
        $penaltyFeesCollectedThisMonth = Payment::whereBetween('payment_date', [
            $now->copy()->startOfMonth(),
            $now->copy()->endOfMonth(),
        ])->sum('penalty_fee');



        // Payments Needed to collected this Month (unpaid collections)
        $paymentsNeededThisMonth = Collection::where('status', 'pending')
            ->orWhere('balance', '>', 0)
            ->count();

        // Payments Needed to collected this Week (expected amount - paid amount)
        $expectedThisWeek = Member::where('type', 'daily')->sum('amount') * 7 + 
                           Member::where('type', 'weekly')->sum('amount');
        $paidThisWeek = Payment::whereBetween('payment_date', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
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
