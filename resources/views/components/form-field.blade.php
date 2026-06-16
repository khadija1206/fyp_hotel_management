@props([
    'label',
    'name',
    'type' => 'text',
    'value' => null,
    'placeholder' => '',
    'required' => false,
    'hint' => null,
])

<div class="mb-3">
    <label for="{{ $name }}" class="form-label">
        {{ $label }}
        @if ($required) <span class="text-required">*</span> @endif
    </label>

    <input
        type="{{ $type }}"
        id="{{ $name }}"
        name="{{ $name }}"
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        class="form-control @error($name) is-invalid @enderror"
        {{ $required ? 'required' : '' }}
        {{ $attributes }}
    />

    @if ($hint && !$errors->has($name))
        <small class="form-text text-secondary-custom">{{ $hint }}</small>
    @endif

    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
