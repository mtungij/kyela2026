<?php

namespace App\Livewire;

use App\Models\Member;
use Livewire\Component;

class MemberPenaltyStatementReport extends Component
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

        return Member::query()->when($payType, fn ($query) => $query->where('pay_type', $payType));
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
            ->where(function ($query) use ($term) {
                $query->where('name', 'like', "%{$term}%")
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

    public function render()
    {
        return view('livewire.member-penalty-statement-report', [
            'searchResults' => $this->searchResults,
            'selectedMember' => $this->selectedMember,
        ]);
    }
}
