<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $members = Member::query()
            ->when($search, function($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%")
                            ->orWhere('address', 'like', "%{$search}%")
                            ->orWhere('business_address', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('member.index', compact('members'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:255',
            'business_address' => 'nullable|string|max:255',
            'amount' => 'required|numeric',
            'type' => 'required|in:daily,weekly,monthly',
            'number_type' => 'required|numeric|min:1',
            'penalty_per_day' => 'nullable|numeric|min:0',
        ]);
        
        // Add 255 prefix to phone if not already present
        $phone = $validatedData['phone'];
        if (!str_starts_with($phone, '255')) {
            $phone = ltrim($phone, '0'); // Remove leading zero if present
            $validatedData['phone'] = '255' . $phone;
        }
        
        // Set default penalty if not provided
        if (!isset($validatedData['penalty_per_day'])) {
            $validatedData['penalty_per_day'] = 0;
        }
        
        DB::transaction(function () use ($validatedData) {
            // Create member
            $member = Member::create($validatedData);
            
            // Calculate total collection amount (amount * number_type)
            $totalAmount = $member->amount * $member->number_type;
            
            // Create collection record with penalty tracking
            Collection::create([
                'member_id' => $member->id,
                'total_amount' => $totalAmount,
                'amount_paid' => 0,
                'balance' => $totalAmount,
                'total_penalty' => 0,
                'penalty_paid' => 0,
                'penalty_balance' => 0,
                'last_payment_date' => now(),
                'status' => 'pending',
            ]);
        });

        

        $phone = $validatedData['phone'];
     $massage = "Karibu $validatedData[name] kwenye Kalumbulu Group Kikundi cha kuwezeshana. Karibu tushirikiane na kuwezeshana!";

   


        $this->sendsms($phone,$massage);
        
        return redirect()->route('members.index')->with('success', 'Member created successfully.'); 
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:255',
            'business_address' => 'nullable|string|max:255',
            'amount' => 'required|numeric',
            'type' => 'required|in:daily,weekly,monthly',
            'number_type' => 'required|numeric|min:1',
        ]);
        
        // Add 255 prefix to phone if not already present
        $phone = $validatedData['phone'];
        if (!str_starts_with($phone, '255')) {
            $phone = ltrim($phone, '0');
            $validatedData['phone'] = '255' . $phone;
        }
        
        $member = Member::findOrFail($id);
        $member->update($validatedData);
        
        return redirect()->route('members.index')->with('success', 'Member updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Only admin can delete members
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('members.index')
                ->with('error', 'Hairuhusiwi kufuta wanachama. Harufu za admin kwa kufanya hatua hii.');
        }

        $member = Member::findOrFail($id);
        $member->delete();
        
        return redirect()->route('members.index')->with('success', 'Member deleted successfully.');
    }

    /**
     * Forgive penalty for a member
     */
    public function forgivePenalty(string $id)
    {
        $member = Member::findOrFail($id);
        $collection = $member->collections()->first();
        
        if ($collection && $collection->penalty_balance > 0) {
            DB::transaction(function () use ($collection) {
                // Reset all penalty values to zero
                $collection->total_penalty = 0;
                $collection->penalty_paid = 0;
                $collection->penalty_balance = 0;
                $collection->save();
            });
            
            return redirect()->route('members.index')->with('success', 'Faini imesamehewa kikamilifu.');
        }
        
        return redirect()->route('members.index')->with('info', 'Hakuna faini ya kusamehe.');
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
