<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <!-- Public Navigation -->
        <nav class="border-b border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <a href="{{ route('home') }}" wire:navigate class="flex items-center">
                        <img src="{{ asset('images/gghi logo (1).png') }}" alt="MediConnect" class="h-10 w-auto" />
                    </a>

                    <div class="hidden items-center gap-6 md:flex">
                        <a href="{{ route('doctors.index') }}" wire:navigate class="text-sm font-medium text-zinc-600 hover:text-blue-600 dark:text-zinc-400">Find Doctors</a>
                        @auth
                            @role('patient')
                                <a href="{{ route('patient.dashboard') }}" wire:navigate class="text-sm font-medium text-zinc-600 hover:text-blue-600 dark:text-zinc-400">Dashboard</a>
                            @endrole
                            @role('doctor')
                                <a href="{{ route('doctor.dashboard') }}" wire:navigate class="text-sm font-medium text-zinc-600 hover:text-blue-600 dark:text-zinc-400">Dashboard</a>
                            @endrole
                            @role('admin|super_admin')
                                <a href="{{ route('admin.dashboard') }}" wire:navigate class="text-sm font-medium text-zinc-600 hover:text-blue-600 dark:text-zinc-400">Admin</a>
                            @endrole
                        @else
                            <a href="{{ route('login') }}" wire:navigate class="text-sm font-medium text-zinc-600 hover:text-blue-600 dark:text-zinc-400">Login</a>
                            <flux:button href="{{ route('register') }}" wire:navigate size="sm" variant="primary">Get Started</flux:button>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <main>
            {{ $slot }}
        </main>

        <footer class="mt-16 border-t border-zinc-200 bg-zinc-50 py-8 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="mx-auto max-w-7xl px-4 text-center text-sm text-zinc-500">
                &copy; {{ date('Y') }} MediConnect. All rights reserved.
            </div>
        </footer>

        @persist('toast')
            <flux:toast.group><flux:toast /></flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
