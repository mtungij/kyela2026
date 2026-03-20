<x-layouts.app.sidebar :title="$title ?? null">
    <flux:main class="w-full max-w-none px-0 sm:px-0 lg:px-0">
        {{ $slot }}
    </flux:main>
</x-layouts.app.sidebar>
