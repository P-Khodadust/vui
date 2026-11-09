@props(['name' => '', 'open' => false])
{!! accordion_item(raw($slot), $name, $open, attrs: $attributes->getAttributes()) !!}