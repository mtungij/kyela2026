<?php

namespace App\Livewire;

use App\Models\Collection;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class MemberStatementReport extends Component
{
    public string $search = '';
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

    public function selectMember(int $memberId): void
    {
        $this->selectedMemberId = $memberId;
        $this->search = '';
    }

    public function getSearchResultsProperty()
    {
        if (mb_strlen(trim($this->search)) < 2) {
            return collect();
        }

        $term = trim($this->search);

        return $this->membersQuery()
            ->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('phone', 'like', "%{$term}%");
            })
            ->orderBy('name')
            ->limit(50)
            ->get(['id', 'name', 'phone']);
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
        if (!$member) {
            return null;
        }

        $collection = $member->collections()->orderByDesc('id')->first();
        if (!$collection || !$collection->last_payment_date) {
            return null;
        }

        $collectionDate = Carbon::parse($collection->last_payment_date)->toDateString();
        $closed = DB::table('closed_accounts')->whereDate('date', $collectionDate)->exists();
        $hasPayment = $member->payments()->whereDate('payment_date', $collectionDate)->exists();

        return [
            'date'    => Carbon::parse($collectionDate)->format('d/m/Y'),
            'charged' => $closed && !$hasPayment,
            'closed'  => $closed,
        ];
    }

    public function render()
    {
        return view('livewire.member-statement-report', [
            'searchResults'             => $this->searchResults,
            'selectedMember'            => $this->selectedMember,
            'collectionPenaltyStatus'   => $this->collectionPenaltyStatus,
        ]);
    }
}
