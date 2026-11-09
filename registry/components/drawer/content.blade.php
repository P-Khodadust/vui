@props(['id'])
{!! drawer_content(raw($slot), $id, attrs: $attributes->getAttributes()) !!}