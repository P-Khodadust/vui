@props(['for' => ''])
{!! context_menu_trigger(raw($slot), $for, attrs: $attributes->getAttributes()) !!}