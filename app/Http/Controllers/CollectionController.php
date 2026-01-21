<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Collection;
use Carbon\Carbon;

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

public function paymentSms($memberId)
{

    $member = Member::findOrFail($memberId);
    $memberpayments = $member->collections()->first();
   $sumPaid = $member->collections()->sum('amount_paid');
     $remain = $memberpayments->total_amount -  $sumPaid;
     $total = $memberpayments->total_amount;    
     $name = $member->name;
     $phone = $member->phone;

    //  dd($sumPaid);

     $currentDate = Carbon::today()->format('d-m-Y');

$massage = "Habari {$member->name}, tunakukumbusha katika jumla ya kiasi cha kuchangia Tsh "
    . number_format($total, 0) .
    " mpaka tarehe {$currentDate} umelipa jumla Tsh "
    . number_format($sumPaid, 0) .
    " na kilichobaki kulipwa ni Tsh "
    . number_format($remain, 0) .
    ". Asante kwa ushirikiano wako Kalumbulu Group!";


    $this->sendsms($phone,$massage);

    return redirect()->back()->with('success', 'SMS ya malipo imetumwa kwa ' . $member->name);
    


 
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

            // dd($paymentType);
            
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

            $memberName = Member::find($validated['member_id'])->name;
            $phone = Member::find($validated['member_id'])->phone;
            $amount=$validated['amount'];
            $totalPaid = $collection->amount_paid + $loanPayment;
            $remain = $collection->total_amount - $totalPaid;
            // dd($remain);
      $currentDate = Carbon::today()->format('d-m-Y');
$massage = "Habari {$memberName}, tumepokea malipo yako ya Tsh "
    . number_format($amount, 0) .
    " tarehe {$currentDate}. Jumla uliyolipa mpaka sasa ni Tsh "
    . number_format($totalPaid, 0) .
    " na kilichobaki kulipwa ni Tsh "
    . number_format($remain, 0) .
    ". Asante kwa ushirikiano wako Kalumbulu Group!";

        $this->sendsms($phone,$massage);
            
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




     public function sendsms($phone,$massage){
    //public function sendsms(){f
    //$phone = '255628323760';
    //$massage = 'mapenzi yanauwa';
    // $api_key = '';                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               
    //$api_key = 'qFzd89PXu1e/DuwbwxOE5uUBn6';
    //$curl = curl_init();
    $url = "https://sms-api.kadolab.com/api/send-sms";
    $token = getenv('SMS_TOKEN');

  
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      'Authorization: Bearer '. $token,
      'Content-Type: application/json',
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
      "phoneNumbers" => ["+$phone"],
      "message" => $massage
    ]));
  
  $server_output = curl_exec($ch);
  curl_close ($ch);
  
  //print_r($server_output);
  }
  
}



