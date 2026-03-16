<x-layouts.app :title="__('Daily Report - Funga Hesabu')">

<div class="w-full px-4 sm:px-6 lg:px-8 py-6">

    {{-- Header --}}
    <div class="bg-gray-100 dark:bg-gray-900 mb-6">
        <div class="w-full bg-gradient-to-r from-cyan-600 to-blue-600 text-white px-4 md:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:justify-between py-4">
                <h1 class="text-lg font-semibold tracking-widest uppercase">
                    📊 Ripoti ya Siku - Funga Hesabu
                </h1>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="mb-6 flex flex-col sm:flex-row gap-3 items-end">
        <form method="GET" action="{{ route('daily.report') }}" class="flex flex-col sm:flex-row gap-3 items-end flex-1">
            <div class="w-full sm:w-auto">
                <label for="date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Chagua Tarehe</label>
                <input type="date" name="date" id="date" value="{{ $date->format('Y-m-d') }}"
                    class="bg-gray-50 border border-cyan-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-500 focus:border-cyan-500 block w-full p-2.5 dark:bg-gray-700 dark:border-cyan-600 dark:text-white">
            </div>
            <button type="submit" class="w-full sm:w-auto text-white bg-cyan-700 hover:bg-cyan-800 font-medium rounded-lg text-sm px-6 py-2.5">
                Tazama Ripoti
            </button>
            <a href="{{ route('daily.report.download-pdf', ['date' => request('date', today()->format('Y-m-d')), 'pay_type' => request('pay_type')]) }}"
               class="w-full sm:w-auto text-white bg-red-600 hover:bg-red-700 font-medium rounded-lg text-sm px-6 py-2.5 flex items-center justify-center gap-2">
                PDF
            </a>
            @if($date->format('Y-m-d') != today()->format('Y-m-d') || request('pay_type'))
            <a href="{{ route('daily.report') }}"
               class="w-full sm:w-auto text-gray-700 bg-white hover:bg-gray-100 border border-gray-300 font-medium rounded-lg text-sm px-6 py-2.5">
                Leo
            </a>
            @endif
        </form>

        {{-- Funga Hesabu Button with Alpine.js --}}
        <form method="POST" action="{{ route('daily.close-account') }}" x-data="{ closed: false }" @submit.prevent="closed = true; $el.submit()">
            @csrf
            <button type="submit"
                :disabled="closed"
                class="w-full sm:w-auto text-white bg-orange-600 hover:bg-orange-700 font-medium rounded-lg text-sm px-6 py-2.5"
                x-text="closed ? 'Hesabu imefungwa' : 'Funga Hesabu'">
            </button>
        </form>
    </div>

    {{-- Summary Cards --}}
    <div class="mb-6">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">
            📊 Hesabu ya Jumla - {{ $date->format('d/m/Y') }}
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            
            {{-- Total Members --}}
            <x-report.card title="Jumla ya Wanachama" :value="$totalMembers" color="indigo" icon="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m5-4a4 4 0 10-8 0 4 4 0 008 0zm6 4a4 4 0 10-8 0 4 4 0 008 0z"/>

            {{-- Completed Members --}}
            <x-report.card title="Waliomaliza Kulipa" :value="$completedMembers" color="teal" icon="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>

            {{-- Expected Today --}}
            <x-report.card title="Kiasi Hitajika Leo" :value="$expectedToday" color="blue" icon="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" description="Waliokuwa walipaswa kulipa"/>

            {{-- Total Paid Today --}}
            <x-report.card title="Jumla Waliolipa Leo" :value="$totaMemberPaidToday" color="green" icon="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </div>
    </div>

    {{-- Financial Summary --}}
    <div class="mb-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <x-report.card title="Malipo ya Michango" :value="$totalCollectionPayments" color="green" icon="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        <x-report.card title="Faini Iliyokusanywa" :value="$totalPenaltyPayments" color="orange" icon="M12 9v2m0 4h.01M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
        <x-report.card title="Matumizi" :value="$totalExpenses" color="red" icon="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
        <x-report.card title="Kiasi Kilichobaki" :value="$netAmount" color="gradient" icon="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" description="(Baada ya Matumizi)"/>
    </div>

</div>

</x-layouts.app>