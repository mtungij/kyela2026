<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
           <div class="max-w-sm">
    <a href="{{ route('members.index') }}" class="block hover:scale-105 transition-transform duration-200">
        <div class="flex items-center justify-between p-6 rounded-2xl shadow
                    bg-white dark:bg-gray-900
                    border border-gray-200 dark:border-gray-700
                    hover:border-indigo-400 dark:hover:border-indigo-600
                    transition-colors duration-300">

            <!-- Content -->
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    Jumla ya Wanachama
                </p>

                <h2 class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">
                    {{ number_format($totalMembers) }}
                </h2>

                <p class="mt-1 text-xs text-green-600 dark:text-green-400">
                    Bonyeza kuona orodha
                </p>
            </div>

            <!-- Icon -->
            <div class="flex items-center justify-center w-14 h-14 rounded-xl
                        bg-indigo-100 dark:bg-indigo-900/40">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-7 h-7 text-indigo-600 dark:text-indigo-300"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 20h5v-2a4 4 0 00-4-4h-1
                             M9 20H4v-2a4 4 0 014-4h1
                             m5-4a4 4 0 10-8 0 4 4 0 008 0
                             m6 4a4 4 0 10-8 0 4 4 0 008 0" />
                </svg>
            </div>

        </div>
    </a>
</div>

         <div class="max-w-sm">
    <a href="{{ route('collections.index') }}" class="block hover:scale-105 transition-transform duration-200">
        <div class="flex items-center justify-between p-6 rounded-2xl shadow
                    bg-white dark:bg-gray-900
                    border border-gray-200 dark:border-gray-700
                    hover:border-emerald-400 dark:hover:border-emerald-600
                    transition-colors duration-300">

            <!-- Content -->
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    Ukusanyaji Unatarajiwa Leo
                </p>

                <h2 class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">
                     {{ number_format($expectedCollectionToday, 2) }}
                </h2>

                {{-- <p class="mt-1 text-xs text-blue-600 dark:text-blue-400">
                    Bonyeza kwa mikusanyo
                </p> --}}
            </div>

            <!-- Icon -->
            <div class="flex items-center justify-center w-14 h-14 rounded-xl
                        bg-emerald-100 dark:bg-emerald-900/40">
                <!-- Money / Collection Icon -->
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-7 h-7 text-emerald-600 dark:text-emerald-300"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2
                             3 .895 3 2-1.343 2-3 2
                             m0-8V6
                             m0 12v-2
                             M5 10h2
                             M17 10h2
                             M5 14h2
                             M17 14h2" />
                </svg>
            </div>

        </div>
    </a>
</div>

          <div class="max-w-sm">
    <a href="{{ route('payments.report') }}" class="block hover:scale-105 transition-transform duration-200">
        <div class="p-6 rounded-2xl shadow-lg
                bg-gradient-to-r from-emerald-100 to-teal-100
                dark:from-emerald-900 dark:to-teal-900
                hover:from-emerald-200 hover:to-teal-200
                dark:hover:from-emerald-800 dark:hover:to-teal-800
                text-gray-900 dark:text-white
                transition-all duration-300">

            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-70">Ukusanyaji Uliokusanywa Leo</p>
                    <h2 class="text-3xl font-bold">{{ number_format($collectionCollectedToday, 2) }}</h2>
                    <p class="text-xs mt-1 opacity-80">Tazama ripoti ya malipo</p>
                </div>

                <div class="w-14 h-14 flex items-center justify-center rounded-full
                            bg-emerald-200 dark:bg-emerald-800">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-7 h-7 text-emerald-700 dark:text-emerald-200"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2
                                 3 .895 3 2-1.343 2-3 2
                                 m0-8V6
                                 m0 12v-2" />
                    </svg>
                </div>
            </div>
        </div>
    </a>
</div>

