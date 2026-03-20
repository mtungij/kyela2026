<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\Member;
use App\Models\Payment;
use App\Models\Expense;
use App\Models\User;
use App\Notifications\AccountClosed; // <-- add this line
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class DailyReportController extends Controller
{

public function closeAccount(Request $request)
{
    $date = $request->input('date')
        ? Carbon::parse($request->input('date'))->startOfDay()
        : Carbon::today();

    $alreadyClosed = DB::table('closed_accounts')
        ->whereDate('date', $date)
        ->exists();

    if ($alreadyClosed) {
        return redirect()->back()->with(
            'success',
            'Hesabu ilikuwa tayari imefungwa tarehe ' . $date->format('d/m/Y')
        );
    }

    $membersNotPaid = Member::whereDate('start_date', '<=', $date)
        ->whereDoesntHave('payments', function ($query) use ($date) {
            $query->whereDate('payment_date', $date)
                ->where('payment_type', 'regular');
        })
        ->get();

    DB::transaction(function () use ($membersNotPaid, $date) {

        foreach ($membersNotPaid as $member) {

            $collection = Collection::firstOrCreate(
                ['member_id' => $member->id],
                [
                    'total_penalty' => 0,
                    'penalty_balance' => 0,
                    'last_payment_date' => $date->toDateString(),
                ]
            );

            $collection->total_penalty += $member->penalty_per_day;
            $collection->penalty_balance += $member->penalty_per_day;
            $collection->last_payment_date = $date->toDateString();

            $collection->save();
        }

        DB::table('closed_accounts')->insert([
            'date' => $date->toDateString(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    });

    return redirect()->back()->with(
        'success',
        'Hesabu imefungwa tarehe ' . $date->format('d/m/Y') . ' na faini zimeongezwa kwa waliokosa regular payment.'
    );
}

    public function index(Request $request)
    {

      $payType = $request->session()->get('pay_type');


        if ($payType && !in_array($payType, ['mchango_mdogo', 'mchango_mkubwa'], true)) {
            $payType = null;
        }

        // Get date from request or use today
        $date = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::today();
        
     
        
        // Total Members (filtered by pay_type if provided)
        $totalMembers = \App\Models\Member::when($payType, function ($query) use ($payType) {
            return $query->where('pay_type', $payType);
        })->whereDate('start_date', '<=', $date)->count();
        
        // Members who completed payment (collections with balance = 0 and penalty_balance = 0)
        $completedMembers = \App\Models\Collection::where('balance', '<=', 0)
            ->where('penalty_balance', '<=', 0)
            ->where('status', 'completed')
            ->count();
        
        // Expected amount today (members who should pay today)
        $expectedToday = 0;
        $members = \App\Models\Member::when($payType, function ($query) use ($payType) {
            return $query->where('pay_type', $payType);
        })
        ->whereDate('start_date', '<=', $date)
        ->get();
        // dd($members);
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
            ->when($payType, function ($query) use ($payType) {
                return $query->whereHas('member', function ($q) use ($payType) {
                    $q->where('pay_type', $payType);
                });
            })
            ->sum('amount');


            $totaMemberPaidToday = Payment::whereDate('payment_date', $date)
            ->where ('payment_type', 'regular')
            ->when($payType, function ($query) use ($payType) {
                return $query->whereHas('member', function ($q) use ($payType) {
                    $q->where('pay_type', $payType);
                });
            })
            ->distinct('member_id')
            ->count('member_id');

            // dd($totaMemberPaidToday);

            // dd($totalCollectionPayments);
        
        // Total Penalty Payments
        $totalPenaltyPayments = Payment::whereDate('payment_date', $date)
            ->where('payment_type', 'penalty')
            ->when($payType, function ($query) use ($payType) {
                return $query->whereHas('member', function ($q) use ($payType) {
                    $q->where('pay_type', $payType);
                });
            })
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
            ->when($payType, function ($query) use ($payType) {
                return $query->whereHas('member', function ($q) use ($payType) {
                    $q->where('pay_type', $payType);
                });
            })
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
            'expenses',
            'totaMemberPaidToday'
        ));
    }

    public function downloadPdf(Request $request)
    {
        // Get date from request or use today
        $date = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::today();
        
        // Get pay_type filter
        $payType = $request->input('pay_type');
        
        // Total Members (filtered by pay_type if provided)
        $totalMembers = \App\Models\Member::when($payType, function ($query) use ($payType) {
            return $query->where('pay_type', $payType);
        })->count();
        
        // Members who completed payment
        $completedMembers = \App\Models\Collection::where('balance', '<=', 0)
            ->where('penalty_balance', '<=', 0)
            ->where('status', 'completed')
            ->count();
        
        // Expected amount today
        $expectedToday = 0;
        $members = \App\Models\Member::when($payType, function ($query) use ($payType) {
            return $query->where('pay_type', $payType);
        })->get();
        foreach ($members as $member) {
            $collection = $member->collections()->first();
            if ($collection && $collection->balance > 0) {
                if ($member->type === 'daily') {
                    $expectedToday += $member->amount;
                } elseif ($member->type === 'weekly') {
                    $lastPaymentDate = $collection->last_payment_date ?? $collection->created_at;
                    if ($lastPaymentDate->diffInDays($date) >= 7) {
                        $expectedToday += $member->amount;
                    }
                } elseif ($member->type === 'monthly') {
                    $lastPaymentDate = $collection->last_payment_date ?? $collection->created_at;
                    if ($lastPaymentDate->diffInDays($date) >= 30) {
                        $expectedToday += $member->amount;
                    }
                }
            }
        }
        
        // Total Collection Payments
        $totalCollectionPayments = Payment::whereDate('payment_date', $date)
            ->where('payment_type', 'regular')
            ->when($payType, function ($query) use ($payType) {
                return $query->whereHas('member', function ($q) use ($payType) {
                    $q->where('pay_type', $payType);
                });
            })
            ->sum('amount');
        
        // Total Penalty Payments
        $totalPenaltyPayments = Payment::whereDate('payment_date', $date)
            ->where('payment_type', 'penalty')
            ->when($payType, function ($query) use ($payType) {
                return $query->whereHas('member', function ($q) use ($payType) {
                    $q->where('pay_type', $payType);
                });
            })
            ->sum('amount');
        
        // Total Expenses
        $totalExpenses = Expense::whereDate('expense_date', $date)
            ->sum('amount');
        
        // Calculate Net
        $totalIncome = $totalCollectionPayments + $totalPenaltyPayments;
        $netAmount = $totalIncome - $totalExpenses;
        
        // Get detailed payment list
        $payments = Payment::with(['member', 'user'])
            ->whereDate('payment_date', $date)
            ->when($payType, function ($query) use ($payType) {
                return $query->whereHas('member', function ($q) use ($payType) {
                    $q->where('pay_type', $payType);
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get detailed expense list
        $expenses = Expense::with('user')
            ->whereDate('expense_date', $date)
            ->orderBy('created_at', 'desc')
            ->get();
        
        $payTypeLabel = $payType ? ($payType === 'mchango_mdogo' ? 'Mchango Mdogo' : 'Mchango Mkubwa') : 'Zote';
        
        $pdf = Pdf::loadView('reports.daily-pdf', compact(
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
            'expenses',
            'payType',
            'payTypeLabel'
        ));
        
        $filename = 'Ripoti_ya_Siku_' . $date->format('d-m-Y') . ($payType ? '_' . $payType : '') . '.pdf';
        
        return $pdf->download($filename);
    }
}
