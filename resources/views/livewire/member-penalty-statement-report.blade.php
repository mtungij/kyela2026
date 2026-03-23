<div class="w-full space-y-5">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4">
        <div class="flex flex-col sm:flex-row gap-3 items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tafuta Mwanachama</label>
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

    @if($selectedMember)
        <div wire:loading.remove wire:target="selectedMemberId" class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4">
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
