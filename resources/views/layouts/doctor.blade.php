<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.header>
                <div class="flex items-center px-2">
                    <img src="{{ asset('images/gghi logo (1).png') }}" alt="MediConnect" class="h-8 w-auto" />
                </div>
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                <flux:sidebar.group :heading="__('Doctor Portal')" class="grid">
                    <flux:sidebar.item icon="home" :href="route('doctor.dashboard')" :current="request()->routeIs('doctor.dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="calendar-days" :href="route('doctor.appointments')" :current="request()->routeIs('doctor.appointments*')" wire:navigate>
                        {{ __('Appointments') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="clock" :href="route('doctor.schedule')" :current="request()->routeIs('doctor.schedule*')" wire:navigate>
                        {{ __('My Schedule') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="video-camera" :href="route('doctor.appointments', ['type' => 'teleconsultation'])" :current="false" wire:navigate>
                        {{ __('Teleconsultations') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="banknotes" :href="route('doctor.earnings')" :current="request()->routeIs('doctor.earnings*')" wire:navigate>
                        {{ __('Earnings') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>
            </flux:sidebar.nav>

            <flux:spacer />

            <flux:sidebar.nav>
                <flux:sidebar.item icon="bell" :href="route('doctor.notifications')" wire:navigate>
                    {{ __('Notifications') }}
                    @if(auth()->user()->unreadNotificationsCount() > 0)
                        <flux:badge size="sm" color="red" class="ml-auto">{{ auth()->user()->unreadNotificationsCount() }}</flux:badge>
                    @endif
                </flux:sidebar.item>
            </flux:sidebar.nav>

            <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
        </flux:sidebar>

        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
            <flux:spacer />
            <flux:dropdown position="top" align="end">
                <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />
                <flux:menu>
                    <flux:menu.separator />
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full cursor-pointer">
                            {{ __('Log out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        <flux:main>
            {{ $slot }}
        </flux:main>

        @persist('toast')
            <flux:toast.group><flux:toast /></flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
