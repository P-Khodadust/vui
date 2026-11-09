@props(['for'])
{!! sheet_trigger(raw($slot), $for, attrs: $attributes->getAttributes()) !!}