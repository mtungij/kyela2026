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

        // Fetch all payments with type
        $payments = \App\Models\Payment::with('user')
            ->where('collection_id', $collection->id)
            ->orderBy('payment_date', 'desc')
            ->get();

        $allPayments = $payments->map(function ($p) {
            return [
                'date' => $p->payment_date,
                'amount' => $p->amount,
                'type' => $p->payment_type === 'penalty' ? 'Faini' : 'Mchango',
                'notes' => $p->notes,
                'user' => $p->user->name ?? 'N/A',
            ];
        });
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
            'payment_type' => 'nullable|in:regular,penalty',
            'notes' => 'nullable|string',
        ]);

        \DB::transaction(function () use ($validated) {
            $collection = \App\Models\Collection::find($validated['collection_id']);
            
            // Calculate current penalty
            $penaltyBalance = $collection->getCurrentPenaltyBalance();
            $paymentAmount = $validated['amount'];
            $paymentType = $validated['payment_type'] ?? 'regular';
            $penaltyPayment = 0;
            $loanPayment = 0;
            
            // Determine how to split payment based on type and balances
            if ($paymentType === 'penalty' || $penaltyBalance > 0) {
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
            } else {
                // No penalty, full payment goes to loan
                $loanPayment = $paymentAmount;
            }
            
            // Create payment record with type
            \App\Models\Payment::create([
                'member_id' => $validated['member_id'],
                'collection_id' => $validated['collection_id'],
                'user_id' => auth()->id(),
                'amount' => $validated['amount'],
                'payment_type' => $penaltyPayment > 0 ? 'penalty' : 'regular',
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
