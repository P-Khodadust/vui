@props(['label' => 'Toggle Sidebar', 'for' => 'sidebar'])
{!! sidebar_trigger($label, $for, attrs: $attributes->getAttributes()) !!}