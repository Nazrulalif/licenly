@props([
    'label' => '',
    'name' => '',
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'rows' => 3,
    'hint' => '',
])

<div class="fv-row mb-8">
    @if($label)
        <label class="{{ $required ? 'required' : '' }} form-label">{{ $label }}</label>
    @endif
    <textarea
        name="{{ $name }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        class="form-control @error($name) is-invalid @enderror"
        rows="{{ $rows }}"
        {{ $attributes }}
    >{{ old($name, $value) }}</textarea>
    @if($hint)
        <div class="form-text">{{ $hint }}</div>
    @endif
    @error($name)
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>
