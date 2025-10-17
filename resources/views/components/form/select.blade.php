@props([
    'label' => '',
    'name' => '',
    'placeholder' => 'Select an option',
    'required' => false,
    'options' => [],
    'selected' => '',
    'useSelect2' => true,
])

<div class="fv-row mb-8">
    @if($label)
        <label class="{{ $required ? 'required' : '' }} form-label">{{ $label }}</label>
    @endif
    <select
        name="{{ $name }}"
        class="form-select @error($name) is-invalid @enderror"
        {{ $useSelect2 ? 'data-control=select2' : '' }}
        {{ $useSelect2 ? 'data-placeholder=' . $placeholder : '' }}
        {{ $required ? 'required' : '' }}
        {{ $attributes }}
    >
        <option value="">{{ $placeholder }}</option>
        @foreach($options as $value => $text)
            <option value="{{ $value }}" {{ old($name, $selected) == $value ? 'selected' : '' }}>
                {{ $text }}
            </option>
        @endforeach
    </select>
    @error($name)
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>
