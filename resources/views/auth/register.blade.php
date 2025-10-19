@extends('layouts.guest')

@push('title')
    Register
@endpush

@push('styles')
@endpush

@push('scripts')
@endpush

@section('content')
    <!--begin::Form-->
    <form class="form w-100" id="form" method="POST" action="{{ route('register') }}" novalidate>
        @csrf
        <!--begin::Heading-->
        <div class="mb-11 text-center">
            <h1 class="fw-bolder mb-3 text-gray-900">Create Account</h1>
            <div class="fw-semibold fs-6 text-gray-500">Start Your Journey With Us</div>
        </div>
        <!--end::Heading-->

        <!--begin::Login options-->
        <div class="row g-3 mb-9">
            <a href="#" class="btn btn-outline btn-text-gray-700 btn-active-color-primary bg-state-light w-100">
                <img alt="Logo" src="{{ asset('/assets/media/svg/brand-logos/google-icon.svg') }}"
                    class="h-15px me-3" />
                Sign up with Google
            </a>
        </div>
        <!--end::Login options-->

        <!--begin::Separator-->
        <div class="separator separator-content my-14">
            <span class="w-125px fw-semibold fs-7 text-gray-500">Or with email</span>
        </div>
        <!--end::Separator-->

        <!--begin::Input group - Name-->
        <div class="fv-row mb-8">
            <input type="text" placeholder="Full Name" name="name" autocomplete="name" required autofocus
                class="form-control @error('name') is-invalid @enderror bg-transparent" value="{{ old('name') }}" />
            @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <!--end::Input group-->

        <!--begin::Input group - Email-->
        <div class="fv-row mb-8">
            <input type="email" placeholder="Email" name="email" autocomplete="email" required
                class="form-control @error('email') is-invalid @enderror bg-transparent" value="{{ old('email') }}" />
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <!--end::Input group-->

        <!--begin::Input group - Password-->
        <div class="fv-row mb-8" data-kt-password-meter="true">
            <div class="mb-1">
                <div class="position-relative mb-3">
                    <input class="form-control @error('password') is-invalid @enderror bg-transparent" type="password"
                        placeholder="Password" name="password" required autocomplete="new-password" />
                    @if (!$errors->has('password'))
                        <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 me-n2 end-0"
                            data-kt-password-meter-control="visibility">
                            <i class="fa-solid fa-eye-slash fs-2"></i>
                            <i class="fa-solid fa-eye fs-2 d-none"></i>
                        </span>
                    @endif

                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!--begin::Meter-->
                <div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
                </div>
                <!--end::Meter-->
            </div>

            <!--begin::Hint-->
            <div class="text-muted">
                Use 8 or more characters with a mix of letters, numbers & symbols.
            </div>
            <!--end::Hint-->
        </div>
        <!--end::Input group-->

        <!--begin::Input group - Confirm Password-->
        <div class="fv-row mb-8">
            <input type="password" placeholder="Confirm Password" name="password_confirmation" required
                autocomplete="new-password"
                class="form-control @error('password_confirmation') is-invalid @enderror bg-transparent" />
            @error('password_confirmation')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <!--end::Input group-->

        <!--begin::Accept Terms-->
        <div class="fv-row mb-8">
            <label class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="terms" value="1" required />
                <span class="form-check-label fw-semibold text-gray-700 fs-base ms-1">
                    I Agree to the
                    <a href="#" class="ms-1 link-primary">Terms and Conditions</a>
                </span>
            </label>
            @error('terms')
                <div class="text-danger mt-2">
                    <strong>{{ $message }}</strong>
                </div>
            @enderror
        </div>
        <!--end::Accept Terms-->

        <!--begin::Submit button-->
        <div class="d-grid mb-10">
            <button type="submit" id="submit_form" class="btn btn-primary">
                <span class="indicator-label">Sign Up</span>
                <span class="indicator-progress">Please wait...
                    <span class="spinner-border spinner-border-sm ms-2 align-middle"></span>
                </span>
            </button>
        </div>
        <!--end::Submit button-->

        <!--begin::Sign in link-->
        <div class="text-gray-500 text-center fw-semibold fs-6">
            Already have an Account?
            <a href="{{ route('login') }}" class="link-primary fw-semibold">
                Sign In
            </a>
        </div>
        <!--end::Sign in link-->
    </form>
    <!--end::Form-->
@endsection
