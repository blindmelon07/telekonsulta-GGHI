@props([
    'title',
    'description',
])

<div class="flex w-full flex-col items-center text-center">
    <img src="{{ asset('images/gghi logo (1).png') }}" alt="GGHI Logo" class="mb-4 h-20 w-auto" />
    <flux:heading size="xl">{{ $title }}</flux:heading>
    <flux:subheading>{{ $description }}</flux:subheading>
</div>
