@props(['size' => 'default'])
{!! select(raw($slot), $size, attrs: $attributes->getAttributes()) !!}