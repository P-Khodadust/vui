@props(['href' => '#'])
{!! navigation_menu_link(raw($slot), $href, attrs: $attributes->getAttributes()) !!}