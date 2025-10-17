@props([
    'label' => '',
    'name' => '',
    'id' => '',
    'value' => '1',
    'checked' => false,
    'switchLabel' => '',
    'hint' => '',
])

@php
    $switchId = $id ?: $name;
@endphp

<div class="fv-row mb-8">
    @if($label)
        <label class="form-label">{{ $label }}</label>
    @endif
    <div class="form-check form-switch form-check-custom form-check-solid">
        <input
            class="form-check-input"
            type="checkbox"
            name="{{ $name }}"
            value="{{ $value }}"
            id="{{ $switchId }}"
            {{ old($name, $checked) ? 'checked' : '' }}
            {{ $attributes }}
        />
        @if($switchLabel)
            <label class="form-check-label" for="{{ $switchId }}">
                {{ $switchLabel }}
            </label>
        @endif
    </div>
    @if($hint)
        <div class="form-text">{{ $hint }}</div>
    @endif
    @error($name)
        <span class="text-danger" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>
