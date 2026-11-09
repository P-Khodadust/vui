@props(['value' => null, 'min' => 0, 'max' => 100, 'step' => 1])
{!! slider($value, $min, $max, $step, attrs: $attributes->getAttributes()) !!}