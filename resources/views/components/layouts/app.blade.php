<x-layouts.app.sidebar :title="$title ?? null">
    <flux:main >
        <div class="w-full">
  {{ $slot }}
        </div>
      
    </flux:main>
</x-layouts.app.sidebar>
