@props(['for'])
{!! drawer_trigger(raw($slot), $for, attrs: $attributes->getAttributes()) !!}