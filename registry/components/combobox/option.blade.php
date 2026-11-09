@props(['value' => ''])
{!! combobox_option($value, raw($slot), attrs: $attributes->getAttributes()) !!}