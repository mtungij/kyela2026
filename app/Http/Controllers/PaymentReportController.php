<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Member;
use App\Models\Collection;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class PaymentReportController extends Controller
{
    /**
     * Display all members who paid with date filter
     */
    public function index(Request $request)
    {
        $query = Payment::with(['member', 'user', 'collection']);

        // Default to today's date
        $fromDate = $request->get('from_date', now()->toDateString());
        $toDate = $request->get('to_date', now()->toDateString());

        // Filter by date range
        if ($fromDate && $toDate) {
            $query->whereBetween('payment_date', [$fromDate, $toDate]);
        }

        // Get distinct members who made payments in the period
        $payments = $query->orderBy('payment_date', 'desc')
            ->paginate(15);

        // Calculate summary statistics
        $summary = [
            'total_payments' => Payment::whereBetween('payment_date', [$fromDate, $toDate])->count(),
            'total_amount' => Payment::whereBetween('payment_date', [$fromDate, $toDate])->sum('amount'),
            'total_members' => Payment::whereBetween('payment_date', [$fromDate, $toDate])
                ->distinct('member_id')
                ->count('member_id'),
        ];

        return view('payments.report', compact('payments', 'fromDate', 'toDate', 'summary'));
    }

    /**
     * Download payment report as PDF
     */
    public function downloadPdf(Request $request)
    {
        $fromDate = $request->get('from_date', now()->toDateString());
        $toDate = $request->get('to_date', now()->toDateString());

        $query = Payment::with(['member', 'user', 'collection']);

        // Filter by date range
        if ($fromDate && $toDate) {
            $query->whereBetween('payment_date', [$fromDate, $toDate]);
        }

        $payments = $query->orderBy('payment_date', 'desc')->get();

        // Calculate summary statistics
        $summary = [
            'total_payments' => $payments->count(),
            'total_amount' => $payments->sum('amount'),
            'total_members' => $payments->pluck('member_id')->unique()->count(),
        ];

        $pdf = Pdf::loadView('payments.pdf', compact('payments', 'fromDate', 'toDate', 'summary'));
        
        return $pdf->download('Ripoti_Ya_Malipo_' . $fromDate . '_' . $toDate . '.pdf');
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

        // Default to today's date
        $fromDate = $request->get('from_date', now()->toDateString());
        $toDate = $request->get('to_date', now()->toDateString());

        // Filter by date range (using updated_at as proxy since we don't have separate penalty payment table)
        if ($fromDate && $toDate) {
            $query->whereBetween('updated_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
        }

        $collections = $query->orderBy('updated_at', 'desc')->paginate(15);

        // Calculate summary statistics
        $summary = [
            'total_members' => $collections->count(),
            'total_penalty_paid' => Collection::where('penalty_paid', '>', 0)
                ->whereBetween('updated_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59'])
                ->sum('penalty_paid'),
            'total_penalty_balance' => Collection::where('penalty_paid', '>', 0)
                ->whereBetween('updated_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59'])
                ->sum('penalty_balance'),
        ];

        return view('penalties.report', compact('collections', 'fromDate', 'toDate', 'summary'));
    }

    /**
     * Download penalty report as PDF
     */
    public function penaltyDownloadPdf(Request $request)
    {
        $fromDate = $request->get('from_date', now()->toDateString());
        $toDate = $request->get('to_date', now()->toDateString());

        $query = Collection::with(['member'])
            ->where('penalty_paid', '>', 0);

        if ($fromDate && $toDate) {
            $query->whereBetween('updated_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
        }

        $collections = $query->orderBy('updated_at', 'desc')->get();

        // Calculate summary statistics
        $summary = [
            'total_members' => $collections->count(),
            'total_penalty_paid' => $collections->sum('penalty_paid'),
            'total_penalty_balance' => $collections->sum('penalty_balance'),
        ];

        $pdf = Pdf::loadView('penalties.pdf', compact('collections', 'fromDate', 'toDate', 'summary'));
        
        return $pdf->download('Ripoti_Ya_Faini_' . $fromDate . '_' . $toDate . '.pdf');
    }

    /**
     * Display all members who haven't paid with date filter
     */
public function unpaidReport(Request $request)
{
    // Tarehe ya kuchuja (default = leo)
    $date = $request->get('date', now()->toDateString());

    $collections = Collection::with('member')
        ->where('balance', '>', 0)

        // Hakuna malipo tarehe hiyo
        ->whereDoesntHave('payments', function ($q) use ($date) {
            $q->whereDate('payment_date', $date);
        })

      

        ->orderBy('balance', 'desc')
        ->paginate(15);

    // Summary
    $summary = [
        'total_members' => $collections->total(),
        'total_amount_owed' => $collections->sum('balance'),
        'total_amount_paid' => 0,
    ];

    return view('unpaid.report', compact('collections', 'date', 'summary'));
}

    /**
     * Download unpaid report as PDF
     */
    public function unpaidDownloadPdf(Request $request)
    {
        $fromDate = $request->get('from_date', now()->toDateString());
        $toDate = $request->get('to_date', now()->toDateString());

        $query = Collection::with(['member'])
            ->where('balance', '>', 0);

        if ($fromDate && $toDate) {
            $query->whereBetween('created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
        }

        $collections = $query->orderBy('balance', 'desc')->get();

        // Calculate summary statistics
        $summary = [
            'total_members' => $collections->count(),
            'total_amount_owed' => $collections->sum('balance'),
            'total_amount_paid' => $collections->sum('amount_paid'),
        ];

        $pdf = Pdf::loadView('unpaid.pdf', compact('collections', 'fromDate', 'toDate', 'summary'));
        
        return $pdf->download('Ripoti_Ya_Hawajalipa_' . $fromDate . '_' . $toDate . '.pdf');
    }
}
