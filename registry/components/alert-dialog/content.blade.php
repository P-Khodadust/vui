@props(['id'])
{!! alert_dialog_content(raw($slot), $id, attrs: $attributes->getAttributes()) !!}