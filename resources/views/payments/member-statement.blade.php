<x-layouts.app :title="__('Taarifa ya Malipo ya Mwanachama')">
    <div class="w-full px-4 lg:px-8 py-6 space-y-6">
        <div class="bg-gray-100 dark:bg-gray-900">
            <div class="w-full bg-cyan-600 text-white rounded-lg">
                <div class="p-4 flex items-center justify-between">
                    <h1 class="text-lg font-semibold tracking-widest uppercase">
                        Taarifa ya Malipo - {{ $member->name }}
                    </h1>
                    <a
                        href="{{ route('payments.report') }}"
                        class="px-3 py-2 text-sm font-medium bg-white text-cyan-700 rounded-lg hover:bg-cyan-50"
                    >
                        Rudi Report
                    </a>
                </div>
            </div>
        </div>

        <livewire:member-payment-statement :member="$member" />
    </div>
</x-layouts.app>
