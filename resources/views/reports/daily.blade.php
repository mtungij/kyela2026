<x-layouts.app :title="__('Daily Report - Funga Hesabu')">

<div class="w-full max-w-screen-xl px-4 sm:px-6 lg:px-8 py-6">
    
    <!-- Header -->
    <div class="bg-gray-100 dark:bg-gray-900 mb-6">
        <div class="w-full bg-gradient-to-r from-cyan-600 to-blue-600 text-white">
            <div class="flex flex-col max-w-screen-xl px-4 mx-auto md:flex-row md:justify-between md:px-6 lg:px-8">
                <div class="p-4 flex flex-row items-center justify-between">
                    <h1 class="text-lg font-semibold tracking-widest uppercase rounded-lg focus:outline-none focus:shadow-outline">
                        📊 Ripoti ya Siku - Funga Hesabu
                    </h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Date Filter -->
    <div class="mb-6">
        <form method="GET" action="{{ route('daily.report') }}" class="flex flex-col sm:flex-row gap-3 items-end">
            <div class="w-full sm:w-auto">
                <label for="date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Chagua Tarehe</label>
                <input type="date" name="date" id="date" value="{{ $date->format('Y-m-d') }}" 
                    class="bg-gray-50 border border-cyan-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-500 focus:border-cyan-500 block w-full p-2.5 dark:bg-gray-700 dark:border-cyan-600 dark:placeholder-gray-400 dark:text-white">
            </div>
            <button type="submit" class="w-full sm:w-auto text-white bg-cyan-700 hover:bg-cyan-800 focus:ring-4 focus:ring-cyan-300 font-medium rounded-lg text-sm px-6 py-2.5 dark:bg-cyan-600 dark:hover:bg-cyan-700 focus:outline-none dark:focus:ring-cyan-800">
                Tazama Ripoti
            </button>
            @if($date->format('Y-m-d') != today()->format('Y-m-d'))
            <a href="{{ route('daily.report') }}" class="w-full sm:w-auto text-gray-700 bg-white hover:bg-gray-100 border border-gray-300 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-6 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-700">
                Leo
            </a>
            @endif
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="mb-6">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">
            📊 Hesabu ya Jumla - {{ $date->format('d/m/Y') }}
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            
            <!-- Total Members -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md border-l-4 border-indigo-500">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Jumla ya Wanachama</h3>
                        <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400 mt-2">
                            {{ number_format($totalMembers) }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/40 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m5-4a4 4 0 10-8 0 4 4 0 008 0zm6 4a4 4 0 10-8 0 4 4 0 008 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Completed Members -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md border-l-4 border-teal-500">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Waliomaliza Kulipa</h3>
                        <p class="text-2xl font-bold text-teal-600 dark:text-teal-400 mt-2">
                            {{ number_format($completedMembers) }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-teal-100 dark:bg-teal-900/40 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-teal-600 dark:text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Expected Today -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Kiasi Hitajika Leo</h3>
                        <p class="text-2xl font-bold text-blue-600 dark:text-blue-400 mt-2">
                            {{ number_format($expectedToday, 2) }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Waliokuwa walipaswa kulipa</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/40 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Paid Today -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Jumla Waliolipa Leo</h3>
                        <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-2">
                            {{ number_format($totalCollectionPayments, 2) }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/40 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Financial Summary -->
    <div class="mb-6">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">
            💰 Muhtasari wa Fedha
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Collection Payments -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Malipo ya Michango</h3>
                        <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-2">
                            TZS {{ number_format($totalCollectionPayments, 2) }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/40 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Penalty Payments -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md border-l-4 border-orange-500">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Malipo ya Faini</h3>
                        <p class="text-2xl font-bold text-orange-600 dark:text-orange-400 mt-2">
                            + TZS {{ number_format($totalPenaltyPayments, 2) }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/40 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Expenses -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md border-l-4 border-red-500">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Gharama</h3>
                        <p class="text-2xl font-bold text-red-600 dark:text-red-400 mt-2">
                            - TZS {{ number_format($totalExpenses, 2) }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900/40 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Net Amount (Remainder) -->
            <div class="bg-gradient-to-br from-cyan-500 to-blue-600 p-6 rounded-lg shadow-lg text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium opacity-90">SALIO / NET</h3>
                        <p class="text-3xl font-bold mt-2">
                            TZS {{ number_format($netAmount, 2) }}
                        </p>
                        <p class="text-xs mt-1 opacity-80">
                            ({{ $netAmount >= 0 ? 'Faida' : 'Hasara' }})
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Summary -->
    <div class="mb-6">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">
            💰 Muhtasari wa Fedha
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Collection Payments -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Malipo ya Michango</h3>
                        <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-2">
                            {{ number_format($totalCollectionPayments, 2) }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/40 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Penalty Payments -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md border-l-4 border-orange-500">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Faini Iliyokusanywa</h3>
                        <p class="text-2xl font-bold text-orange-600 dark:text-orange-400 mt-2">
                            + {{ number_format($totalPenaltyPayments, 2) }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/40 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Expenses -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md border-l-4 border-red-500">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Matumizi</h3>
                        <p class="text-2xl font-bold text-red-600 dark:text-red-400 mt-2">
                            - {{ number_format($totalExpenses, 2) }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900/40 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Net Amount (Remainder After Expenses) -->
            <div class="bg-gradient-to-br from-cyan-500 to-blue-600 p-6 rounded-lg shadow-lg text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium opacity-90">Kiasi Kilichobaki</h3>
                        <p class="text-xs opacity-80 mb-1">(Baada ya Matumizi)</p>
                        <p class="text-3xl font-bold mt-1">
                            {{ number_format($netAmount, 2) }}
                        </p>
                        <p class="text-xs mt-1 opacity-80">
                            ({{ $netAmount >= 0 ? 'Faida' : 'Hasara' }})
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Calculation Formula -->
    <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
        <h3 class="text-sm font-bold text-blue-800 dark:text-blue-400 mb-2">📐 Hesabu ya Kiasi Kilichobaki:</h3>
        <p class="text-sm text-blue-700 dark:text-blue-300">
            <span class="font-mono">Malipo ya Michango ({{ number_format($totalCollectionPayments, 2) }}) 
            + Faini Iliyokusanywa ({{ number_format($totalPenaltyPayments, 2) }}) 
            - Matumizi ({{ number_format($totalExpenses, 2) }}) 
            = <strong class="text-lg">{{ number_format($netAmount, 2) }}</strong></span>
        </p>
    </div>

    <!-- Detailed Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Payments Table -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
            <div class="p-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
                    Malipo ({{ $payments->count() }})
                </h2>
                
                @if($payments->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-cyan-600 dark:bg-cyan-700">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Member</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Aina</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-white uppercase">Kiasi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($payments as $payment)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                    {{ $payment->member->name ?? 'N/A' }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $payment->payment_type === 'penalty' ? 'bg-orange-100 text-orange-800 dark:bg-orange-900/40 dark:text-orange-400' : 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-400' }}">
                                        {{ $payment->payment_type === 'penalty' ? 'Faini' : 'Mchango' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm font-bold text-right text-green-600 dark:text-green-400">
                                    {{ number_format($payment->amount, 2) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-green-50 dark:bg-green-900/20">
                            <tr>
                                <td colspan="2" class="px-4 py-3 text-sm font-bold text-gray-900 dark:text-white">JUMLA</td>
                                <td class="px-4 py-3 text-sm font-bold text-right text-green-600 dark:text-green-400">
                                    {{ number_format($totalIncome, 2) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @else
                <p class="text-center text-gray-500 dark:text-gray-400 py-8">Hakuna malipo yaliyorekodiwa</p>
                @endif
            </div>
        </div>

        <!-- Expenses Table -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
            <div class="p-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
                    Matumizi ({{ $expenses->count() }})
                </h2>
                
                @if($expenses->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-red-600 dark:bg-red-700">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Aina</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Maelezo</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-white uppercase">Kiasi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($expenses as $expense)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-4 py-3 text-sm">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-400">
                                        {{ $expense->category }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                    {{ Str::limit($expense->description, 30) }}
                                </td>
                                <td class="px-4 py-3 text-sm font-bold text-right text-red-600 dark:text-red-400">
                                    {{ number_format($expense->amount, 2) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-red-50 dark:bg-red-900/20">
                            <tr>
                                <td colspan="2" class="px-4 py-3 text-sm font-bold text-gray-900 dark:text-white">JUMLA</td>
                                <td class="px-4 py-3 text-sm font-bold text-right text-red-600 dark:text-red-400">
                                    {{ number_format($totalExpenses, 2) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @else
                <p class="text-center text-gray-500 dark:text-gray-400 py-8">Hakuna Matumizi yaliyorekodiwa</p>
                @endif
            </div>
        </div>

    </div>

</div>

</x-layouts.app>
