@props(['variant' => 'default'])
{!! badge(raw($slot), $variant, attrs: $attributes->getAttributes()) !!}