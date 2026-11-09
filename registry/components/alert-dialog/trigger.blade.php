@props(['for'])
{!! alert_dialog_trigger(raw($slot), $for, attrs: $attributes->getAttributes()) !!}