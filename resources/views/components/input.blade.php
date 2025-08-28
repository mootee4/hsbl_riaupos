@props(['label', 'name', 'type' => 'text', 'placeholder' => ''])

<div>
    <label class="block text-sm font-medium text-gray-700">{{ $label }}</label>
    <input type="{{ $type }}" name="{{ $name }}" placeholder="{{ $placeholder }}" class="mt-1 block w-full border border-gray-300 p-2 rounded" />
</div>
