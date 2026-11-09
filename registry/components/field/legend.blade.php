@props(['variant' => 'legend'])
{!! field_legend(raw($slot), $variant, attrs: $attributes->getAttributes()) !!}