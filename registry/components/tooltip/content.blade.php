@props(['id' => ''])
{!! tooltip_content(raw($slot), $id, attrs: $attributes->getAttributes()) !!}