@props(['for' => ''])
{!! alert_dialog_title(raw($slot), $for, attrs: $attributes->getAttributes()) !!}