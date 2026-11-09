@props(['variant' => 'ghost'])
{!! input_group_button(raw($slot), $variant, attrs: $attributes->getAttributes()) !!}