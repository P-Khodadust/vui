@props(['close' => 'Close'])
{!! toaster(raw($slot), $close, attrs: $attributes->getAttributes()) !!}