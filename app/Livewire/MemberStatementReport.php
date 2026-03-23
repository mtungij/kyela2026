<?php

namespace App\Livewire;

use App\Models\Collection;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class MemberStatementReport extends Component
{
    public ?int $selectedMemberId = null;

    public function mount(): void
    {
        $latestMember = $this->membersQuery()->orderByDesc('created_at')->first(['id']);
        if ($latestMember) {
            $this->selectedMemberId = $latestMember->id;
        }
    }

    private function membersQuery()
    {
        $payType = session('pay_type');
        if (!in_array($payType, ['mchango_mdogo', 'mchango_mkubwa'], true)) {
            $payType = null;
        }

        return Member::query()->when($payType, fn ($q) => $q->where('pay_type', $payType));
    }

    public function getMemberOptionsProperty()
    {
        return $this->membersQuery()
            ->orderBy('name')
            ->get(['id', 'name', 'phone'])
            ->map(fn ($member) => [
                'value' => $member->id,
                'label' => trim($member->name . ' - ' . ($member->phone ?? 'No phone')),
            ]);
    }

    public function getSelectedMemberProperty(): ?Member
    {
        if (!$this->selectedMemberId) {
            return null;
        }

        return $this->membersQuery()->find($this->selectedMemberId);
    }

   public function getCollectionPenaltyStatusProperty(): ?array
{
    $member = $this->selectedMember;
    if (!$member) return null;

    $collection = $member->collections()->orderByDesc('id')->first();
    if (!$collection || !$collection->last_payment_date) return null;

    $date = Carbon::parse($collection->last_payment_date)->toDateString();

    $closed = DB::table('closed_accounts')
        ->whereDate('date', $date)
        ->exists();

    // Check if any payment was made **on that date**
    $paidOnDate = $member->payments()
        ->whereDate('payment_date', $date)
        ->sum('amount');

    return [
        'date'    => Carbon::parse($date)->format('d/m/Y'),
        'closed'  => $closed,
        'paid'    => $paidOnDate > 0,
        'charged' => $closed && $paidOnDate <= 0, // Penalty only if closed AND not paid on that date
    ];
}
    public function render()
    {
        return view('livewire.member-statement-report', [
            'memberOptions'             => $this->memberOptions,
            'selectedMember'            => $this->selectedMember,
            'collectionPenaltyStatus'   => $this->collectionPenaltyStatus,
        ]);
    }
}
