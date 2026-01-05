<x-layouts.app :title="__('Ripoti Ya Faini - Ambao Waliooza')">

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
        <div class="w-full bg-orange-600 text-white">
            <div class="flex flex-col max-w-screen-xl px-4 mx-auto md:flex-row md:justify-between md:px-6 lg:px-8">
                <div class="p-4 flex flex-row items-center justify-between w-full">
                    <h1 class="text-lg font-semibold tracking-widest uppercase rounded-lg focus:outline-none focus:shadow-outline">
                        Ripoti Ya Faini - Ambao Waliooza
                    </h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md mb-6">
        <form method="GET" action="{{ route('penalties.report') }}" class="space-y-4">
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
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 dark:bg-gray-700 dark:text-white"
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
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 dark:bg-gray-700 dark:text-white"
                    />
                </div>

                <!-- Action Buttons -->
                <div class="flex items-end gap-2">
                    <button 
                        type="submit" 
                        class="flex-1 px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white font-medium rounded-lg transition-colors"
                    >
                        Chafya
                    </button>
                    <a 
                        href="{{ route('penalties.download-pdf', ['from_date' => $fromDate, 'to_date' => $toDate]) }}"
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
        <!-- Total Members -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md border-l-4 border-orange-500">
            <div class="text-sm text-gray-600 dark:text-gray-400 font-medium">Jumla ya Wanachama</div>
            <div class="text-2xl font-bold text-orange-600 dark:text-orange-400">{{ $summary['total_members'] }}</div>
        </div>

        <!-- Total Penalty Paid -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md border-l-4 border-green-500">
            <div class="text-sm text-gray-600 dark:text-gray-400 font-medium">Jumla ya Faini Zilizolipwa</div>
            <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ number_format($summary['total_penalty_paid'], 0) }}</div>
        </div>

        <!-- Total Penalty Balance -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md border-l-4 border-red-500">
            <div class="text-sm text-gray-600 dark:text-gray-400 font-medium">Jumla ya Faini Iliyobaki</div>
            <div class="text-2xl font-bold text-red-600 dark:text-red-400">{{ number_format($summary['total_penalty_balance'], 0) }}</div>
        </div>
    </div>

    <!-- Penalties Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
                <thead class="bg-orange-100 dark:bg-orange-900 text-orange-800 dark:text-orange-200 font-semibold sticky top-0">
                    <tr>
                        <th class="px-6 py-3 border-b dark:border-gray-700">Jina la Mwanachama</th>
                        <th class="px-6 py-3 border-b dark:border-gray-700">Simu</th>
                        <th class="px-6 py-3 border-b dark:border-gray-700 text-right">Jumla ya Faini</th>
                        <th class="px-6 py-3 border-b dark:border-gray-700 text-right">Faini Zilizolipwa</th>
                        <th class="px-6 py-3 border-b dark:border-gray-700 text-right">Faini Iliyobaki</th>
                        <th class="px-6 py-3 border-b dark:border-gray-700">Hali</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($collections as $collection)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-6 py-3 font-medium text-gray-900 dark:text-white">
                                <a href="{{ route('collections.show', $collection->member->id) }}" class="text-orange-600 dark:text-orange-400 hover:underline">
                                    {{ $collection->member->name }}
                                </a>
                            </td>
                            <td class="px-6 py-3">
                                {{ $collection->member->phone }}
                            </td>
                            <td class="px-6 py-3 text-right font-semibold text-orange-600 dark:text-orange-400">
                                {{ number_format($collection->total_penalty, 0) }}
                            </td>
                            <td class="px-6 py-3 text-right font-semibold text-green-600 dark:text-green-400">
                                {{ number_format($collection->penalty_paid, 0) }}
                            </td>
                            <td class="px-6 py-3 text-right font-semibold text-red-600 dark:text-red-400">
                                {{ number_format($collection->penalty_balance, 0) }}
                            </td>
                            <td class="px-6 py-3">
                                @if($collection->penalty_balance <= 0)
                                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-500 text-white">Malipo Kamili</span>
                                @elseif($collection->penalty_paid > 0)
                                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-yellow-500 text-white">Inaendelea</span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-red-500 text-white">Haujajipa</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                Hakuna faini katika kipindi hiki
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $collections->appends(request()->query())->links() }}
        </div>
    </div>
</div>

</x-layouts.app>
