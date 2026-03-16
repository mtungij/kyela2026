<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md border-l-4 {{ $borderColor }}">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $title }}</h3>
            <p class="text-2xl font-bold {{ $textColor }} mt-2">
                {{ $value }}
            </p>
            @if($extra)
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $extra }}</p>
            @endif
        </div>
        @if($icon)
        <div class="w-12 h-12 {{ $iconBg }} rounded-full flex items-center justify-center">
            {!! $icon !!}
        </div>
        @endif
    </div>
</div>