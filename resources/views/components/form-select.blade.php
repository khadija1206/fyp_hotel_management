@props([
    'label',
    'name',
    'options' => [],
    'selected' => null,
    'placeholder' => 'Select an option',
    'required' => false,
])

<div class="mb-3">
    <label for="{{ $name }}" class="form-label">
        {{ $label }}
        @if ($required) <span class="text-required">*</span> @endif
    </label>

    <select
        id="{{ $name }}"
        name="{{ $name }}"
        class="form-select @error($name) is-invalid @enderror"
        {{ $required ? 'required' : '' }}
        {{ $attributes }}
    >
        <option value="">{{ $placeholder }}</option>
        @foreach ($options as $value => $optionLabel)
            <option value="{{ $value }}" {{ old($name, $selected) == $value ? 'selected' : '' }}>
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>

    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
