@props(['align' => 'inline-start'])
{!! input_group_addon(raw($slot), $align, attrs: $attributes->getAttributes()) !!}