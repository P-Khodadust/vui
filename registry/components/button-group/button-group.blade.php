@props(['orientation' => 'horizontal'])
{!! button_group(raw($slot), $orientation, attrs: $attributes->getAttributes()) !!}