<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    @include('partials.head')
</head>
<body class="min-h-screen antialiased">

    <!-- Full-page background image -->
    <div class="absolute inset-0 z-0">
        <img 
            src="{{ asset('images/back1.jpg') }}" 
            alt="Background" 
            class="w-full h-full object-cover"
        />
        <!-- Optional: dark overlay for readability -->
        <div class="absolute inset-0 bg-black/40"></div>
    </div>

    <!-- Page content -->
    <div class="relative z-10 flex min-h-screen flex-col items-center justify-center gap-6 p-6 md:p-10">
        <div class="flex w-full max-w-sm flex-col gap-2">
            <!-- Logo above the form -->
            <!-- <a href="{{ route('home') }}" class="flex flex-col items-center gap-2 font-medium" wire:navigate>
                <img src="{{ asset('images/logo.jpeg') }}" alt="Logo" class="h-16 w-auto rounded-full shadow-md" />
                <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
            </a> -->
            <div class="flex flex-col gap-6">
                {{ $slot }}
            </div>
        </div>
    </div>

    @fluxScripts
</body>
</html>
