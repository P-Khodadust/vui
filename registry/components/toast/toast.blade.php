@props(['variant' => 'default', 'close' => 'Close'])
{!! toast(raw($slot), $variant, $close, attrs: $attributes->getAttributes()) !!}