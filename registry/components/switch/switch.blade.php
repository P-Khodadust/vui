@props(['size' => 'default'])
{!! switch_($size, attrs: $attributes->getAttributes()) !!}