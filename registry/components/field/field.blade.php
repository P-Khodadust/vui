@props(['orientation' => 'vertical', 'invalid' => false])
{!! field(raw($slot), $orientation, $invalid, attrs: $attributes->getAttributes()) !!}