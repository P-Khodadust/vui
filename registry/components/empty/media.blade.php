@props(['variant' => 'default'])
{!! empty_media(raw($slot), $variant, attrs: $attributes->getAttributes()) !!}