<div class="max-w-sm">
    <a href="{{ route('payments.report') }}" class="block hover:scale-105 transition-transform duration-200">
        <div class="flex items-center justify-between p-6 rounded-2xl shadow
                    bg-white dark:bg-gray-900
                    border border-gray-200 dark:border-gray-700
                    hover:border-indigo-400 dark:hover:border-indigo-600">

            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    Makusanyo Iliyokusanywa Wiki Hii
                </p>
                <h2 class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">
                    {{ number_format($collectionsThisWeek) }}
                </h2>
                <p class="mt-1 text-xs text-indigo-600 dark:text-indigo-400">
                    Tazama ripoti ya malipo
                </p>
            </div>

            <div class="flex items-center justify-center w-14 h-14 rounded-xl
                        bg-indigo-100 dark:bg-indigo-900/40">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-indigo-600 dark:text-indigo-300"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2
                             3 .895 3 2-1.343 2-3 2" />
                </svg>
            </div>
        </div>
    </a>
</div>


<div class="max-w-sm">
    <a href="{{ route('payments.report') }}" class="block hover:scale-105 transition-transform duration-200">
        <div class="flex items-center justify-between p-6 rounded-2xl shadow
                    bg-white dark:bg-gray-900
                    border border-gray-200 dark:border-gray-700
                    hover:border-green-400 dark:hover:border-green-600">

            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    Malipo Yaliyokusanywa Mwezi Huu
                </p>
                <h2 class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">
                     {{ number_format($paymentsCollectedThisMonth, 2) }}
                </h2>
                <p class="mt-1 text-xs text-green-600 dark:text-green-400">
                    Tazama ripoti ya malipo
                </p>
            </div>

            <div class="flex items-center justify-center w-14 h-14 rounded-xl
                        bg-green-100 dark:bg-green-900/40">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-green-600 dark:text-green-300"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 10h18M7 15h10" />
                </svg>
            </div>
        </div>
    </a>
</div>


<div class="max-w-sm">
    <a href="{{ route('unpaid.report') }}" class="block hover:scale-105 transition-transform duration-200">
        <div class="flex items-center justify-between p-6 rounded-2xl shadow
                    bg-white dark:bg-gray-900
                    border border-gray-200 dark:border-gray-700
                    hover:border-red-400 dark:hover:border-red-600">

            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                Malipo Yanayohitajika Mwezi Huu
                </p>
                <h2 class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">
                    {{ number_format($paymentsNeededThisMonth) }}
                </h2>
                <p class="mt-1 text-xs text-red-600 dark:text-red-400">
                    Tazama ripoti ya hajalipa
                </p>
            </div>

            <div class="flex items-center justify-center w-14 h-14 rounded-xl
                        bg-red-100 dark:bg-red-900/40">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-red-600 dark:text-red-300"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01M5 19h14" />
                </svg>
            </div>
        </div>
    </a>
</div>


<div class="max-w-sm">
    <a href="{{ route('collections.index') }}" class="block hover:scale-105 transition-transform duration-200">
        <div class="flex items-center justify-between p-6 rounded-2xl shadow
                    bg-white dark:bg-gray-900
                    border border-gray-200 dark:border-gray-700
                    hover:border-emerald-400 dark:hover:border-emerald-600">

            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                 Malipo Yanayohitajika Wiki Hii
                </p>
                <h2 class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">
                     {{ number_format($paymentsNeededThisWeek, 2) }}
                </h2>
                <p class="mt-1 text-xs text-emerald-600 dark:text-emerald-400">
                    Tazama makusanyo
                </p>
            </div>

            <div class="flex items-center justify-center w-14 h-14 rounded-xl
                        bg-emerald-100 dark:bg-emerald-900/40">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-emerald-600 dark:text-emerald-300"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 6v12m6-6H6" />
                </svg>
            </div>
        </div>
    </a>
</div>


