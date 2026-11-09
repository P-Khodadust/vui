@props(['id', 'side' => 'end', 'close' => 'Close'])
{!! sheet_content(raw($slot), $id, $side, $close, attrs: $attributes->getAttributes()) !!}