@props(['id'])
{!! dropdown_menu_content(raw($slot), $id, attrs: $attributes->getAttributes()) !!}