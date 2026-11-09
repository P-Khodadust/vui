@props(['error' => false])
{!! form_label(raw($slot), $error, attrs: $attributes->getAttributes()) !!}