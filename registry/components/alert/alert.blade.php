@props(['variant' => 'default'])
{!! alert(raw($slot), $variant, attrs: $attributes->getAttributes()) !!}