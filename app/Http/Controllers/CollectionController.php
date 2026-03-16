<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Collection;
use Carbon\Carbon;

class CollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $payType = $request->session()->get('pay_type');

        if ($payType && !in_array($payType, ['mchango_mdogo', 'mchango_mkubwa'], true)) {
            $payType = null;
        }

        $members = Member::query()
            ->when($payType, function ($query, $payType) {
                return $query->where('pay_type', $payType);
            })
            ->orderBy('name')
            ->get();

        return view('collections.index', compact('members', 'payType'));
    }

public function show($memberId, Request $request)
{
    $payType = $request->session()->get('pay_type');


    if ($payType && !in_array($payType, ['mchango_mdogo', 'mchango_mkubwa'], true)) {
        $payType = null;
    }

    $member = Member::with('collections')->findOrFail($memberId);
    $collection = $member->collections()->first(); 

//   $member = Member::with('collections')->findOrFail($memberId);
$collection = $member->collections()->first();

if ($collection) {
    $collection->refresh(); // Ensure latest DB values
    $allPayments = Payment::with('user')
        ->where('collection_id', $collection->id)
        ->orderBy('payment_date', 'desc')
        ->get()
        ->map(function ($p) {
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

    $members = Member::query()
        ->when($payType, function ($query, $payType) {
            return $query->where('pay_type', $payType);
        })
        ->orderBy('name')
        ->get();

    return view('collections.show', compact('member', 'collection', 'allPayments', 'members', 'payType'));
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
        'payment_type' => 'required|in:regular,penalty',
        'notes' => 'nullable|string',
    ]);

    \DB::transaction(function () use ($validated) {
        $collection = \App\Models\Collection::find($validated['collection_id']);
        $paymentAmount = $validated['amount'];
        $paymentType = $validated['payment_type'];

        // Ensure the payment does not exceed max allowed
        if ($paymentType === 'penalty' && $paymentAmount > $collection->penalty_balance) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'amount' => "Kiasi cha malipo cha faini hawezi kuzidi {$collection->penalty_balance}",
            ]);
        }
        if ($paymentType === 'regular' && $paymentAmount > $collection->balance) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'amount' => "Kiasi cha malipo cha mkopo hawezi kuzidi {$collection->balance}",
            ]);
        }

        // Update balances based on payment type
        if ($paymentType === 'penalty') {
            $collection->penalty_paid += $paymentAmount;
            $collection->penalty_balance = $collection->total_penalty - $collection->penalty_paid;
        } else {
            $collection->amount_paid += $paymentAmount;
            $collection->balance = $collection->total_amount - $collection->amount_paid;
        }

        // Record payment
        \App\Models\Payment::create([
            'member_id' => $validated['member_id'],
            'collection_id' => $validated['collection_id'],
            'user_id' => auth()->id(),
            'amount' => $paymentAmount,
            'payment_type' => $paymentType,
            'payment_date' => $validated['payment_date'],
            'notes' => $validated['notes'] ?? null,
        ]);

        // Update last payment date
        $collection->last_payment_date = $validated['payment_date'];

        // Update collection status
        if ($collection->balance <= 0 && $collection->penalty_balance <= 0) {
            $collection->status = 'completed';
        } elseif ($collection->amount_paid > 0 || $collection->penalty_paid > 0) {
            $collection->status = 'partial';
        } else {
            $collection->status = 'pending';
        }

        $collection->save();

        // Send SMS notification
        $member = \App\Models\Member::find($validated['member_id']);
        $totalPaid = $collection->amount_paid;
        $remain = $collection->balance;
        $currentDate = \Carbon\Carbon::parse($validated['payment_date'])->format('d-m-Y');

     $paymentLabel = $paymentType === 'penalty' ? 'Faini' : 'Malipo';

if ($paymentType === 'penalty') {
  $message = "Habari {$member->name}, umelipa faini ya Tsh "
    . number_format($paymentAmount, 0)
    . " tarehe {$currentDate}. Epuka kulaza kuepuka malipo ya faini. "
    . "Jumla uliyolipa mpaka sasa ni Tsh "
    . number_format($totalPaid, 0)
    . ". Asante kwa ushirikiano wako Kalumbulu Group!";
} else {
    $message = "Habari {$member->name}, tumepokea malipo yako ya Tsh "
        . number_format($paymentAmount, 0)
        . " tarehe {$currentDate}. Jumla uliyolipa mpaka sasa ni Tsh "
        . number_format($totalPaid, 0)
        . " na kilichobaki kulipwa ni Tsh "
        . number_format($remain, 0)
        . ". Asante kwa ushirikiano wako Kalumbulu Group!";
}

        $this->sendsms($member->phone, $message);
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



