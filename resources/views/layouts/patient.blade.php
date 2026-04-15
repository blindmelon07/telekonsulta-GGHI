<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.header>
                <div class="flex items-center gap-2 px-2">
                    <flux:icon.heart-pulse class="size-6 text-blue-600" />
                    <flux:heading size="lg" class="font-bold text-blue-600">MediConnect</flux:heading>
                </div>
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                <flux:sidebar.group :heading="__('Patient Portal')" class="grid">
                    <flux:sidebar.item icon="home" :href="route('patient.dashboard')" :current="request()->routeIs('patient.dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="calendar" :href="route('patient.appointments')" :current="request()->routeIs('patient.appointments*')" wire:navigate>
                        {{ __('Appointments') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="video" :href="route('patient.teleconsultations')" :current="request()->routeIs('patient.teleconsultations*')" wire:navigate>
                        {{ __('Teleconsultations') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="document-text" :href="route('patient.medical-records')" :current="request()->routeIs('patient.medical-records*')" wire:navigate>
                        {{ __('Medical Records') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="credit-card" :href="route('patient.payment-history')" :current="request()->routeIs('patient.payment-history*')" wire:navigate>
                        {{ __('Payments') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>

                <flux:sidebar.group :heading="__('Browse')" class="grid">
                    <flux:sidebar.item icon="magnifying-glass" :href="route('doctors.index')" :current="request()->routeIs('doctors.*')" wire:navigate>
                        {{ __('Find Doctors') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>
            </flux:sidebar.nav>

            <flux:spacer />

            <flux:sidebar.nav>
                <flux:sidebar.item icon="bell" :href="route('patient.notifications')" wire:navigate>
                    {{ __('Notifications') }}
                    @if(auth()->user()->unreadNotificationsCount() > 0)
                        <flux:badge size="sm" color="red" class="ml-auto">{{ auth()->user()->unreadNotificationsCount() }}</flux:badge>
                    @endif
                </flux:sidebar.item>
                <flux:sidebar.item icon="cog-6-tooth" :href="route('patient.profile.edit')" wire:navigate>
                    {{ __('Profile') }}
                </flux:sidebar.item>
            </flux:sidebar.nav>

            <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
        </flux:sidebar>

        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
            <flux:spacer />
            <livewire:shared.notification-bell lazy />
            <flux:dropdown position="top" align="end">
                <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />
                <flux:menu>
                    <flux:menu.item :href="route('patient.profile.edit')" icon="cog" wire:navigate>{{ __('Profile') }}</flux:menu.item>
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
