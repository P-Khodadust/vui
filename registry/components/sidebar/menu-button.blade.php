@props(['href' => '#', 'active' => false])
{!! sidebar_menu_button(raw($slot), $href, $active, attrs: $attributes->getAttributes()) !!}