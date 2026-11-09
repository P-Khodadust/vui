@props(['id'])
{!! popover_content(raw($slot), $id, attrs: $attributes->getAttributes()) !!}