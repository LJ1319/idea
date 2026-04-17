@props(['label', 'name', 'type' => 'text'])

<div class="space-y-2">
    <label for={{ $name }} class="labael">{{ $label }}</label>
    <input type={{ $type }} id={{ $name }} name={{ $name }} value="{{ old($name, '') }}"
           class="input" {{ $attributes }}>

    @error($name)
    <p class="error">{{ $message }}</p>
    @enderror
</div>
