@props(['id'])
{!! context_menu_content(raw($slot), $id, attrs: $attributes->getAttributes()) !!}