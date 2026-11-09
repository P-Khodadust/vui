@props(['as' => 'p'])
{!! typography($as, raw($slot), attrs: $attributes->getAttributes()) !!}