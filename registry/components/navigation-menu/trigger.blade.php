@props(['for'])
{!! navigation_menu_trigger(raw($slot), $for, attrs: $attributes->getAttributes()) !!}