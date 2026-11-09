@props(['for' => '', 'variant' => 'default'])
{!! context_menu_item(raw($slot), $for, $variant, attrs: $attributes->getAttributes()) !!}