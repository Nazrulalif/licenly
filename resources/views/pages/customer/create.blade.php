@extends('layouts.app')

@section('title', 'Add New Customer')

@section('page-title', 'Add New Customer')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('customer.index') }}">Customers</a></li>
    <li class="breadcrumb-item text-gray-900">Add New</li>
@endsection

@section('content')

    <form action="{{ route('customer.store') }}" method="POST" id="customer-form">
        @csrf

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Customer Information</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Company Name -->
                    <div class="col-md-6 mb-7">
                        <label class="required form-label">Company Name</label>
                        <input type="text" name="company_name" class="form-control @error('company_name') is-invalid @enderror"
                            placeholder="Enter company name" value="{{ old('company_name') }}" required />
                        @error('company_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Contact Name -->
                    <div class="col-md-6 mb-7">
                        <label class="required form-label">Contact Name</label>
                        <input type="text" name="contact_name" class="form-control @error('contact_name') is-invalid @enderror"
                            placeholder="Enter contact person name" value="{{ old('contact_name') }}" required />
                        @error('contact_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="col-md-6 mb-7">
                        <label class="required form-label">Email Address</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            placeholder="customer@example.com" value="{{ old('email') }}" required />
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Email must be unique</div>
                    </div>

                    <!-- Phone -->
                    <div class="col-md-6 mb-7">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                            placeholder="+1-555-0123" value="{{ old('phone') }}" />
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div class="col-md-6 mb-7">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control @error('address') is-invalid @enderror"
                            rows="3" placeholder="Enter full address">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Country -->
                    <div class="col-md-6 mb-7">
                        <label class="form-label">Country</label>
                        <select name="country" class="form-select @error('country') is-invalid @enderror" data-control="select2"
                            data-placeholder="Select a country">
                            <option value="">Select Country</option>
                            <option value="United States" {{ old('country') == 'United States' ? 'selected' : '' }}>United States</option>
                            <option value="United Kingdom" {{ old('country') == 'United Kingdom' ? 'selected' : '' }}>United Kingdom</option>
                            <option value="Canada" {{ old('country') == 'Canada' ? 'selected' : '' }}>Canada</option>
                            <option value="Australia" {{ old('country') == 'Australia' ? 'selected' : '' }}>Australia</option>
                            <option value="Germany" {{ old('country') == 'Germany' ? 'selected' : '' }}>Germany</option>
                            <option value="France" {{ old('country') == 'France' ? 'selected' : '' }}>France</option>
                            <option value="India" {{ old('country') == 'India' ? 'selected' : '' }}>India</option>
                            <option value="China" {{ old('country') == 'China' ? 'selected' : '' }}>China</option>
                            <option value="Japan" {{ old('country') == 'Japan' ? 'selected' : '' }}>Japan</option>
                            <option value="Singapore" {{ old('country') == 'Singapore' ? 'selected' : '' }}>Singapore</option>
                            <option value="Malaysia" {{ old('country') == 'Malaysia' ? 'selected' : '' }}>Malaysia</option>
                            <option value="Other" {{ old('country') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('country')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div class="col-12 mb-7">
                        <label class="form-label">Internal Notes</label>
                        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror"
                            rows="4" placeholder="Add any internal notes about this customer...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">These notes are for internal use only</div>
                    </div>

                    <!-- Active Status -->
                    <div class="col-12 mb-7">
                        <div class="form-check form-switch form-check-custom form-check-solid">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                id="is_active" {{ old('is_active', true) ? 'checked' : '' }} />
                            <label class="form-check-label" for="is_active">
                                Active Customer
                            </label>
                        </div>
                        <div class="form-text">Inactive customers cannot receive new licenses</div>
                    </div>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-end py-6">
                <a href="{{ route('customer.index') }}" class="btn btn-light me-3">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="ki-duotone ki-check fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Save Customer
                </button>
            </div>
        </div>
    </form>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize Select2 for country dropdown
            $('[data-control="select2"]').select2({
                placeholder: "Select a country",
                allowClear: true
            });
        });
    </script>
@endpush
