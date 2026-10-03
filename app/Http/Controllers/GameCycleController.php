<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\GameCycle;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class GameCycleController extends Controller
{
    public function create(Request $request)
    {
        $payType = $this->sessionPayType($request);
        abort_unless($payType, 403);

        $members = Member::where('pay_type', $payType)->orderBy('name')->get();
        $outstandingDebt = Collection::whereHas('member', function ($query) use ($payType) {
            $query->where('pay_type', $payType);
        })->sum('balance') + Collection::whereHas('member', function ($query) use ($payType) {
            $query->where('pay_type', $payType);
        })->sum('penalty_balance');
        $currentCycle = GameCycle::where('pay_type', $payType)
            ->latest('start_date')
            ->first();
        $activeCycle = GameCycle::where('pay_type', $payType)
            ->where('status', 'active')
            ->whereDate('end_date', '>=', today())
            ->latest('start_date')
            ->first();

        return view('game-cycles.create', compact('payType', 'members', 'outstandingDebt', 'currentCycle', 'activeCycle'));
    }

    public function store(Request $request)
    {
        $payType = $this->sessionPayType($request);
        abort_unless($payType, 403);

        $members = Member::where('pay_type', $payType)->orderBy('id')->get();
        $validated = $request->validate([
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'contribution_amount' => ['required', 'numeric', 'min:0.01', 'max:999999999'],
            'confirm' => ['accepted'],
        ]);
        $cycleDays = (int) Carbon::parse($validated['start_date'])
            ->diffInDays(Carbon::parse($validated['end_date'])) + 1;

        DB::transaction(function () use ($request, $validated, $payType, $members, $cycleDays) {
            $activeCycle = GameCycle::where('pay_type', $payType)
                ->where('status', 'active')
                ->whereDate('end_date', '>=', today())
                ->lockForUpdate()
                ->latest('start_date')
                ->first();

            if ($activeCycle) {
                throw ValidationException::withMessages([
                    'start_date' => 'Mzunguko uliopo bado unaendelea hadi ' . $activeCycle->end_date->format('d-m-Y') . '. Anzisha mwingine baada ya tarehe hiyo.',
                ]);
            }

            $overlappingCycleExists = GameCycle::where('pay_type', $payType)
                ->whereDate('start_date', '<=', $validated['end_date'])
                ->whereDate('end_date', '>=', $validated['start_date'])
                ->lockForUpdate()
                ->exists();

            if ($overlappingCycleExists) {
                throw ValidationException::withMessages([
                    'start_date' => 'Tarehe ulizochagua zinaingiliana na kipindi cha mzunguko mwingine wa mchezo huu.',
                ]);
            }

            $activeCycle = GameCycle::where('pay_type', $payType)
                ->where('status', 'active')
                ->lockForUpdate()
                ->latest('start_date')
                ->first();

            if ($activeCycle) {
                $activeCycle->update(['status' => 'closed']);
            }

            $cycle = GameCycle::create([
                'pay_type' => $payType,
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'contribution_amount' => $validated['contribution_amount'],
                'status' => 'active',
                'created_by' => $request->user()->id,
            ]);

            foreach ($members as $member) {
                $installmentAmount = (float) $validated['contribution_amount'];
                $member->update([
                    'amount' => $installmentAmount,
                    'number_type' => $cycleDays,
                    'start_date' => $validated['start_date'],
                    'end_date' => $validated['end_date'],
                ]);

                $totalAmount = $installmentAmount * $cycleDays;

                Collection::create([
                    'member_id' => $member->id,
                    'game_cycle_id' => $cycle->id,
                    'installment_amount' => $installmentAmount,
                    'total_amount' => $totalAmount,
                    'amount_paid' => 0,
                    'balance' => $totalAmount,
                    'total_penalty' => 0,
                    'penalty_paid' => 0,
                    'penalty_balance' => 0,
                    'last_payment_date' => $validated['start_date'],
                    'status' => 'pending',
                ]);
            }
        });

        $membersRoute = $payType === 'mchango_mdogo'
            ? 'members.index.mdogo'
            : 'members.index.mkubwa';

        return redirect()->route($membersRoute)
            ->with('success', 'Mzunguko mpya umeanzishwa. Madeni na malipo ya zamani yamehifadhiwa kwenye mzunguko wake.');
    }

    private function sessionPayType(Request $request): ?string
    {
        $payType = $request->session()->get('pay_type');

        return in_array($payType, ['mchango_mdogo', 'mchango_mkubwa'], true)
            ? $payType
            : null;
    }
}