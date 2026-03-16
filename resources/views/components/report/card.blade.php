@props([
    'title' => '',
    'value' => '',
    'icon' => null,
    'description' => null,
    'color' => 'gray'
])

@php
    $colors = [
        'indigo' => ['bg' => 'bg-indigo-100 dark:bg-indigo-900/40', 'text' => 'text-indigo-600 dark:text-indigo-400', 'border' => 'border-indigo-500'],
        'teal' => ['bg' => 'bg-teal-100 dark:bg-teal-900/40', 'text' => 'text-teal-600 dark:text-teal-400', 'border' => 'border-teal-500'],
        'blue' => ['bg' => 'bg-blue-100 dark:bg-blue-900/40', 'text' => 'text-blue-600 dark:text-blue-400', 'border' => 'border-blue-500'],
        'green' => ['bg' => 'bg-green-100 dark:bg-green-900/40', 'text' => 'text-green-600 dark:text-green-400', 'border' => 'border-green-500'],
        'orange' => ['bg' => 'bg-orange-100 dark:bg-orange-900/40', 'text' => 'text-orange-600 dark:text-orange-400', 'border' => 'border-orange-500'],
        'red' => ['bg' => 'bg-red-100 dark:bg-red-900/40', 'text' => 'text-red-600 dark:text-red-400', 'border' => 'border-red-500'],
        'gradient' => ['bg' => 'bg-white/20', 'text' => 'text-white', 'border' => ''],
        'gray' => ['bg' => 'bg-gray-100 dark:bg-gray-800', 'text' => 'text-gray-900 dark:text-white', 'border' => 'border-gray-300']
    ];

    $bgColor = $colors[$color]['bg'] ?? $colors['gray']['bg'];
    $textColor = $colors[$color]['text'] ?? $colors['gray']['text'];
    $borderColor = $colors[$color]['border'] ?? $colors['gray']['border'];
@endphp

<div class="p-6 rounded-lg shadow-md {{ $borderColor ? "border-l-4 $borderColor" : '' }} {{ $color === 'gradient' ? 'bg-gradient-to-br from-cyan-500 to-blue-600 text-white shadow-lg' : $bgColor }}">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-sm font-medium opacity-90">{{ $title }}</h3>
            <p class="text-2xl font-bold mt-2 {{ $textColor }}">
                {{ $value }}
            </p>
            @isset($description)
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $description }}</p>
            @endisset
        </div>
        @isset($icon)
            <div class="w-12 h-12 {{ $bgColor }} rounded-full flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 {{ $textColor }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}" />
                </svg>
            </div>
        @endisset
    </div>
</div>