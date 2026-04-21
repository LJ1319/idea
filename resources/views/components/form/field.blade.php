@props(['label' => false, 'name', 'type' => 'text'])

<div class="space-y-2">
    @if ($label)
        <label for={{ $name }} class="labael">{{ $label }}</label>
    @endif

    @if ($type === 'textarea')
        <textarea
            name="{{ $name }}"
            id="{{ $name }}"
            class="textarea"
            {{ $attributes }}
        >{{ old($name) }}</textarea>
    @else
        <input
            type={{ $type }}
            id={{ $name }}
            name={{ $name }}
            value="{{ old($name, '') }}"
            class="input"
            {{ $attributes }}>
    @endif

    <x-form.error name="{{ $name }}"/>
</div>
