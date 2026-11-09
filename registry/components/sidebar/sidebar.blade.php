@props(['id' => 'sidebar'])
{!! sidebar(raw($slot), $id, attrs: $attributes->getAttributes()) !!}