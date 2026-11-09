@props(['variant' => 'default', 'size' => 'default'])
{!! item(raw($slot), $variant, $size, attrs: $attributes->getAttributes()) !!}