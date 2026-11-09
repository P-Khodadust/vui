@props(['size' => 'default'])
{!! avatar(raw($slot), $size, attrs: $attributes->getAttributes()) !!}