@props(['label' => ''])
{!! select_optgroup(raw($slot), $label, attrs: $attributes->getAttributes()) !!}