<x-layouts.app :title="__('Ripoti Ya Ambao Hawajalipa')">

<div class="w-full px-4 lg:px-8 py-6">

    {{-- Success Message --}}
    @if(session('success'))
        <div class="bg-teal-100 border border-teal-200 text-sm text-teal-800 rounded-lg p-4 mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="mb-6">
        <div class="bg-red-600 text-white p-4 rounded-lg">
            <h1 class="text-lg font-semibold uppercase">
                Ambao Hawajalipa (Deni Liliopo)
            </h1>
        </div>
    </div>

    {{-- Filter Form (Single Date) --}}
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md mb-6">
        <form method="GET" action="{{ route('unpaid.report') }}">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <div>
                    <label class="block text-sm font-medium mb-2">
                        Chagua Tarehe
                    </label>
                    <input
                        type="date"
                        name="date"
                        value="{{ $date }}"
                        class="w-full px-4 py-2 border rounded-lg"
                    >
                </div>

                <div class="flex items-end gap-2">
                    <button
                        type="submit"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg"
                    >
                        Chafya
                    </button>

                    <a
                        href="{{ route('unpaid.download-pdf', ['date' => $date]) }}"
                        class="px-4 py-2 bg-orange-600 text-white rounded-lg"
                    >
                        PDF
                    </a>
                </div>

            </div>
        </form>
    </div>

    {{-- Summary --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

        <div class="bg-white p-4 rounded-lg shadow border-l-4 border-red-500 dark:bg-cyan-950">
            <div class="text-sm dark:text-white">Jumla ya Wanachama</div>
            <div class="text-2xl font-bold text-red-600">
                {{ $summary['total_members'] }}
            </div>
        </div>

        <div class="bg-white p-4 rounded-lg shadow border-l-4 border-blue-500  dark:bg-cyan-950">
            <div class="text-sm dark:text-white">Jumla ya Kulipwa</div>
            <div class="text-2xl font-bold text-blue-600">
                {{ number_format($summary['total_amount_paid'], 0) }}
            </div>
        </div>

        <div class="bg-white p-4 rounded-lg shadow border-l-4 border-red-700  dark:bg-cyan-950">
            <div class="text-sm dark:text-white">Jumla ya Deni</div>
            <div class="text-2xl font-bold text-red-700">
                {{ number_format($summary['total_amount_owed'], 0) }}
            </div>
        </div>

    </div>

    {{-- Table --}}
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-red-400 font-semibold dark:text-black">
                    <tr>
                        <th class="px-6 py-3 dark:text-black">Tarehe</th>
                        <th class="px-6 py-3 dark:text-black">Jina la Mwanachama</th>
                        <th class="px-6 py-3  text-white ">Simu</th>
                        <th class="px-6 py-3 text-right dark:text-black">Jumla</th>
                        <th class="px-6 py-3 text-right dark:text-black">Kulipwa</th>
                        <th class="px-6 py-3 text-right dark:text-black">Baki</th>
                      
                    </tr>
                </thead>

                <tbody class="divide-y text-white bg-cyan-900 dark:divide-gray-700">
                    @forelse($collections as $collection)
                        <tr class="">

                            <td class="px-6 py-3">
                                {{ \Carbon\Carbon::parse($date)->format('d-m-Y') }}
                            </td>

                            <td class="px-6 py-3 font-medium">
                                <a href="{{ route('collections.show', $collection->member->id) }}"
                                   class="text-white hover:underline">
                                    {{ $collection->member->name }}
                                </a>
                            </td>

                            <td class="px-6 py-3">
                                {{ $collection->member->phone }}
                            </td>

                            <td class="px-6 py-3 text-right">
                                {{ number_format($collection->total_amount, 0) }}
                            </td>

                            <td class="px-6 py-3 text-right text-white">
                                {{ number_format($collection->amount_paid, 0) }}
                            </td>

                            <td class="px-6 py-3 text-right text-red-600">
                                {{ number_format($collection->balance, 0) }}
                            </td>

                          
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-6 text-center text-gray-500">
                                Wote wamelipa tarehe {{ \Carbon\Carbon::parse($date)->format('d-m-Y') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-6 py-4 border-t">
            {{ $collections->appends(request()->query())->links() }}
        </div>
    </div>

</div>

</x-layouts.app>
