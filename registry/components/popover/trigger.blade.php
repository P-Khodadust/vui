@props(['for'])
{!! popover_trigger(raw($slot), $for, attrs: $attributes->getAttributes()) !!}