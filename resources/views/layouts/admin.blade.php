<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.header>
                <div class="flex items-center gap-2 px-2">
                    <flux:icon.shield-check class="size-6 text-violet-600" />
                    <flux:heading size="lg" class="font-bold text-violet-600">MediConnect Admin</flux:heading>
                </div>
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                <flux:sidebar.group :heading="__('Administration')" class="grid">
                    <flux:sidebar.item icon="home" :href="route('admin.dashboard')" :current="request()->routeIs('admin.dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="users" :href="route('admin.doctors')" :current="request()->routeIs('admin.doctors*')" wire:navigate>
                        {{ __('Manage Doctors') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="user-group" :href="route('admin.patients')" :current="request()->routeIs('admin.patients*')" wire:navigate>
                        {{ __('Manage Patients') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="calendar" :href="route('admin.appointments')" :current="request()->routeIs('admin.appointments*')" wire:navigate>
                        {{ __('Appointments') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="credit-card" :href="route('admin.payments')" :current="request()->routeIs('admin.payments*')" wire:navigate>
                        {{ __('Payments') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="chart-bar" :href="route('admin.reports')" :current="request()->routeIs('admin.reports*')" wire:navigate>
                        {{ __('Reports') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="cog-6-tooth" :href="route('admin.settings')" :current="request()->routeIs('admin.settings*')" wire:navigate>
                        {{ __('System Settings') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>
            </flux:sidebar.nav>

            <flux:spacer />

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
