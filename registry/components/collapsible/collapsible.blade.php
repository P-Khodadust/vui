@props(['open' => false])
{!! collapsible(raw($slot), $open, attrs: $attributes->getAttributes()) !!}