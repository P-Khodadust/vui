@props(['value' => null, 'selected' => false])
{!! select_option(raw($slot), $value, $selected, attrs: $attributes->getAttributes()) !!}