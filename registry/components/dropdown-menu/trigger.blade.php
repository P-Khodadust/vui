@props(['for'])
{!! dropdown_menu_trigger(raw($slot), $for, attrs: $attributes->getAttributes()) !!}