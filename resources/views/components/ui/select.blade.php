@props([
    'placeholder' => 'Select option...',
    'icon' => null,
    'searchable' => false,
    'noResultsText' => 'Hakuna matokeo.',
])

@if(!$searchable)
    <div class="relative">
        @if($icon)
            <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-400 dark:text-gray-500">
                <x-dynamic-component :component="'flux::icon.' . $icon" class="size-4" />
            </span>
        @endif

        <select
            {{ $attributes->merge([
                'class' => 'w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500 dark:bg-gray-700 dark:text-white' . ($icon ? ' pl-10' : ''),
            ]) }}
        >
            <option value="">{{ $placeholder }}</option>
            {{ $slot }}
        </select>
    </div>
@else
    @php
        $searchableInputClass = 'w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500 dark:bg-gray-700 dark:text-white' . ($icon ? ' pl-10 pr-10' : ' pr-10');
        $wireModelAttributes = $attributes->whereStartsWith('wire:model');
        $searchableAttributes = $attributes->except(array_keys($wireModelAttributes->getAttributes()));
    @endphp

    <div
        class="relative"
        x-data="{
            open: false,
            query: '',
            selectedValue: '',
            activeIndex: -1,
            options: [],
            init() {
                this.options = Array.from(this.$refs.nativeSelect.options)
                    .filter(option => option.value !== '')
                    .map(option => ({ value: option.value, label: option.text }));

                this.syncFromNative();
            },
            get filteredOptions() {
                if (!this.query) {
                    return this.options;
                }

                const term = this.query.toLowerCase();
                return this.options.filter(option => option.label.toLowerCase().includes(term));
            },
            syncFromNative() {
                this.selectedValue = this.$refs.nativeSelect.value;
                const selected = this.options.find(option => option.value == this.selectedValue);
                this.query = selected ? selected.label : '';
                this.activeIndex = -1;
            },
            choose(option) {
                this.selectedValue = option.value;
                this.query = option.label;
                this.$refs.nativeSelect.value = option.value;
                this.$refs.nativeSelect.dispatchEvent(new Event('change', { bubbles: true }));
                this.open = false;
                this.activeIndex = -1;
            },
            openDropdown() {
                this.open = true;

                if (this.filteredOptions.length === 0) {
                    this.activeIndex = -1;
                    return;
                }

                if (this.activeIndex < 0 || this.activeIndex >= this.filteredOptions.length) {
                    this.activeIndex = 0;
                }

                this.$nextTick(() => this.scrollActiveIntoView());
            },
            onInput() {
                this.open = true;
                this.activeIndex = this.filteredOptions.length > 0 ? 0 : -1;
                this.$nextTick(() => this.scrollActiveIntoView());
            },
            moveActive(step) {
                if (!this.open) {
                    this.openDropdown();
                    return;
                }

                const total = this.filteredOptions.length;
                if (total === 0) {
                    this.activeIndex = -1;
                    return;
                }

                if (this.activeIndex === -1) {
                    this.activeIndex = step > 0 ? 0 : total - 1;
                } else {
                    this.activeIndex = (this.activeIndex + step + total) % total;
                }

                this.$nextTick(() => this.scrollActiveIntoView());
            },
            selectActive() {
                if (!this.open) {
                    this.openDropdown();
                    return;
                }

                const option = this.filteredOptions[this.activeIndex];
                if (!option) {
                    return;
                }

                this.choose(option);
            },
            scrollActiveIntoView() {
                if (!this.$refs.optionsList || this.activeIndex < 0) {
                    return;
                }

                const activeElement = this.$refs.optionsList.querySelector('[data-option-index=' + this.activeIndex + ']');
                if (activeElement) {
                    activeElement.scrollIntoView({ block: 'nearest' });
                }
            },
            clearIfInvalid() {
                const selected = this.options.find(option => option.value == this.selectedValue);
                if (!selected) {
                    this.selectedValue = '';
                    this.$refs.nativeSelect.value = '';
                    this.$refs.nativeSelect.dispatchEvent(new Event('change', { bubbles: true }));
                }
            }
        }"
    >
        <select
            x-ref="nativeSelect"
            class="hidden"
            {{ $wireModelAttributes }}
            @change="syncFromNative()"
        >
            <option value="">{{ $placeholder }}</option>
            {{ $slot }}
        </select>

        @if($icon)
            <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-400 dark:text-gray-500 z-10">
                <x-dynamic-component :component="'flux::icon.' . $icon" class="size-4" />
            </span>
        @endif

        <input
            type="text"
            x-model="query"
            placeholder="{{ $placeholder }}"
            autocomplete="off"
            {{ $searchableAttributes->merge(['class' => $searchableInputClass]) }}
            @focus="$event.target.select(); openDropdown()"
            @input="onInput()"
            @keydown.escape.prevent="open = false"
            @keydown.arrow-down.prevent="moveActive(1)"
            @keydown.arrow-up.prevent="moveActive(-1)"
            @keydown.enter.prevent="selectActive()"
            @keydown.tab="open = false"
            @blur="setTimeout(() => { open = false; clearIfInvalid(); syncFromNative(); }, 120)"
        />

        <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400 dark:text-gray-500">
            <x-dynamic-component :component="'flux::icon.chevrons-up-down'" class="size-4" />
        </span>

        <div
            x-show="open"
            x-transition
            x-ref="optionsList"
            class="absolute z-40 mt-1 w-full max-h-64 overflow-y-auto rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-lg"
            style="display: none;"
        >
            @if(filled($noResultsText))
                <template x-if="filteredOptions.length === 0">
                    <div class="px-3 py-2 text-sm text-gray-500 dark:text-gray-400">{{ $noResultsText }}</div>
                </template>
            @endif

            <template x-for="(option, index) in filteredOptions" :key="option.value">
                <button
                    type="button"
                    :data-option-index="index"
                    :class="activeIndex === index ? 'bg-cyan-50 dark:bg-cyan-900/30' : ''"
                    class="w-full text-left px-3 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-cyan-50 dark:hover:bg-cyan-900/30"
                    @mouseenter="activeIndex = index"
                    @mousedown.prevent="choose(option)"
                >
                    <span x-text="option.label"></span>
                </button>
            </template>
        </div>
    </div>
@endif
