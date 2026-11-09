@props(['for'])
{!! menubar_trigger(raw($slot), $for, attrs: $attributes->getAttributes()) !!}