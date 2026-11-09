@props(['id'])
{!! menubar_content(raw($slot), $id, attrs: $attributes->getAttributes()) !!}