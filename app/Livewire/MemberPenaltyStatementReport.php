<?php

namespace App\Livewire;

use App\Models\Member;
use Livewire\Component;

class MemberPenaltyStatementReport extends Component
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

        return Member::query()->when($payType, fn ($query) => $query->where('pay_type', $payType));
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

    public function render()
    {
        return view('livewire.member-penalty-statement-report', [
            'memberOptions' => $this->memberOptions,
            'selectedMember' => $this->selectedMember,
        ]);
    }
}
