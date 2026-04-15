@props(['status'])

@php
$color = match($status) {
    'confirmed'  => 'green',
    'pending'    => 'yellow',
    'completed'  => 'blue',
    'cancelled'  => 'zinc',
    'no_show'    => 'red',
    'paid'       => 'green',
    'unpaid'     => 'red',
    'refunded'   => 'blue',
    'failed'     => 'red',
    'active'     => 'green',
    'inactive'   => 'zinc',
    default      => 'zinc',
};
@endphp

<flux:badge color="{{ $color }}" size="sm">{{ ucfirst(str_replace('_', ' ', $status)) }}</flux:badge>
