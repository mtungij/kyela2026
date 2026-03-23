<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700 p-6 space-y-6">
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="p-4 rounded-lg bg-cyan-50 dark:bg-cyan-900/30">
            <div class="text-sm text-cyan-700 dark:text-cyan-300">Jumla ya Kipindi</div>
            <div class="text-xl font-bold text-cyan-800 dark:text-cyan-200">{{ $this->summary['expected_periods'] }}</div>
        </div>
        <div class="p-4 rounded-lg bg-green-50 dark:bg-green-900/30">
            <div class="text-sm text-green-700 dark:text-green-300">Zilizolipwa</div>
            <div class="text-xl font-bold text-green-800 dark:text-green-200">{{ $this->summary['paid_periods'] }}</div>
        </div>
        <div class="p-4 rounded-lg bg-purple-50 dark:bg-purple-900/30">
            <div class="text-sm text-purple-700 dark:text-purple-300">Jumla Kiasi</div>
            <div class="text-xl font-bold text-purple-800 dark:text-purple-200">{{ number_format($this->summary['total_amount'], 0) }}</div>
        </div>
        <div class="p-4 rounded-lg bg-blue-50 dark:bg-blue-900/30">
            <div class="text-sm text-blue-700 dark:text-blue-300">Baki</div>
            <div class="text-xl font-bold text-blue-800 dark:text-blue-200">{{ number_format($collection?->balance ?? 0, 0) }}</div>
        </div>
    </div>

   
    @if(session('success'))
        <div class="p-3 rounded-lg bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
             {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col sm:flex-row sm:items-end gap-3 w-full">
        <div class="w-full">
            <label for="dateSearch" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tafuta kwa Tarehe</label>
            <input
                type="date"
                id="dateSearch"
                wire:model.live="dateSearch"
                class="w-full px-3 py-2 rounded-lg focus:outline-none dark:bg-gray-700 dark:text-white"
            />
        </div>
    </div>

    <!-- Payment Schedule Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
            <thead class="bg-cyan-100 dark:bg-cyan-900 text-cyan-800 dark:text-cyan-200 font-semibold sticky top-0">
                <tr>
                    <th class="px-4 py-3 border-b dark:border-gray-700">Tarehe</th>
                   
                    <th class="px-4 py-3 border-b dark:border-gray-700 text-right">Kiasi</th>
                   
                    <th class="px-4 py-3 border-b dark:border-gray-700 text-center">Hali</th>
                    <th class="px-4 py-3 border-b dark:border-gray-700 text-center">Faini</th>
                     <th class="px-4 py-3 border-b dark:border-gray-700 text-center">Hesabu</th>
                    <th class="px-4 py-3 border-b dark:border-gray-700 text-center">Hatua</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($this->filteredPaymentSchedule as $item)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-4 py-3 font-medium">{{ $item['date'] }}</td>
                       
                        <td class="px-4 py-3 text-right font-semibold {{ $item['is_paid'] ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                            @if($item['amount'] !== null)
                                {{ number_format($item['amount'], 0) }}
                            @else
                                —
                            @endif
                        </td>
                     
                        <td class="px-4 py-3 text-center">
                            @if($item['is_paid'])
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300">
                                    ✅ 
                                </span>
                            @else
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300">
                                    ❌ 
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
    @if($item['is_closed'])
        @if($item['is_paid'])
            <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300">
                Hana Faini
            </span>
        @else
            <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300">
                Amepewa Faini
            </span>
        @endif
    @else
        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300">
            Haikufungwa
        </span>
    @endif
</td>
                        <td class="px-4 py-3 text-center">
                            @if($item['is_closed'])
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-cyan-100 text-cyan-700 dark:bg-cyan-900/40 dark:text-cyan-300">
                                    Ilifungwa
                                </span>
                            @else
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                    Haikufungwa
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if(!$item['is_paid'])
                                <button
                                    type="button"
                                    wire:click="openPaymentModal(@js($item))"
                                    class="px-3 py-1 text-xs font-semibold rounded-lg bg-cyan-600 hover:bg-cyan-700 text-white transition-colors"
                                >
                                     Lipa
                                </button>
                            @else
                                <span class="text-gray-400 text-xs">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                            Hakuna mgawanyiko wa malipo unaowezekana kwa kipindi hiki
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Schedule Info -->
    <div class="text-xs text-gray-600 dark:text-gray-400 space-y-1 p-3 rounded-lg bg-gray-50 dark:bg-gray-900/20">
        <p><strong>Kipindi:</strong> {{ $member->start_date?->format('d/m/Y') }} - {{ $member->end_date?->format('d/m/Y') }}</p>
        <p><strong>Aina ya Malipo:</strong> 
            @switch($member->type)
                @case('daily')
                    Kila Siku
                    @break
                @case('weekly')
                    Kila Wiki
                    @break
                @case('monthly')
                    Kila Mwezi
                    @break
                @default
                    {{ $member->type }}
            @endswitch
        </p>
        <p><strong>Kiasi kwa Kipindi:</strong> {{ number_format($member->amount, 0) }} TSh</p>
    </div>

  

    <!-- Payment Modal -->
    <div x-show="$wire.showPaymentModal" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center" style="display: {{ $showPaymentModal ? 'flex' : 'none' }};">
        <div class="fixed inset-0 bg-black bg-opacity-50" wire:click="closePaymentModal()"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4 p-6 z-10">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">📝 Ingiza Malipo</h3>
                <button
                    type="button"
                    wire:click="closePaymentModal()"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                >
                    ✕
                </button>
            </div>

            @if($selectedPayment)
                <div class="mb-4 p-3 rounded-lg bg-gray-100 dark:bg-gray-700">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        <strong>Tarehe:</strong> {{ $selectedPayment['date'] }}
                    </p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        <strong>Inaposomeka:</strong> {{ number_format($member->amount, 0) }} TSh
                    </p>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        <strong>Penalty:</strong>
                        @if($selectedPayment['is_closed'] ?? false)
                            @if($selectedPayment['penalty_charged'] ?? false)
                                <span class="text-red-600 dark:text-red-400 font-semibold">Imepigwa Faini</span>
                            @else
                                <span class="text-green-600 dark:text-green-400 font-semibold">Hakuna Faini</span>
                            @endif
                        @else
                            <span class="text-gray-500 dark:text-gray-300 font-semibold">Haikufungwa</span>
                        @endif
                    </p>
                </div>
            @endif

            <form wire:submit="submitPaymentFromModal()" class="space-y-4">
                <div>
                    <label for="modalAmount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Kiasi (TSh)
                    </label>
                    <input
                        type="number"
                        id="modalAmount"
                        wire:model="modalAmount"
                        step="0.01"
                        min="0.01"
                        required
                        placeholder="Weka kiasi"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500 dark:bg-gray-700 dark:text-white"
                    />
                    @error('modalAmount') <span class="text-red-600 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="modalPaymentDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Tarehe ya Malipo
                    </label>
                    <input
                        type="date"
                        id="modalPaymentDate"
                        wire:model="modalPaymentDate"
                        readonly
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-300 cursor-not-allowed"
                    />
                </div>

                <div class="flex gap-3 pt-4">
                    <button
                        type="submit"
                        class="flex-1 px-4 py-2 bg-cyan-600 hover:bg-cyan-700 text-white font-medium rounded-lg transition-colors"
                    >
                        ✅ Lipa
                    </button>
                    <button
                        type="button"
                        wire:click="closePaymentModal()"
                        class="flex-1 px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-900 font-medium rounded-lg transition-colors dark:bg-gray-600 dark:hover:bg-gray-700 dark:text-white"
                    >
                        Ghairi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
