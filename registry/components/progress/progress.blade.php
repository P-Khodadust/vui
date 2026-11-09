@props(['value' => null, 'max' => 100])
{!! progress($value, $max, attrs: $attributes->getAttributes()) !!}