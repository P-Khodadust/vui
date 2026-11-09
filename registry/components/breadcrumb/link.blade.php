@props(['href' => '#'])
{!! breadcrumb_link(raw($slot), $href, attrs: $attributes->getAttributes()) !!}