    
    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
<body class=" bg-white dark:bg-zinc-800 ">
    <flux:header container class="bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700">
        <flux:sidebar.toggle class="" icon="bars-2" />
        <flux:navbar class="-mb-px ">


            {{-- <flux:separator vertical variant="subtle" class="my-2"/> --}}

        
        </flux:navbar>

        <flux:spacer />



        <flux:dropdown position="top" align="start">
            <flux:profile  />

            <flux:menu>
                <flux:menu.radio.group>
                   
                    <flux:menu.radio>Truly Delta</flux:menu.radio>

                    
                </flux:menu.radio.group>
            <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                <flux:menu.separator />

                 <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>


    
    <flux:sidebar sticky collapsible="mobile" class=" bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700">
        <flux:sidebar.header>
            <flux:sidebar.brand
                href="#"
                logo="{{ asset('images/logo.jpeg') }}"
                logo:dark="{{ asset('images/logo-dark.jpeg') }}"
                name="KWS"
            />

            <flux:sidebar.collapse class="in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:-mr-2" />
        </flux:sidebar.header>

        <flux:sidebar.nav>
            <flux:sidebar.item icon="home" href="{{ route('dashboard') }}" current>Home</flux:sidebar.item>
                <flux:sidebar.group   expandable
               :expanded="false" heading="Members"  class="grid">
                <flux:sidebar.item  badge="{{ App\Models\Member::count() }}" href="{{ route('members.index') }}" >Sajili Member</flux:sidebar.item>
                {{-- <flux:sidebar.item href="#" badge="12">Member Wote</flux:sidebar.item> --}}
        
            </flux:sidebar.group>
            <flux:sidebar.item icon="banknotes"  href="{{ route('collections.index') }}">Pokea Malipo</flux:sidebar.item>
            <flux:sidebar.item icon="receipt-refund"  href="{{ route('expenses.index') }}">Matumizi</flux:sidebar.item>
     

     <flux:sidebar.group   expandable
    :expanded="false" heading="Reports" icon="chart-bar" class="grid">
                <flux:sidebar.item href="{{ route('daily.report') }}">Funga Hesabu</flux:sidebar.item>
   
                <flux:sidebar.item href="{{ route('unpaid.report') }}">Ambao Hawajalipa</flux:sidebar.item>
                <flux:sidebar.item href="{{ route('payments.report') }}">Ambao Wamelipa</flux:sidebar.item>
                <flux:sidebar.item href="{{ route('penalties.report') }}">Ambao Waliolipa Faini</flux:sidebar.item>
             
            </flux:sidebar.group>

            <flux:sidebar.group   expandable
            :expanded="false" heading="Users" icon="users" class="grid">
                <flux:sidebar.item href="{{ route('profile.edit') }}" icon="user">Profaili Yangu</flux:sidebar.item>
                <flux:sidebar.item href="{{ route('user-password.edit') }}" icon="lock-closed">Badilisha Password</flux:sidebar.item>
                @if(auth()->user()->isAdmin())
                <flux:sidebar.item href="{{ route('users.index') }}" icon="users">Watumiaji wa mfumo</flux:sidebar.item>
                @endif
            </flux:sidebar.group>

            
        </flux:sidebar.nav>

        <flux:sidebar.spacer />

       
    </flux:sidebar>

   
        {{ $slot }}
 
    @fluxScripts
    
<script src="../path/to/flowbite/dist/flowbite.min.js"></script>

    </body>
</html>
