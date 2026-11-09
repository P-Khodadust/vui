@props(['name', 'label', 'value' => '', 'error' => null, 'description' => '', 'type' => 'text'])
@php($key = rtrim(strtr($name, ['[' => '.', ']' => '']), '.'))
{!! form_field($name, $label, old($key, $value), $error ?? ($errors ?? null)?->first($key) ?? '', $description, $type, $attributes->get('class', ''), $attributes->except('class')->getAttributes()) !!}