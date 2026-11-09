@props(['for' => ''])
{!! alert_dialog_description(raw($slot), $for, attrs: $attributes->getAttributes()) !!}