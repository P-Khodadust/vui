@props(['href' => '#', 'active' => false])
{!! pagination_link(raw($slot), $href, $active, attrs: $attributes->getAttributes()) !!}