<div class="w-full space-y-5">

    {{-- Search Bar --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4">
        <div class="flex flex-col sm:flex-row gap-3 items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Tafuta Mwanachama
                </label>
                <x-ui.select
                    placeholder="Tafuta mwanachama..."
                    icon="user"
                    wire:model.live="selectedMemberId"
                    searchable
                    class="w-full"
                >
                    @foreach($memberOptions as $memberOption)
                        <option value="{{ $memberOption['value'] }}">{{ $memberOption['label'] }}</option>
                    @endforeach
                </x-ui.select>
            </div>
        </div>
    </div>

    {{-- Skeleton placeholder while loading --}}
    <div wire:loading wire:target="selectedMemberId" class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 space-y-4 animate-pulse">
        {{-- Header skeleton --}}
        <div class="flex items-center gap-3">
            <div class="h-4 w-40 bg-gray-200 dark:bg-gray-700 rounded"></div>
            <div class="h-3 w-24 bg-gray-100 dark:bg-gray-600 rounded"></div>
        </div>
        {{-- Badge skeleton --}}
        <div class="h-10 w-full bg-gray-100 dark:bg-gray-700 rounded-lg"></div>
        {{-- Summary cards skeleton --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <div class="h-16 bg-gray-100 dark:bg-gray-700 rounded-lg"></div>
            <div class="h-16 bg-gray-100 dark:bg-gray-700 rounded-lg"></div>
            <div class="h-16 bg-gray-100 dark:bg-gray-700 rounded-lg"></div>
            <div class="h-16 bg-gray-100 dark:bg-gray-700 rounded-lg"></div>
        </div>
        {{-- Table skeleton --}}
        <div class="space-y-2">
            <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded"></div>
            @for($i = 0; $i < 6; $i++)
                <div class="h-7 bg-gray-100 dark:bg-gray-600 rounded"></div>
            @endfor
        </div>
    </div>

    {{-- Member Statement --}}
    @if($selectedMember)
        <div wire:loading.remove wire:target="selectedMemberId" class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4">
            <div class="flex items-center justify-between gap-3 mb-3">
                <h2 class="text-sm md:text-base font-semibold text-cyan-700 dark:text-cyan-300">
                    Statement ya: <span class="text-gray-900 dark:text-white uppercase">{{ $selectedMember->name }}</span>
                </h2>
                <a
                    href="{{ route('reports.member-statement.download-pdf', ['member' => $selectedMember->id]) }}"
                    class="inline-flex items-center px-3 py-2 text-xs md:text-sm font-semibold rounded-lg bg-red-600 hover:bg-red-700 text-white"
                >
                    Download PDF
                </a>
            </div>

         

            <livewire:member-payment-statement
                :member="$selectedMember"
                :key="'msr-' . $selectedMember->id"
            />
        </div>
    @endif

</div>
