@props(['variant' => 'default', 'size' => 'default'])
{!! button(raw($slot), $variant, $size, attrs: $attributes->getAttributes()) !!}