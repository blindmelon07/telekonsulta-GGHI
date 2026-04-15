@props([
    'label',
    'value',
    'color' => 'zinc',
    'icon'  => null,
])

@php
$borderColor = match($color) {
    'blue'  => 'border-blue-100 dark:border-blue-900',
    'green' => 'border-green-100 dark:border-green-900',
    'red'   => 'border-red-100 dark:border-red-900',
    'yellow' => 'border-yellow-100 dark:border-yellow-900',
    default => 'border-zinc-200 dark:border-zinc-700',
};
$bgColor = match($color) {
    'blue'  => 'bg-blue-50 dark:bg-blue-950',
    'green' => 'bg-green-50 dark:bg-green-950',
    'red'   => 'bg-red-50 dark:bg-red-950',
    'yellow' => 'bg-yellow-50 dark:bg-yellow-950',
    default => 'bg-white dark:bg-zinc-900',
};
$textColor = match($color) {
    'blue'  => 'text-blue-600',
    'green' => 'text-green-600',
    'red'   => 'text-red-600',
    'yellow' => 'text-yellow-600',
    default => 'text-zinc-500',
};
$valueColor = match($color) {
    'blue'  => 'text-blue-700 dark:text-blue-300',
    'green' => 'text-green-700 dark:text-green-300',
    'red'   => 'text-red-700 dark:text-red-300',
    'yellow' => 'text-yellow-700 dark:text-yellow-300',
    default => 'text-zinc-900 dark:text-white',
};
@endphp

<div class="rounded-xl border {{ $borderColor }} {{ $bgColor }} p-6">
    <p class="text-sm {{ $textColor }}">{{ $label }}</p>
    <p class="mt-1 text-3xl font-bold {{ $valueColor }}">{{ $value }}</p>
</div>
