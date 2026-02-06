<x-layouts.auth>
  <div
    class="flex flex-col gap-6 max-w-md mx-auto mt-4 p-6
           bg-white dark:bg-gray-900
           rounded-lg shadow-md
           border border-gray-300 dark:border-gray-700
           overflow-hidden"
>


        <div class="flex justify-center mb-4">
            <img
                src="{{ asset('images/logo.jpeg') }}"
                alt="Logo"
                class="h-36 w-auto rounded-full"
            >
        </div>

        <!-- Session Status -->
        <x-auth-session-status
            class="text-center text-gray-700 dark:text-gray-300"
            :status="session('status')"
        />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('Email address')"
                :value="old('email')"
                type="email"
                required
                autofocus
                autocomplete="email"
                placeholder="email@example.com"
            />

            <div>
                <label for="pay_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Aina Ya Mchango
                </label>
                <select
                    id="pay_type"
                    name="pay_type"
                    required
                    class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900
                           focus:border-cyan-500 focus:ring-cyan-500
                           dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                >
                    <option value="">-- Chagua Aina Ya Mchango --</option>
                    <option value="mchango_mdogo" {{ old('pay_type') == 'mchango_mdogo' ? 'selected' : '' }}>
                        Mchango Mdogo (5000)
                    </option>
                    <option value="mchango_mkubwa" {{ old('pay_type') == 'mchango_mkubwa' ? 'selected' : '' }}>
                        Mchango Mkubwa (10000)
                    </option>
                </select>
            </div>

            <!-- Password -->
            <div class="relative">
                <flux:input
                    name="password"
                    :label="__('Password')"
                    type="password"
                    required
                    autocomplete="current-password"
                    :placeholder="__('Password')"
                    viewable
                />

                @if (Route::has('password.request'))
                    <flux:link
                        class="absolute top-0 end-0 text-sm
                               text-gray-600 dark:text-gray-400
                               hover:underline"
                        :href="route('password.request')"
                        wire:navigate
                    >
                        {{ __('') }}
                    </flux:link>
                @endif
            </div>

            <!-- Msaada Section -->
            <p class="text-center text-sm text-gray-600 dark:text-gray-400 mt-4">
                Kwa changamoto ama msaada zaidi, wasiliana na injinia wa mfumo:
                <a
                    href="https://wa.me/255747384847"
                    target="_blank"
                    class="font-semibold
                           text-[#0B3D2E] dark:text-emerald-400
                           hover:underline"
                >
                    0747384847
                </a>
            </p>

            <div class="flex items-center justify-end">
                <flux:button
                    variant="primary"
                    type="submit"
                    class="w-full
                           bg-[#0B3D2E] hover:bg-[#093327]
                           dark:bg-emerald-600 dark:hover:bg-emerald-700
                           text-white transition-colors"
                    data-test="login-button"
                >
                    {{ __('Log in') }}
                </flux:button>
            </div>
        </form>

    </div>
</x-layouts.auth>
