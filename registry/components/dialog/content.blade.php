@props(['id', 'close' => 'Close'])
{!! dialog_content(raw($slot), $id, $close, attrs: $attributes->getAttributes()) !!}