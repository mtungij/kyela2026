<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\Member;
use App\Models\Payment;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class DailyReportController extends Controller
{

    /**
     * 🔒 CLOSE ACCOUNT (Funga Hesabu)
     */
    public function closeAccount(Request $request)
    {
        $date = $request->input('date')
            ? Carbon::parse($request->input('date'))->startOfDay()
            : Carbon::today();

            dd($date); 

        // Prevent duplicate closing
        $alreadyClosed = DB::table('closed_accounts')
            ->whereDate('date', $date)
            ->exists();

        if ($alreadyClosed) {
            return redirect()->back()->with(
                'success',
                'Hesabu ilikuwa tayari imefungwa tarehe ' . $date->format('d/m/Y')
            );
        }

        // Get eligible members
        $eligibleMembers = Member::whereDate('start_date', '<=', $date)
            ->where(function ($q) use ($date) {
                $q->whereNull('end_date')
                  ->orWhereDate('end_date', '>=', $date);
            })
            ->with(['payments' => function ($query) {
                $query->where('payment_type', 'regular');
            }])
            ->get();

        DB::transaction(function () use ($eligibleMembers, $date) {

            foreach ($eligibleMembers as $member) {

                $existingCollection = Collection::where('member_id', $member->id)
                    ->orderByDesc('id')
                    ->first();

                // Skip completed members
                if ($existingCollection && 
                    ($existingCollection->balance <= 0 || $existingCollection->status === 'completed')) {
                    continue;
                }

                // 🚨 MAIN LOGIC (ACCUMULATION BASED)

                if ((float) $member->amount > 0) {

                    $scheduleStart = Carbon::parse($member->start_date)->startOfDay();

                    $memberEndDate = $member->end_date
                        ? Carbon::parse($member->end_date)->startOfDay()
                        : null;

                    // Limit selected date to end_date
                    $effectiveDate = $memberEndDate && $memberEndDate->lt($date)
                        ? $memberEndDate
                        : $date->copy();

                    // If not started yet → skip
                    if ($effectiveDate->lt($scheduleStart)) {
                        continue;
                    }

                    // Days that should be covered
                    $daysPassed = $scheduleStart->diffInDays($effectiveDate) + 1;

                    // Expected total up to selected date
                    $expectedAmount = $daysPassed * (float) $member->amount;

                    // Total paid (ALL TIME, not by date)
                    $totalPaid = (float) $member->payments->sum('amount');

                    // ✅ If covered → NO penalty
                    if ($totalPaid >= $expectedAmount) {
                        continue;
                    }
                }

                // ❌ NOT COVERED → ADD PENALTY
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

            // Mark date as closed
            DB::table('closed_accounts')->insert([
                'date' => $date->toDateString(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        return redirect()->back()->with(
            'success',
            'Hesabu imefungwa tarehe ' . $date->format('d/m/Y') .
            ' na faini imeongezwa kwa ambao hawajafikia kiwango cha malipo hadi tarehe hiyo.'
        );
    }

    /**
     * 📊 DAILY REPORT VIEW
     */
    public function index(Request $request)
    {
        $payType = $request->session()->get('pay_type');

        if ($payType && !in_array($payType, ['mchango_mdogo', 'mchango_mkubwa'], true)) {
            $payType = null;
        }

        $date = $request->input('date')
            ? Carbon::parse($request->input('date'))
            : Carbon::today();

        // Total Members
        $totalMembers = Member::when($payType, function ($query) use ($payType) {
            return $query->where('pay_type', $payType);
        })
        ->whereDate('start_date', '<=', $date)
        ->count();

        // Completed Members
        $completedMembers = Collection::where('balance', '<=', 0)
            ->where('penalty_balance', '<=', 0)
            ->where('status', 'completed')
            ->count();

        // Expected Today (simple view logic)
        $expectedToday = 0;
        $members = Member::when($payType, function ($query) use ($payType) {
            return $query->where('pay_type', $payType);
        })
        ->whereDate('start_date', '<=', $date)
        ->get();

        foreach ($members as $member) {
            $collection = $member->collections()->first();

            if ($collection && $collection->balance > 0) {
                $expectedToday += $member->amount;
            }
        }

        // Payments
        $totalCollectionPayments = Payment::whereDate('payment_date', $date)
            ->where('payment_type', 'regular')
            ->sum('amount');

        $totalMemberPaidToday = Payment::whereDate('payment_date', $date)
            ->where('payment_type', 'regular')
            ->distinct('member_id')
            ->count('member_id');

        // Penalties
        $totalPenaltyPayments = Payment::whereDate('payment_date', $date)
            ->where('payment_type', 'penalty')
            ->sum('amount');

        // Expenses
        $totalExpenses = Expense::whereDate('expense_date', $date)
            ->sum('amount');

        $totalIncome = $totalCollectionPayments + $totalPenaltyPayments;
        $netAmount = $totalIncome - $totalExpenses;

        $payments = Payment::with(['member', 'user'])
            ->whereDate('payment_date', $date)
            ->orderBy('created_at', 'desc')
            ->get();

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
            'totalMemberPaidToday'
        ));
    }

    /**
     * 📄 DOWNLOAD PDF
     */
    public function downloadPdf(Request $request)
    {
        $date = $request->input('date')
            ? Carbon::parse($request->input('date'))
            : Carbon::today();

        $totalCollectionPayments = Payment::whereDate('payment_date', $date)
            ->where('payment_type', 'regular')
            ->sum('amount');

        $totalPenaltyPayments = Payment::whereDate('payment_date', $date)
            ->where('payment_type', 'penalty')
            ->sum('amount');

        $totalExpenses = Expense::whereDate('expense_date', $date)
            ->sum('amount');

        $totalIncome = $totalCollectionPayments + $totalPenaltyPayments;
        $netAmount = $totalIncome - $totalExpenses;

        $payments = Payment::with(['member', 'user'])
            ->whereDate('payment_date', $date)
            ->get();

        $expenses = Expense::with('user')
            ->whereDate('expense_date', $date)
            ->get();

        $pdf = Pdf::loadView('reports.daily-pdf', compact(
            'date',
            'totalCollectionPayments',
            'totalPenaltyPayments',
            'totalExpenses',
            'totalIncome',
            'netAmount',
            'payments',
            'expenses'
        ));

        return $pdf->download('Ripoti_ya_Siku_' . $date->format('d-m-Y') . '.pdf');
    }
}