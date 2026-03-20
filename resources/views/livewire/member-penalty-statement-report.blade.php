<div class="w-full space-y-5">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4">
        <div class="flex flex-col sm:flex-row gap-3 items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tafuta Mwanachama</label>
                <div class="relative">
                    <input
                        wire:model.live.debounce.300ms="search"
                        type="text"
                        placeholder="Andika jina au namba ya simu..."
                        class="w-full px-4 py-2 pr-10 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500 dark:bg-gray-700 dark:text-white"
                        autocomplete="off"
                    >
                    <div wire:loading wire:target="search" class="absolute right-3 top-2.5">
                        <svg class="animate-spin h-4 w-4 text-cyan-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        @if(mb_strlen(trim($search)) >= 2)
            @if($searchResults->count() > 0)
                <div class="mt-3 divide-y divide-gray-100 dark:divide-gray-700 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden max-h-64 overflow-y-auto">
                    @foreach($searchResults as $member)
                        <button
                            wire:click="selectMember({{ $member->id }})"
                            class="w-full text-left px-4 py-2 hover:bg-cyan-50 dark:hover:bg-cyan-900/30 transition flex items-center justify-between"
                        >
                            <span class="font-medium text-gray-800 dark:text-gray-100">{{ $member->name }}</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $member->phone }}</span>
                        </button>
                    @endforeach
                </div>
            @else
                <div class="mt-3 p-3 rounded-lg bg-yellow-50 dark:bg-yellow-900/20 text-yellow-700 dark:text-yellow-300 text-sm">
                    Hakuna mwanachama aliyepatikana kwa "<strong>{{ $search }}</strong>".
                </div>
            @endif
        @endif
    </div>

    @if($selectedMember)
        <div wire:loading.remove wire:target="selectMember" class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4">
            <h2 class="text-sm md:text-base font-semibold text-cyan-700 dark:text-cyan-300 mb-3">
                Penalty Statement ya: <span class="text-gray-900 dark:text-white uppercase">{{ $selectedMember->name }}</span>
            </h2>

            <livewire:member-penalty-statement
                :member="$selectedMember"
                :key="'mps-' . $selectedMember->id"
            />
        </div>
    @endif
</div>
