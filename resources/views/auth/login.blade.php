@extends('layouts.guest')

@push('title')
    Login
@endpush

@push('styles')
@endpush

@push('scripts')
@endpush

@section('content')
    <!--begin::Form-->
    <form class="form w-100" id="form" method="POST" action="{{ route('login') }}" novalidate>
        @csrf
        <!--begin::Heading-->
        <div class="mb-11 text-center">
            <h1 class="fw-bolder mb-3 text-gray-900">Sign In</h1>
            <div class="fw-semibold fs-6 text-gray-500">Access Your Account</div>
        </div>
        <div style="mb-11">
            <div class="alert alert-warning alert-dismissible *:d-flex align-items-center p-5 mb-10">
                <div class="d-flex flex-column">
                    <div class="row">
                        Email: demo@example.com
                    </div>
                    <div class="row">Password: demo
                    </div>
                </div>
            </div>
            <!--begin::Input group-->
            <div class="fv-row mb-8">
                <input type="email" placeholder="Email" name="email" autocomplete="email" required autofocus
                    class="form-control @session('error')
is-invalid
@endsession @error('email') is-invalid @enderror bg-transparent"
                    value="{{ old('email') }}" />
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="fv-row mb-3" data-kt-password-meter="true">
                <div class="mb-1">
                    <div class="position-relative mb-3">
                        <input class="form-control @error('password') is-invalid @enderror bg-transparent" type="password"
                            placeholder="Password" name="password" required autocomplete="current-password" />
                        @if (!$errors->has('password'))
                            <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 me-n2 end-0"
                                data-kt-password-meter-control="visibility">
                                <i class="fa-solid fa-eye-slash fs-2 @error('password') text-danger @enderror"></i>
                                <i class="fa-solid fa-eye fs-2 d-none @error('password') text-danger @enderror"></i>
                            </span>
                        @endif

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                    </div>
                </div>

                <!--begin::Wrapper-->
                <div class="d-flex flex-stack fs-base fw-semibold mb-8 flex-wrap gap-3">
                    <div>

                    </div>
                    {{-- @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="link-primary">Forgot Password ?</a>
                    @endif --}}
                </div>
                <!--begin::Submit button-->
                <div class="d-grid mb-10">
                    <button type="submit" id="submit_form" class="btn btn-primary">
                        <span class="indicator-label">Sign In</span>
                        <span class="indicator-progress">Please wait...
                            <span class="spinner-border spinner-border-sm ms-2 align-middle"></span>
                        </span>
                    </button>
                </div>

                @if (\App\Models\User::count() == 0)
                    <div class="text-gray-500 text-center fw-semibold fs-6">
                        Don't have an Account?
                        <a href="{{ route('register') }}" class="link-primary fw-semibold">
                            Create Account
                        </a>
                    </div>
                @endif
    </form>
    <!--end::Form-->
@endsection
