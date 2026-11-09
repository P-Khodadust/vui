@props(['id'])
{!! navigation_menu_content(raw($slot), $id, attrs: $attributes->getAttributes()) !!}