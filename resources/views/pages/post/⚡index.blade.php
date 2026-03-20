<?php

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Payment;

new class extends Component
{
    
    use WithPagination;

    public $payment_date;
    public $search = '';
    public $pay_type = '';

    public function mount()
    {
        $this->payment_date = now()->toDateString();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPaymentDate()
    {
        $this->resetPage();
    }

    public function updatingPayType()
    {
        $this->resetPage();
    }

    public function getPaymentsProperty()
    {
        return Payment::with(['member', 'user'])
            ->where('payment_type', 'regular')
            ->whereDate('payment_date', $this->payment_date)
            ->when($this->pay_type, fn($q) =>
                $q->whereHas('member', fn($q2) =>
                    $q2->where('pay_type', $this->pay_type)
                )
            )
            ->when($this->search, fn($q) =>
                $q->whereHas('member', fn($q2) =>
                    $q2->where('name', 'like', "%{$this->search}%")
                )
            )
            ->latest()
            ->paginate(10);
    }

    public function getSummaryProperty()
    {
        $query = Payment::where('payment_type', 'regular')
            ->whereDate('payment_date', $this->payment_date)
            ->when($this->pay_type, fn($q) =>
                $q->whereHas('member', fn($q2) =>
                    $q2->where('pay_type', $this->pay_type)
                )
            )
            ->when($this->search, fn($q) =>
                $q->whereHas('member', fn($q2) =>
                    $q2->where('name', 'like', "%{$this->search}%")
                )
            );

        return [
            'total_payments' => $query->count(),
            'total_amount' => $query->sum('amount'),
            'total_members' => $query->distinct('member_id')->count('member_id'),
        ];
    }
};
?>

<div class="p-4">

    <!-- 🔍 Filters -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">

        <!-- Date -->
        <input 
            type="date" 
            wire:model.live="payment_date"
            class="px-4 py-2 border rounded-lg"
        >

        <!-- Pay Type -->
        <select wire:model.live="pay_type" class="px-4 py-2 border rounded-lg">
            <option value="">Wote</option>
            <option value="mchango_mdogo">Mchango Mdogo</option>
            <option value="mchango_mkubwa">Mchango Mkubwa</option>
        </select>

        <!-- Search -->
        <input 
            type="text" 
            wire:model.live.debounce.300ms="search"
            placeholder="Tafuta jina la mwanachama..."
            class="px-4 py-2 border rounded-lg"
        >

    </div>

    <!-- 📊 Summary -->
    <div class="grid grid-cols-3 gap-4 mb-4 text-sm">
        <div class="bg-white p-3 rounded shadow">
            Malipo: {{ $this->summary['total_payments'] }}
        </div>
        <div class="bg-white p-3 rounded shadow">
            Kiasi: {{ number_format($this->summary['total_amount']) }}
        </div>
        <div class="bg-white p-3 rounded shadow">
            Wanachama: {{ $this->summary['total_members'] }}
        </div>
    </div>

    <!-- 📋 Table -->
    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 text-left">Jina</th>
                    <th class="p-2 text-left">Tarehe</th>
                    <th class="p-2 text-right">Kiasi</th>
                    <th class="p-2 text-left">User</th>
                </tr>
            </thead>
            <tbody>
                @forelse($this->payments as $payment)
                    <tr class="border-t">
                        <td class="p-2">{{ $payment->member->name }}</td>
                        <td class="p-2">{{ $payment->payment_date->format('d/m/Y') }}</td>
                        <td class="p-2 text-right">{{ number_format($payment->amount) }}</td>
                        <td class="p-2">{{ $payment->user->name ?? 'N/A' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-4 text-center text-gray-500">
                            Hakuna matokeo
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="p-3">
            {{ $this->payments->links() }}
        </div>
    </div>

</div>