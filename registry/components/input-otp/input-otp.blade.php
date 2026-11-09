@props(['length' => 6])
{!! input_otp($length, $attributes->get('class', ''), $attributes->except('class')->getAttributes()) !!}