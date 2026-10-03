<x-layouts.app :title="__('Anzisha Mzunguko Mpya')">
    @php
        $payTypeLabel = $payType === 'mchango_mdogo' ? 'Mchango Mdogo' : 'Mchango Mkubwa';
    @endphp

    <div class="mx-auto w-full max-w-5xl px-4 py-8 sm:px-6">
        <div class="mb-6 border-b border-zinc-200 pb-5 dark:border-zinc-700">
            <p class="text-sm font-semibold uppercase text-cyan-700 dark:text-cyan-400">{{ $payTypeLabel }}</p>
            <h1 class="mt-1 text-2xl font-semibold text-zinc-900 dark:text-white">Anzisha Mzunguko Mpya</h1>
        </div>

        @if($errors->any())
            <div class="mb-5 border-l-4 border-red-600 bg-red-50 p-4 text-sm text-red-800 dark:bg-red-950 dark:text-red-200">
                <ul class="list-inside list-disc">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($activeCycle)
            <div class="mb-6 border-l-4 border-amber-500 bg-amber-50 p-4 text-sm text-amber-950 dark:bg-amber-950 dark:text-amber-100" role="alert">
                Mzunguko huu bado unaendelea hadi <strong>{{ $activeCycle->end_date->format('d-m-Y') }}</strong>. Huwezi kuanzisha mzunguko mpya kabla ya tarehe hiyo.
            </div>
        @endif

        <div class="mb-6 grid gap-4 border-b border-zinc-200 pb-6 sm:grid-cols-3 dark:border-zinc-700">
            <div>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Wanachama watakaoendelea</p>
                <p class="mt-1 text-xl font-semibold text-zinc-900 dark:text-white">{{ $members->count() }}</p>
            </div>
            <div>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Madeni yaliyopo kabla ya mzunguko</p>
                <p class="mt-1 text-xl font-semibold text-red-700 dark:text-red-400">TSh {{ number_format($outstandingDebt, 0) }}</p>
            </div>
            <div>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Mzunguko uliosajiliwa</p>
                <p class="mt-1 font-medium text-zinc-900 dark:text-white">
                    @if($currentCycle)
                        {{ $currentCycle->start_date->format('d-m-Y') }} hadi {{ $currentCycle->end_date->format('d-m-Y') }}
                    @else
                        Hakuna mzunguko uliosajiliwa bado
                    @endif
                </p>
            </div>
        </div>

        <form method="POST" action="{{ route('game-cycles.store') }}" class="space-y-6">
            @csrf
            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label for="start_date" class="mb-2 block text-sm font-medium text-zinc-800 dark:text-zinc-200">Tarehe ya kuanza</label>
                    <input id="start_date" name="start_date" type="date" required value="{{ old('start_date', today()->toDateString()) }}" class="w-full rounded border border-zinc-300 bg-white px-3 py-2 text-zinc-900 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white">
                </div>
                <div>
                    <label for="end_date" class="mb-2 block text-sm font-medium text-zinc-800 dark:text-zinc-200">Tarehe ya kumaliza</label>
                    <input id="end_date" name="end_date" type="date" required value="{{ old('end_date') }}" class="w-full rounded border border-zinc-300 bg-white px-3 py-2 text-zinc-900 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white">
                </div>
            </div>

            <div class="border-y border-zinc-200 py-5 dark:border-zinc-700">
                <label for="contribution_amount" class="mb-2 block text-sm font-semibold text-zinc-900 dark:text-white">Kiasi kwa kila malipo (TSh) kwa wanachama wote wa {{ strtolower($payTypeLabel) }}</label>
                <p class="mb-3 text-sm text-zinc-500 dark:text-zinc-400">Kiasi hiki kitatumika kwa kila mwanachama wa mchezo huu. Jumla ya kila mwanachama itazidishwa kwa idadi yake ya malipo.</p>
                <input id="contribution_amount" name="contribution_amount" type="number" min="0.01" max="999999999" step="0.01" required value="{{ old('contribution_amount', $payType === 'mchango_mdogo' ? 5000 : 10000) }}" class="w-full max-w-sm rounded border border-zinc-300 bg-white px-3 py-2 text-zinc-900 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white">
                @error('contribution_amount')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="border-l-4 border-amber-500 bg-amber-50 p-4 text-sm text-amber-950 dark:bg-amber-950 dark:text-amber-100">
                Mzunguko mpya utafungua salio jipya kwa wanachama {{ strtolower($payTypeLabel) }}. Malipo na madeni ya zamani yataendelea kuhifadhiwa kwenye collections zake na hayatahamishiwa kwenye mzunguko huu.
            </div>

            <label class="flex items-start gap-3 text-sm text-zinc-700 dark:text-zinc-300">
                <input name="confirm" type="checkbox" value="1" required class="mt-1 rounded border-zinc-400 text-cyan-700 focus:ring-cyan-600">
                <span>Nimehakiki tarehe na ninaelewa kuwa mzunguko huu utafungua salio jipya bila kufuta deni la zamani.</span>
            </label>

            <div class="flex flex-wrap gap-3 border-t border-zinc-200 pt-5 dark:border-zinc-700">
                <button type="submit" @disabled($activeCycle) class="rounded bg-cyan-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-cyan-800 disabled:cursor-not-allowed disabled:opacity-50">Thibitisha na Anzisha</button>
                <a href="{{ $payType === 'mchango_mdogo' ? route('members.index.mdogo') : route('members.index.mkubwa') }}" class="rounded border border-zinc-300 px-5 py-2.5 text-sm font-medium text-zinc-700 hover:bg-zinc-100 dark:border-zinc-600 dark:text-zinc-200 dark:hover:bg-zinc-800">Ghairi</a>
            </div>
        </form>
    </div>
</x-layouts.app>