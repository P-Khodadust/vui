@props(['for' => '', 'variant' => 'default'])
{!! dropdown_menu_item(raw($slot), $for, $variant, attrs: $attributes->getAttributes()) !!}