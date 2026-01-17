<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DailyReportController extends Controller
{
    public function index(Request $request)
    {
        // Get date from request or use today
        $date = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::today();
        
        // Total Members
        $totalMembers = \App\Models\Member::count();
        
        // Members who completed payment (collections with balance = 0 and penalty_balance = 0)
        $completedMembers = \App\Models\Collection::where('balance', '<=', 0)
            ->where('penalty_balance', '<=', 0)
            ->where('status', 'completed')
            ->count();
        
        // Expected amount today (members who should pay today)
        $expectedToday = 0;
        $members = \App\Models\Member::all();
        foreach ($members as $member) {
            $collection = $member->collections()->first();
            if ($collection && $collection->balance > 0) {
                if ($member->type === 'daily') {
                    // Daily members should pay every day
                    $expectedToday += $member->amount;
                } elseif ($member->type === 'weekly') {
                    // Check if it's been 7 days since last payment
                    $lastPaymentDate = $collection->last_payment_date ?? $collection->created_at;
                    if ($lastPaymentDate->diffInDays($date) >= 7) {
                        $expectedToday += $member->amount;
                    }
                } elseif ($member->type === 'monthly') {
                    // Check if it's been 30 days since last payment
                    $lastPaymentDate = $collection->last_payment_date ?? $collection->created_at;
                    if ($lastPaymentDate->diffInDays($date) >= 30) {
                        $expectedToday += $member->amount;
                    }
                }
            }
        }
        
        // Total Collection Payments (regular payments only)
        $totalCollectionPayments = Payment::whereDate('payment_date', $date)
            ->where('payment_type', 'regular')
            ->sum('amount');
        
        // Total Penalty Payments
        $totalPenaltyPayments = Payment::whereDate('payment_date', $date)
            ->where('payment_type', 'penalty')
            ->sum('amount');
        
        // Total Expenses
        $totalExpenses = Expense::whereDate('expense_date', $date)
            ->sum('amount');
        
        // Calculate Net (Remainder)
        $totalIncome = $totalCollectionPayments + $totalPenaltyPayments;
        $netAmount = $totalIncome - $totalExpenses;
        
        // Get detailed payment list
        $payments = Payment::with(['member', 'user'])
            ->whereDate('payment_date', $date)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get detailed expense list
        $expenses = Expense::with('user')
            ->whereDate('expense_date', $date)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('reports.daily', compact(
            'date',
            'totalMembers',
            'completedMembers',
            'expectedToday',
            'totalCollectionPayments',
            'totalPenaltyPayments',
            'totalExpenses',
            'totalIncome',
            'netAmount',
            'payments',
            'expenses'
        ));
    }
}
