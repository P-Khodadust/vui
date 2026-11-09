@props(['for' => '', 'variant' => 'default'])
{!! menubar_item(raw($slot), $for, $variant, attrs: $attributes->getAttributes()) !!}