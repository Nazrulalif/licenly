@props([
    'label' => 'Password',
    'name' => 'password',
    'placeholder' => 'Enter password',
    'required' => false,
    'showMeter' => true,
    'hint' => 'Use 8 or more characters with a mix of letters, numbers & symbols.',
    'autocomplete' => 'new-password',
])

<div class="fv-row mb-8" {{ $showMeter ? 'data-kt-password-meter=true' : '' }}>
    @if($label)
        <label class="{{ $required ? 'required' : '' }} form-label">{{ $label }}</label>
    @endif
    <div class="mb-1">
        <div class="position-relative mb-3">
            <input
                class="form-control @error($name) is-invalid @enderror"
                type="password"
                placeholder="{{ $placeholder }}"
                name="{{ $name }}"
                {{ $required ? 'required' : '' }}
                autocomplete="{{ $autocomplete }}"
                {{ $attributes }}
            />
            @if (!$errors->has($name))
                <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 me-n2 end-0"
                    data-kt-password-meter-control="visibility">
                    <i class="fa-solid fa-eye-slash fs-2"></i>
                    <i class="fa-solid fa-eye fs-2 d-none"></i>
                </span>
            @endif

            @error($name)
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        @if($showMeter)
            <!--begin::Meter-->
            <div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
                <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
            </div>
            <!--end::Meter-->
        @endif
    </div>

    @if($hint)
        <!--begin::Hint-->
        <div class="text-muted">
            {{ $hint }}
        </div>
        <!--end::Hint-->
    @endif
</div>
