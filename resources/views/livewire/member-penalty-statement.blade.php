<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700 p-6 space-y-5">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="p-4 rounded-lg bg-green-50 dark:bg-green-900/20">
            <div class="text-sm text-green-700 dark:text-green-300">Faini Zilizolipwa</div>
            <div class="text-xl font-bold text-green-700 dark:text-green-300">{{ $this->penaltySummary['paid_count'] }}</div>
        </div>
        <div class="p-4 rounded-lg bg-cyan-50 dark:bg-cyan-900/20">
            <div class="text-sm text-cyan-700 dark:text-cyan-300">Jumla Faini Iliyolipwa</div>
            <div class="text-xl font-bold text-cyan-700 dark:text-cyan-300">{{ number_format($this->penaltySummary['total_paid_amount'], 0) }} TSh</div>
        </div>
        <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-900/30">
            <div class="text-sm text-gray-600 dark:text-gray-300">Faini kwa Siku</div>
            <div class="text-xl font-bold text-gray-900 dark:text-white">{{ number_format($this->penaltySummary['penalty_per_day'], 0) }} TSh</div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
            <thead class="bg-cyan-100 dark:bg-cyan-900 text-cyan-800 dark:text-cyan-200 font-semibold">
                <tr>
                    <th class="px-4 py-3">Tarehe ya Funga Hesabu</th>
                    <th class="px-4 py-3 text-center">Hali ya Faini</th>
                    <th class="px-4 py-3 text-center">Malipo ya Faini</th>
                    <th class="px-4 py-3 text-right">Kiasi Kilicholipwa</th>
                    <th class="px-4 py-3 text-right">Kiasi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($this->penaltyRows as $row)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-4 py-3">{{ $row['date'] }}</td>
                        <td class="px-4 py-3 text-center">
                            @if($row['charged'])
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300">
                                    Faini Imewekwa
                                </span>
                            @else
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300">
                                    Hakuna Faini
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($row['charged'])
                                @if($row['paid'])
                                    <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300">
                                        Imelipwa
                                    </span>
                                @elseif($row['forgiven'])
                                    <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">
                                        Imesamehewa
                                    </span>
                                @else
                                    <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-700 dark:bg-orange-900/40 dark:text-orange-300">
                                        Haijalipwa
                                    </span>
                                @endif
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right font-semibold text-green-700 dark:text-green-300">
                            {{ $row['paid'] ? number_format($row['paid_amount'], 0) : '0' }}
                        </td>
                        <td class="px-4 py-3 text-right font-semibold {{ $row['charged'] ? 'text-red-600 dark:text-red-400' : 'text-gray-500 dark:text-gray-400' }}">
                            {{ $row['charged'] ? number_format($row['penalty_amount'], 0) : '0' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                            Hakuna faini iliyolipwa kwa mwanachama huyu.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
