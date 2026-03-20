<?php

use Livewire\Component;
use App\Models\Collection;
use App\Models\Member;
use App\Models\Payment;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

new class extends Component
{
    
    public $date;
    public $payType;
public $closeButtonLabel = 'Funga Hesabu';
public $closeButtonDisabled = false;
    // Data
    public $totalMembers;
    public $completedMembers;
    public $expectedToday;
    public $totalCollectionPayments;
    public $totalPenaltyPayments;
    public $totalExpenses;
    public $totalIncome;
    public $netAmount;
    public $payments = [];
    public $expenses = [];
    public $totaMemberPaidToday;
public function mount()
{
    $this->date = now()->format('Y-m-d');
    $this->payType = session('pay_type');

    $this->loadData();
    $this->checkClosedStatus();
}

   public function updatedDate()
{
    $this->loadData();
    $this->checkClosedStatus();
}

    public function updatedPayType()
    {
        $this->loadData();

           // Reset button if user changes the date
    $this->closeButtonLabel = 'Funga Hesabu';
    $this->closeButtonDisabled = false;
    }

    public function loadData()
    {
        $selectedDate = Carbon::parse($this->date);

        $payType = in_array($this->payType, ['mchango_mdogo', 'mchango_mkubwa'])
            ? $this->payType
            : null;

        // Total Members
        $this->totalMembers = Member::when($payType, fn($q) => $q->where('pay_type', $payType))
            ->whereDate('start_date', '<=', $selectedDate)
            ->count();

        // Completed Members
        $this->completedMembers = Collection::where('balance', '<=', 0)
            ->where('penalty_balance', '<=', 0)
            ->where('status', 'completed')
            ->count();

        // Expected Today
        $this->expectedToday = 0;

        $members = Member::when($payType, fn($q) => $q->where('pay_type', $payType))
            ->whereDate('start_date', '<=', $selectedDate)
            ->get();

        foreach ($members as $member) {
            $collection = $member->collections()->first();

            if ($collection && $collection->balance > 0) {
                if ($member->type === 'daily') {
                    $this->expectedToday += $member->amount;

                } elseif ($member->type === 'weekly') {
                    $last = $collection->last_payment_date ?? $collection->created_at;
                    if ($last->diffInDays($selectedDate) >= 7) {
                        $this->expectedToday += $member->amount;
                    }

                } elseif ($member->type === 'monthly') {
                    $last = $collection->last_payment_date ?? $collection->created_at;
                    if ($last->diffInDays($selectedDate) >= 30) {
                        $this->expectedToday += $member->amount;
                    }
                }
            }
        }

        // Payments
        $this->totalCollectionPayments = Payment::whereDate('payment_date', $selectedDate)
            ->where('payment_type', 'regular')
            ->when($payType, fn($q) =>
                $q->whereHas('member', fn($m) => $m->where('pay_type', $payType))
            )
            ->sum('amount');

        $this->totaMemberPaidToday = Payment::whereDate('payment_date', $selectedDate)
            ->where('payment_type', 'regular')
            ->when($payType, fn($q) =>
                $q->whereHas('member', fn($m) => $m->where('pay_type', $payType))
            )
            ->distinct('member_id')
            ->count('member_id');

        $this->totalPenaltyPayments = Payment::whereDate('payment_date', $selectedDate)
            ->where('payment_type', 'penalty')
            ->when($payType, fn($q) =>
                $q->whereHas('member', fn($m) => $m->where('pay_type', $payType))
            )
            ->sum('amount');

        $this->totalExpenses = Expense::whereDate('expense_date', $selectedDate)->sum('amount');

        $this->totalIncome = $this->totalCollectionPayments + $this->totalPenaltyPayments;
        $this->netAmount = $this->totalIncome - $this->totalExpenses;

        $this->payments = Payment::with(['member', 'user'])
            ->whereDate('payment_date', $selectedDate)
            ->latest()
            ->get();

        $this->expenses = Expense::with('user')
            ->whereDate('expense_date', $selectedDate)
            ->latest()
            ->get();
    }


public function checkClosedStatus()
{
    $closed = \DB::table('closed_accounts')->whereDate('date', $this->date)->exists();

    if ($closed) {
        $this->closeButtonDisabled = true;
        $this->closeButtonLabel = 'Hesabu imefungwa kikamilifu tarehe ' . \Carbon\Carbon::parse($this->date)->format('d/m/Y');
    } else {
        $this->closeButtonDisabled = false;
        $this->closeButtonLabel = 'Funga Hesabu';
    }
}


public function closeAccount()
{
    $date = \Carbon\Carbon::parse($this->date);

    // Only allow closing for today or selected date if not already closed
    if (\DB::table('closed_accounts')->whereDate('date', $date)->exists()) {
        $this->checkClosedStatus();
        return;
    }

    $membersNotPaid = Member::whereDate('start_date', '<=', $date)
        ->whereDoesntHave('payments', fn($q) => $q->whereDate('payment_date', $date))
        ->get();

    \DB::transaction(function () use ($membersNotPaid) {
        foreach ($membersNotPaid as $member) {
            $collection = Collection::firstOrCreate(
                ['member_id' => $member->id],
                ['total_penalty' => 0, 'penalty_balance' => 0]
            );

            $collection->increment('total_penalty', $member->penalty_per_day);
            $collection->increment('penalty_balance', $member->penalty_per_day);
        }
    });

    // Save closed account
    \DB::table('closed_accounts')->insert([
        'date' => $date,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $this->checkClosedStatus();
    $this->loadData();

    session()->flash('success', 'Hesabu imefungwa kikamilifu tarehe ' . $date->format('d/m/Y'));
}

    public function downloadPdf()
{
    $date = \Carbon\Carbon::parse($this->date);

    $pdf = Pdf::loadView('reports.daily-pdf', [
        'date' => $date,
        'totalMembers' => $this->totalMembers,
        'completedMembers' => $this->completedMembers,
        'expectedToday' => $this->expectedToday,
        'totalCollectionPayments' => $this->totalCollectionPayments,
        'totalPenaltyPayments' => $this->totalPenaltyPayments,
        'totalExpenses' => $this->totalExpenses,
        'totalIncome' => $this->totalIncome,
        'netAmount' => $this->netAmount,
        'payments' => $this->payments,
        'expenses' => $this->expenses,
        'payType' => $this->payType, // ✅ Pass payType here
        'payTypeLabel' => $this->payType === 'mchango_mdogo' ? 'Mchango Mdogo' : ($this->payType === 'mchango_mkubwa' ? 'Mchango Mkubwa' : 'Zote'),
    ]);

    return response()->streamDownload(
        fn() => print($pdf->output()),
        'Ripoti_'.$date->format('d-m-Y').($this->payType ? '_' . $this->payType : '').'.pdf'
    );
}
}
?>

<div class="w-full px-4 sm:px-6 lg:px-8 py-6">

    {{-- Loading --}}
    <div wire:loading class="text-sm text-gray-500 dark:text-gray-300 mb-4">
        Inapakia taarifa...
    </div>

    {{-- Header --}}
    <h1 class="text-xl sm:text-2xl font-semibold mb-6 text-gray-900 dark:text-white">
        📊 Ripoti ya Siku - Funga Hesabu
    </h1>

    {{-- Filters --}}
    <div class="flex flex-col sm:flex-row gap-3 items-end mb-6">
        
        {{-- Date Picker --}}
        <div class="w-full sm:w-auto flex flex-col">
            <label class="text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Chagua Tarehe</label>
            <input type="date" wire:model.live="date"
                class="border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-sm rounded-lg px-3 py-2 focus:ring-2 focus:ring-cyan-500 focus:outline-none w-full sm:w-auto">
        </div>

        {{-- PDF Button --}}
        <button wire:click="downloadPdf"
            class="w-full sm:w-auto bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg px-4 py-2 transition">
            PDF
        </button>

      <button wire:click="closeAccount"
        wire:loading.attr="disabled"
        :disabled="{{ $closeButtonDisabled ? 'true' : 'false' }}"
        class="w-full sm:w-auto bg-orange-600 hover:bg-orange-700 text-white font-medium rounded-lg px-4 py-2 transition">
    <span wire:loading>Inafunga...</span>
    <span wire:loading.remove>{{ $closeButtonLabel }}</span>
</button>

    </div>

    {{-- Summary Header --}}
    <h2 class="text-lg sm:text-xl font-bold mb-4 text-gray-900 dark:text-white">
     Hesabu ya Tarehe {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
    </h2>

    {{-- Member Summary --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
        <x-report.card title="Wanachama" :value="$totalMembers"/>
        <x-report.card title="Waliomaliza Kulipa" :value="$completedMembers"/>
        <x-report.card title="Kiasi Hitajika Leo" :value="$expectedToday"/>
        <x-report.card title="Waliolipa Leo" :value="$totaMemberPaidToday"/>
    </div>

    {{-- Financial Summary --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mt-6">
        <x-report.card title="Michango" :value="$totalCollectionPayments"/>
        <x-report.card title="Faini" :value="$totalPenaltyPayments"/>
        <x-report.card title="Matumizi" :value="$totalExpenses"/>
        <x-report.card title="Kiasi Kilichobaki" :value="$netAmount"/>
    </div>

</div>