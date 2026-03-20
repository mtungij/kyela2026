<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Member;
use App\Models\Collection;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PaymentReportController extends Controller
{
    public function memberStatementDownloadPdf(Member $member, Request $request)
    {
        $payType = $request->session()->get('pay_type');

        if (in_array($payType, ['mchango_mdogo', 'mchango_mkubwa'], true) && $member->pay_type !== $payType) {
            abort(403);
        }

        $collection = $member->collections()->orderByDesc('id')->first();
        $schedule = [];

        if ($member->start_date && $member->type && $member->amount) {
            $currentDate = Carbon::parse($member->start_date)->startOfDay();
            $today = now()->endOfDay();
            $expectedAmount = (float) $member->amount;

            $regularPayments = Payment::where('member_id', $member->id)
                ->where('payment_type', 'regular')
                ->orderBy('payment_date')
                ->get();

            $allPaymentDates = Payment::where('member_id', $member->id)
                ->pluck('payment_date')
                ->map(fn ($date) => Carbon::parse($date)->toDateString())
                ->flip();

            $closedDates = DB::table('closed_accounts')
                ->whereDate('date', '>=', Carbon::parse($member->start_date)->toDateString())
                ->pluck('date')
                ->map(fn ($date) => Carbon::parse($date)->toDateString())
                ->flip();

            $remainingBalance = 0;

            while ($currentDate <= $today) {
                $scheduleDate = $currentDate->toDateString();
                $expectedPaymentDate = $currentDate->copy();

                $paymentsOnDate = $regularPayments->filter(function ($payment) use ($scheduleDate) {
                    return Carbon::parse($payment->payment_date)->toDateString() === $scheduleDate;
                });

                $paidAmount = (float) $paymentsOnDate->sum('amount');
                $hasAnyPayment = isset($allPaymentDates[$scheduleDate]);
                $closedOnDate = isset($closedDates[$scheduleDate]);
                $penaltyCharged = $closedOnDate && !$hasAnyPayment;

                if ($paidAmount > 0) {
                    $remaining = $paidAmount - $expectedAmount;

                    if ($remaining > 0) {
                        $remainingBalance += $remaining;
                        $displayAmount = $expectedAmount;
                    } else {
                        $displayAmount = $paidAmount;
                        $remainingBalance = 0;
                    }
                } else {
                    if ($remainingBalance > 0) {
                        $remaining = $remainingBalance - $expectedAmount;

                        if ($remaining >= 0) {
                            $displayAmount = $expectedAmount;
                            $remainingBalance = $remaining;
                        } else {
                            $displayAmount = $remainingBalance;
                            $remainingBalance = 0;
                        }
                    } else {
                        $displayAmount = null;
                    }
                }

                $schedule[] = [
                    'date' => $expectedPaymentDate->format('d/m/Y'),
                    'amount' => $displayAmount,
                    'is_paid' => $paidAmount > 0 || $displayAmount !== null,
                    'is_closed' => $closedOnDate,
                    'penalty_charged' => $penaltyCharged,
                ];

                match ($member->type) {
                    'daily' => $currentDate->addDay(),
                    'weekly' => $currentDate->addWeek(),
                    'monthly' => $currentDate->addMonth(),
                    default => $currentDate->addDay(),
                };
            }

            if ($remainingBalance > 0) {
                while ($remainingBalance > 0) {
                    $expectedPaymentDate = $currentDate->copy();
                    $remaining = $remainingBalance - $expectedAmount;

                    if ($remaining >= 0) {
                        $displayAmount = $expectedAmount;
                        $remainingBalance = $remaining;
                    } else {
                        $displayAmount = $remainingBalance;
                        $remainingBalance = 0;
                    }

                    $schedule[] = [
                        'date' => $expectedPaymentDate->format('d/m/Y'),
                        'amount' => $displayAmount,
                        'is_paid' => true,
                        'is_closed' => false,
                        'penalty_charged' => false,
                    ];

                    match ($member->type) {
                        'daily' => $currentDate->addDay(),
                        'weekly' => $currentDate->addWeek(),
                        'monthly' => $currentDate->addMonth(),
                        default => $currentDate->addDay(),
                    };
                }
            }
        }

        $summary = [
            'expected_periods' => count($schedule),
            'paid_periods' => collect($schedule)->where('is_paid', true)->count(),
            'total_amount' => Payment::where('member_id', $member->id)->where('payment_type', 'regular')->sum('amount'),
            'balance' => (float) ($collection?->balance ?? 0),
        ];

        $pdf = Pdf::loadView('payments.member-statement-pdf', compact('member', 'collection', 'schedule', 'summary'));
        $filename = 'Member_Statement_' . str($member->name)->slug('_') . '_' . now()->format('Ymd_His') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Display all members who paid with date filter
     */
public function index(Request $request)
{
    $query = Payment::with(['member', 'user', 'collection'])
        ->where('payment_type', 'regular');

    // ✅ Single date instead of range
    $paymentDate = $request->get('payment_date', now()->toDateString());

    $payType = $request->get('pay_type');
    $search = $request->get('search');

    // ✅ Filter by single date
    if ($paymentDate) {
        $query->whereDate('payment_date', $paymentDate);
    }

    if ($payType) {
        $query->whereHas('member', fn($q) => 
            $q->where('pay_type', $payType)
        );
    }

    if ($search) {
        $query->whereHas('member', fn($q) => 
            $q->where('name', 'like', "%{$search}%")
        );
    }

    $payments = $query->orderBy('payment_date', 'desc')
        ->paginate(15)
        ->withQueryString();

    // ✅ Summary (use same single date)
    $summaryQuery = Payment::where('payment_type', 'regular')
        ->whereDate('payment_date', $paymentDate);

    if ($payType) {
        $summaryQuery->whereHas('member', fn($q) => 
            $q->where('pay_type', $payType)
        );
    }

    if ($search) {
        $summaryQuery->whereHas('member', fn($q) => 
            $q->where('name', 'like', "%{$search}%")
        );
    }

    $summary = [
        'total_payments' => $summaryQuery->count(),
        'total_amount' => $summaryQuery->sum('amount'),
        'total_members' => $summaryQuery->distinct('member_id')->count('member_id'),
    ];

    $payTypeLabel = $payType 
        ? ($payType === 'mchango_mdogo' ? 'Mchango Mdogo' : 'Mchango Mkubwa') 
        : 'Wote';

    return view('payments.report', compact(
        'payments',
        'paymentDate', // ✅ changed
        'summary',
        'payType',
        'payTypeLabel',
        'search'
    ));
}

    /**
     * Download payment report as PDF
     */
   public function downloadPdf(Request $request)
{
    $fromDate = $request->get('from_date', now()->toDateString());
    $toDate = $request->get('to_date', now()->toDateString());
    $payType = $request->get('pay_type');
    $search = $request->get('search'); // NEW: search filter for PDF

    $query = Payment::with(['member', 'user', 'collection'])
        ->where('payment_type', 'regular');

    if ($fromDate && $toDate) {
        $query->whereBetween('payment_date', [$fromDate, $toDate]);
    }

    if ($payType) {
        $query->whereHas('member', fn($q) => $q->where('pay_type', $payType));
    }

    if ($search) {
        $query->whereHas('member', fn($q) => $q->where('name', 'like', "%{$search}%"));
    }

    $payments = $query->orderBy('payment_date', 'desc')->get();

    // Summary
    $summary = [
        'total_payments' => $payments->count(),
        'total_amount' => $payments->sum('amount'),
        'total_members' => $payments->pluck('member_id')->unique()->count(),
    ];

    $payTypeLabel = $payType ? ($payType === 'mchango_mdogo' ? 'Mchango Mdogo' : 'Mchango Mkubwa') : 'Wote';

    $pdf = Pdf::loadView('payments.pdf', compact('payments', 'fromDate', 'toDate', 'summary', 'payType', 'payTypeLabel', 'search'));

    $filename = 'Ripoti_Ya_Malipo_' . $fromDate . '_' . $toDate . ($payType ? '_' . $payType : '') . '.pdf';

    return $pdf->download($filename);
}

    /**
     * Delete a payment record
     */
    public function deletePayment($paymentId)
    {
        // Only admin can delete payments
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('payments.report')
                ->with('error', 'Hairuhusiwi kufuta malipo. Harufu za admin kwa kufanya hatua hii.');
        }

        $payment = Payment::findOrFail($paymentId);
        $collectionId = $payment->collection_id;

        DB::transaction(function () use ($payment, $collectionId) {
            // Get the collection
            $collection = Collection::find($collectionId);
            
            if ($collection) {
                // Reverse the payment amount from collection
                $collection->amount_paid -= $payment->amount;
                $collection->balance = $collection->total_amount - $collection->amount_paid;
                
                // Reset status if needed
                if ($collection->amount_paid <= 0) {
                    $collection->status = 'pending';
                } elseif ($collection->amount_paid < $collection->total_amount) {
                    $collection->status = 'partial';
                }
                
                $collection->save();
            }
            
            // Delete the payment
            $payment->delete();
        });

        return redirect()->route('payments.report')
            ->with('success', 'Malipo yamefanikiwa kufuta!');
    }

    /**
     * Display all members who paid penalties with date filter
     */
    public function penaltyReport(Request $request)
    {
        // Get collections with penalty payments
        $query = Collection::with(['member'])
            ->where('penalty_paid', '>', 0);

        $payType = $request->get('pay_type');

        // Default to today's date
        $fromDate = $request->get('from_date', now()->toDateString());
        $toDate = $request->get('to_date', now()->toDateString());

        // Filter by date range (using updated_at as proxy since we don't have separate penalty payment table)
        if ($fromDate && $toDate) {
            $query->whereBetween('updated_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
        }

        if ($payType) {
            $query->whereHas('member', function ($q) use ($payType) {
                $q->where('pay_type', $payType);
            });
        }

        $collections = $query->orderBy('updated_at', 'desc')->paginate(15);

        // Calculate summary statistics
        $summaryQuery = Collection::where('penalty_paid', '>', 0)
            ->whereBetween('updated_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);

        if ($payType) {
            $summaryQuery->whereHas('member', function ($q) use ($payType) {
                $q->where('pay_type', $payType);
            });
        }

        $summary = [
            'total_members' => $collections->count(),
            'total_penalty_paid' => $summaryQuery->sum('penalty_paid'),
            'total_penalty_balance' => $summaryQuery->sum('penalty_balance'),
        ];

        $payTypeLabel = $payType ? ($payType === 'mchango_mdogo' ? 'Mchango Mdogo' : 'Mchango Mkubwa') : 'Wote';

        return view('penalties.report', compact('collections', 'fromDate', 'toDate', 'summary', 'payType', 'payTypeLabel'));
    }

    /**
     * Download penalty report as PDF
     */
    public function penaltyDownloadPdf(Request $request)
    {
        $fromDate = $request->get('from_date', now()->toDateString());
        $toDate = $request->get('to_date', now()->toDateString());
        $payType = $request->get('pay_type');

        $query = Collection::with(['member'])
            ->where('penalty_paid', '>', 0);

        if ($fromDate && $toDate) {
            $query->whereBetween('updated_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
        }

        if ($payType) {
            $query->whereHas('member', function ($q) use ($payType) {
                $q->where('pay_type', $payType);
            });
        }

        $collections = $query->orderBy('updated_at', 'desc')->get();

        // Calculate summary statistics
        $summary = [
            'total_members' => $collections->count(),
            'total_penalty_paid' => $collections->sum('penalty_paid'),
            'total_penalty_balance' => $collections->sum('penalty_balance'),
        ];

        $payTypeLabel = $payType ? ($payType === 'mchango_mdogo' ? 'Mchango Mdogo' : 'Mchango Mkubwa') : 'Wote';

        $pdf = Pdf::loadView('penalties.pdf', compact('collections', 'fromDate', 'toDate', 'summary', 'payType', 'payTypeLabel'));
        
        return $pdf->download('Ripoti_Ya_Faini_' . $fromDate . '_' . $toDate . '.pdf');
    }

    /**
     * Display all members who haven't paid with date filter
     */
public function unpaidReport(Request $request)
{
    // Tarehe ya kuchuja (default = leo)
    $date = $request->get('date', now()->toDateString());
    $payType = $request->get('pay_type');

    $collections = Collection::with('member')
        ->where('balance', '>', 0)

        // Hakuna malipo tarehe hiyo
        ->whereDoesntHave('payments', function ($q) use ($date) {
            $q->whereDate('payment_date', $date);
        })

        // Filter by pay_type
        ->when($payType, function ($query) use ($payType) {
            $query->whereHas('member', function ($q) use ($payType) {
                $q->where('pay_type', $payType);
            });
        })

        ->orderBy('balance', 'desc')
        ->paginate(15);

    // Summary
    $summary = [
        'total_members' => $collections->total(),
        'total_amount_owed' => $collections->sum('balance'),
        'total_amount_paid' => 0,
    ];

    $payTypeLabel = $payType ? ($payType === 'mchango_mdogo' ? 'Mchango Mdogo' : 'Mchango Mkubwa') : 'Wote';

    return view('unpaid.report', compact('collections', 'date', 'summary', 'payType', 'payTypeLabel'));
}

    /**
     * Download unpaid report as PDF
     */
    public function unpaidDownloadPdf(Request $request)
    {
        $fromDate = $request->get('from_date', now()->toDateString());
        $toDate = $request->get('to_date', now()->toDateString());
        $payType = $request->get('pay_type');

        $query = Collection::with(['member'])
            ->where('balance', '>', 0);

        if ($fromDate && $toDate) {
            $query->whereBetween('created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
        }

        // Filter by pay_type
        if ($payType) {
            $query->whereHas('member', function ($q) use ($payType) {
                $q->where('pay_type', $payType);
            });
        }

        $collections = $query->orderBy('balance', 'desc')->get();

        // Calculate summary statistics
        $summary = [
            'total_members' => $collections->count(),
            'total_amount_owed' => $collections->sum('balance'),
            'total_amount_paid' => $collections->sum('amount_paid'),
        ];

        $payTypeLabel = $payType ? ($payType === 'mchango_mdogo' ? 'Mchango Mdogo' : 'Mchango Mkubwa') : 'Wote';

        $pdf = Pdf::loadView('unpaid.pdf', compact('collections', 'fromDate', 'toDate', 'summary', 'payType', 'payTypeLabel'));
        
        $filename = 'Ripoti_Ya_Hawajalipa_' . $fromDate . '_' . $toDate . ($payType ? '_' . $payType : '') . '.pdf';
        
        return $pdf->download($filename);
    }
}
