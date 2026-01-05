<x-layouts.app :title="__('Ambao Wamelipa - Ripoti Ya Malipo')">

<div class="w-full px-4 lg:px-8 py-6">
    @if(session('success'))
    <div class="bg-teal-100 border border-teal-200 text-sm text-teal-800 rounded-lg p-4 dark:bg-teal-800/10 dark:border-teal-900 dark:text-teal-500 mb-4" role="alert">
        <div class="flex">
            <div class="flex-shrink-0">
                <span class="inline-flex justify-center items-center size-8 rounded-full border-4 border-teal-100 bg-teal-200 text-teal-800 dark:border-teal-900 dark:bg-teal-800 dark:text-teal-500">
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"></path>
                        <path d="m9 12 2 2 4-4"></path>
                    </svg>
                </span>
            </div>
            <div class="ms-3">
                <h3 class="text-gray-800 font-semibold dark:text-white">Karibu</h3>
                <p class="mt-2 text-sm text-gray-700 dark:text-gray-400">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Header -->
    <div class="bg-gray-100 dark:bg-gray-900 mb-6">
        <div class="w-full bg-cyan-600 text-white">
            <div class="flex flex-col max-w-screen-xl px-4 mx-auto md:flex-row md:justify-between md:px-6 lg:px-8">
                <div class="p-4 flex flex-row items-center justify-between w-full">
                    <h1 class="text-lg font-semibold tracking-widest uppercase rounded-lg focus:outline-none focus:shadow-outline">
                        Ambao Wamelipa - Ripoti Ya Malipo
                    </h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md mb-6">
        <form method="GET" action="{{ route('payments.report') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- From Date -->
                <div>
                    <label for="from_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Tarehe Ya Kuanza
                    </label>
                    <input 
                        type="date" 
                        name="from_date" 
                        id="from_date"
                        value="{{ $fromDate }}"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500 dark:bg-gray-700 dark:text-white"
                    />
                </div>

                <!-- To Date -->
                <div>
                    <label for="to_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Tarehe Ya Kuishia
                    </label>
                    <input 
                        type="date" 
                        name="to_date" 
                        id="to_date"
                        value="{{ $toDate }}"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500 dark:bg-gray-700 dark:text-white"
                    />
                </div>

                <!-- Action Buttons -->
                <div class="flex items-end gap-2">
                    <button 
                        type="submit" 
                        class="flex-1 px-4 py-2 bg-cyan-600 hover:bg-cyan-700 text-white font-medium rounded-lg transition-colors"
                    >
                        Chafya
                    </button>
                    <a 
                        href="{{ route('payments.download-pdf', ['from_date' => $fromDate, 'to_date' => $toDate]) }}"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors flex items-center gap-2"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2m0 0v-8m0 8l-6-4m6 4l6-4"></path>
                        </svg>
                        PDF
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <!-- Total Payments -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md border-l-4 border-cyan-500">
            <div class="text-sm text-gray-600 dark:text-gray-400 font-medium">Jumla ya Malipo</div>
            <div class="text-2xl font-bold text-cyan-600 dark:text-cyan-400">{{ $summary['total_payments'] }}</div>
        </div>

        <!-- Total Amount -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md border-l-4 border-green-500">
            <div class="text-sm text-gray-600 dark:text-gray-400 font-medium">Jumla ya Kiasi</div>
            <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ number_format($summary['total_amount'], 0) }}</div>
        </div>

        <!-- Total Members -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md border-l-4 border-purple-500">
            <div class="text-sm text-gray-600 dark:text-gray-400 font-medium">Jumla ya Wanachama</div>
            <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $summary['total_members'] }}</div>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
                <thead class="bg-cyan-100 dark:bg-cyan-900 text-cyan-800 dark:text-cyan-200 font-semibold sticky top-0">
                    <tr>
                        <th class="px-6 py-3 border-b dark:border-gray-700">Jina la Mwanachama</th>
                        <th class="px-6 py-3 border-b dark:border-gray-700">Simu</th>
                        <th class="px-6 py-3 border-b dark:border-gray-700">Tarehe ya Malipo</th>
                        <th class="px-6 py-3 border-b dark:border-gray-700 text-right">Kiasi</th>
                        <th class="px-6 py-3 border-b dark:border-gray-700">Kumbuka</th>
                        <th class="px-6 py-3 border-b dark:border-gray-700">Alirekodi na</th>
                        <th class="px-6 py-3 border-b dark:border-gray-700 text-center">Hatua</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-6 py-3 font-medium text-gray-900 dark:text-white">
                                <a href="{{ route('collections.show', $payment->member->id) }}" class="text-cyan-600 dark:text-cyan-400 hover:underline">
                                    {{ $payment->member->name }}
                                </a>
                            </td>
                            <td class="px-6 py-3">
                                {{ $payment->member->phone }}
                            </td>
                            <td class="px-6 py-3">
                                {{ $payment->payment_date->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-3 text-right font-semibold text-green-600 dark:text-green-400">
                                {{ number_format($payment->amount, 0) }}
                            </td>
                            <td class="px-6 py-3 text-xs text-gray-500 dark:text-gray-400">
                                {{ $payment->notes ?? '-' }}
                            </td>
                            <td class="px-6 py-3 text-xs">
                                {{ $payment->user->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-3 text-center">
                                @if(auth()->user()->isAdmin())
                                <form action="{{ route('payments.delete', $payment->id) }}" method="POST" class="inline" onsubmit="return confirm('Je, una hakika kuwa ungetaka kufuta malipo haya? Hatua hii haiwezi kurudi.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs font-medium rounded transition-colors">
                                        Futa
                                    </button>
                                </form>
                                @else
                                <span class="text-gray-400 text-xs">N/A</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                Hakuna malipo kwenye kipindi hiki
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $payments->appends(request()->query())->links() }}
        </div>
    </div>
</div>

</x-layouts.app>
