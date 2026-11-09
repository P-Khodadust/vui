@props(['heading' => ''])
{!! command_group(raw($slot), $heading, attrs: $attributes->getAttributes()) !!}