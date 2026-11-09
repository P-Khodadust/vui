@props(['ratio' => '16/9'])
{!! aspect_ratio(raw($slot), $ratio, attrs: $attributes->getAttributes()) !!}