<div class="max-w-sm">
    <a href="{{ route('expenses.index') }}" class="block hover:scale-105 transition-transform duration-200">
        <div class="flex items-center justify-between p-6 rounded-2xl shadow
                    bg-white dark:bg-gray-900
                    border border-gray-200 dark:border-gray-700
                    hover:border-red-400 dark:hover:border-red-600">

            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    Matumizi Leo
                </p>
                <h2 class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">
                     {{ number_format($expensesToday, 2) }}
                </h2>
                <p class="mt-1 text-xs text-red-600 dark:text-red-400">
                    Tazama orodha ya matumizi
                </p>
            </div>

            <div class="flex items-center justify-center w-14 h-14 rounded-xl
                        bg-red-100 dark:bg-red-900/40">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-red-600 dark:text-red-300"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
        </div>
    </a>
</div>

<div class="max-w-sm">
    <a href="{{ route('expenses.index') }}" class="block hover:scale-105 transition-transform duration-200">
        <div class="flex items-center justify-between p-6 rounded-2xl shadow
                    bg-white dark:bg-gray-900
                    border border-gray-200 dark:border-gray-700
                    hover:border-purple-400 dark:hover:border-purple-600">

            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    Matumizi Mwezi Huu
                </p>
                <h2 class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">
                     {{ number_format($expensesThisMonth, 2) }}
                </h2>
                <p class="mt-1 text-xs text-purple-600 dark:text-purple-400">
                    Tazama orodha ya matumizi
                </p>
            </div>

            <div class="flex items-center justify-center w-14 h-14 rounded-xl
                        bg-purple-100 dark:bg-purple-900/40">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-purple-600 dark:text-purple-300"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
            </div>
        </div>
    </a>
</div>


<div class="max-w-sm">
    <a href="{{ route('penalties.payments-list') }}" class="block hover:scale-105 transition-transform duration-200">
        <div class="flex items-center justify-between p-6 rounded-2xl shadow
                    bg-white dark:bg-gray-900
                    border border-gray-200 dark:border-gray-700
                    hover:border-orange-400 dark:hover:border-orange-600">

            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    Faini Zilizolipwa Leo
                </p>
                <h2 class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">
                     {{ number_format($penaltiesPaidToday, 2) }}
                </h2>
                <p class="mt-1 text-xs text-orange-600 dark:text-orange-400">
                    Bonyeza kuona maelezo
                </p>
            </div>

            <div class="flex items-center justify-center w-14 h-14 rounded-xl
                        bg-orange-100 dark:bg-orange-900/40">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-orange-600 dark:text-orange-300"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
        </div>
    </a>
</div>


{{-- <div class="max-w-sm">
    <div class="flex items-center justify-between p-6 rounded-2xl shadow
                bg-white dark:bg-gray-900
                border border-gray-200 dark:border-gray-700">

        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                Faini Zilizokusanywa Leo
            </p>
            <h2 class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">
                18
            </h2>
            
        </div>

        <div class="flex items-center justify-center w-14 h-14 rounded-xl
                    bg-blue-100 dark:bg-blue-900/40">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-blue-600 dark:text-blue-300"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M18 9a3 3 0 11-6 0 3 3 0 016 0z
                         M13 15h5a4 4 0 014 4v1H2v-1a4 4 0 014-4h5" />
            </svg>
        </div>
    </div>
</div> --}}


{{-- <div class="max-w-sm">
    <div class="flex items-center justify-between p-6 rounded-2xl shadow
                bg-white dark:bg-gray-900
                border border-gray-200 dark:border-gray-700">

        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                Faini Zilizokusanywa Mwezi Huu
            </p>
            <h2 class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">
                27
            </h2>
          
        </div>

        <div class="flex items-center justify-center w-14 h-14 rounded-xl
                    bg-green-100 dark:bg-green-900/40">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-green-600 dark:text-green-300"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4
                         M12 22a10 10 0 100-20 10 10 0 000 20z" />
            </svg>
        </div>
    </div>
</div>  --}}

        </div>
      
    </div>
</x-layouts.app>
