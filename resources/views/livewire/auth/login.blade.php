
<x-layouts.auth>
    <div class="flex flex-col gap-6">

          <div class="flex justify-center mb-4 ">
            <img src="{{ asset('images/logo.jpeg') }}" alt="Logo" class="h-36 w-auto rounded-full">
        </div>

        <x-auth-header :title="__('Ingia kwenye akaunti yako')" :description="__('Ingiza barua pepe na nenosiri lako hapa ili kuingia')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

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
                    <flux:link class="absolute top-0 text-sm end-0" :href="route('password.request')" wire:navigate>
                        {{ __('Forgot your password?') }}
                    </flux:link>
                @endif
            </div>

            

          <div class="flex items-center justify-end">
    <flux:button
        variant="primary"
        type="submit"
        class="w-full bg-[#0B3D2E] hover:bg-[#093327] text-white transition-colors"
        data-test="login-button"
    >
        {{ __('Log in') }}
    </flux:button>
</div>

        </form>

      
    </div>
</x-layouts.auth>
