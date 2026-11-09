@props(['for' => ''])
{!! tooltip_trigger(raw($slot), $for, attrs: $attributes->getAttributes()) !!}