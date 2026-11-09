@props(['variant' => 'default'])
{!! item_media(raw($slot), $variant, attrs: $attributes->getAttributes()) !!}