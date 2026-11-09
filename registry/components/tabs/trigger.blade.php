@props(['value', 'active' => false])
{!! tabs_trigger(raw($slot), $value, $active, attrs: $attributes->getAttributes()) !!}