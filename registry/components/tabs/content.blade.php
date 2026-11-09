@props(['value', 'active' => false])
{!! tabs_content(raw($slot), $value, $active, attrs: $attributes->getAttributes()) !!}