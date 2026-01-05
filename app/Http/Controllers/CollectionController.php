<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('collections.index');
    }

public function show($memberId)
{
    $member = \App\Models\Member::with('collections')->findOrFail($memberId);
    $collection = $member->collections()->first(); 
    $payments = collect();

    if ($collection) {
        // Calculate current penalty
        $collection->getCurrentPenaltyBalance();

        // Fetch normal payments
        $payments = \App\Models\Payment::with('user')
            ->where('collection_id', $collection->id)
            ->get();

        // If you track penalty payments separately
        $penaltyPayments = $collection->penaltyPayments ?? collect();

        // Merge all payments
        $allPayments = $payments->map(function ($p) {
            return [
                'date' => $p->payment_date,
                'amount' => $p->amount,
                'type' => 'Mchango', // regular payment
                'notes' => $p->notes,
                'user' => $p->user->name ?? 'N/A',
            ];
        });

        $allPayments = $allPayments->merge(
            $penaltyPayments->map(function ($p) {
                return [
                    'date' => $p->payment_date,
                    'amount' => $p->amount,
                    'type' => 'Faini', // penalty
                    'notes' => $p->notes ?? 'Faini ya kuchelewa',
                    'user' => $p->user->name ?? 'N/A',
                ];
            })
        )->sortByDesc('date'); // newest first
    } else {
        $allPayments = collect();
    }

    return view('collections.show', compact('member', 'collection', 'allPayments'));
}

    public function storePayment(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'collection_id' => 'required|exists:collections,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        \DB::transaction(function () use ($validated) {
            $collection = \App\Models\Collection::find($validated['collection_id']);
            
            // Calculate current penalty
            $penaltyBalance = $collection->getCurrentPenaltyBalance();
            $paymentAmount = $validated['amount'];
            $penaltyPayment = 0;
            $loanPayment = 0;
            
            // First, apply payment to penalty if exists
            if ($penaltyBalance > 0) {
                if ($paymentAmount >= $penaltyBalance) {
                    // Full penalty payment + remaining to loan
                    $penaltyPayment = $penaltyBalance;
                    $loanPayment = $paymentAmount - $penaltyBalance;
                } else {
                    // Partial penalty payment only
                    $penaltyPayment = $paymentAmount;
                    $loanPayment = 0;
                }
            } else {
                // No penalty, full payment goes to loan
                $loanPayment = $paymentAmount;
            }
            
            // Create payment record
            \App\Models\Payment::create([
                'member_id' => $validated['member_id'],
                'collection_id' => $validated['collection_id'],
                'user_id' => auth()->id(),
                'amount' => $validated['amount'],
                'payment_date' => $validated['payment_date'],
                'notes' => $validated['notes'] ?? null,
            ]);

            // Update penalty paid
            if ($penaltyPayment > 0) {
                $collection->penalty_paid += $penaltyPayment;
                $collection->penalty_balance = $collection->total_penalty - $collection->penalty_paid;
            }
            
            // Update loan payment
            if ($loanPayment > 0) {
                $collection->amount_paid += $loanPayment;
                $collection->balance = $collection->total_amount - $collection->amount_paid;
            }
            
            // Update last payment date
            $collection->last_payment_date = $validated['payment_date'];
            
            // Update status if fully paid (both loan and penalty)
            if ($collection->balance <= 0 && $collection->penalty_balance <= 0) {
                $collection->status = 'completed';
            } elseif ($collection->amount_paid > 0 || $collection->penalty_paid > 0) {
                $collection->status = 'partial';
            } else {
                $collection->status = 'pending';
            }
            
            $collection->save();
        });

        return redirect()->route('collections.show', ['member' => $validated['member_id']])
            ->with('success', 'Malipo yamefanikiwa kurekodiwa!');
    }

}
