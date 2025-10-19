@extends('layouts.app')

@section('title', 'Generate New License')

@section('page-title', 'Generate New License')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('license.index') }}">Licenses</a></li>
    <li class="breadcrumb-item text-gray-900">Generate New</li>
@endsection

@section('content')

    <form action="{{ route('license.store') }}" method="POST" id="license-form">
        @csrf

        <div class="row">
            <!-- Main Form -->
            <div class="col-xl-8">
                <div class="card mb-5">
                    <div class="card-header">
                        <h3 class="card-title">License Information</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Customer Selection -->
                            <div class="col-12 mb-7">
                                <label class="required form-label">Select Customer</label>
                                <select name="customer_id" id="customer_id"
                                    class="form-select @error('customer_id') is-invalid @enderror" data-control="select2"
                                    data-placeholder="Select a customer" required>
                                    <option value="">Select Customer</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}"
                                            {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->company_name }} ({{ $customer->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('customer_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if ($customers->count() == 0)
                                    <div class="form-text text-danger">
                                        No active customers found. <a href="{{ route('customer.create') }}">Create a
                                            customer first</a>.
                                    </div>
                                @else
                                    <div class="form-text">Select the customer who will receive this license</div>
                                @endif
                            </div>

                            <!-- License Type -->
                            <div class="col-md-6 mb-7">
                                <label class="required form-label">License Type</label>
                                <select name="license_type" id="license_type"
                                    class="form-select @error('license_type') is-invalid @enderror" data-control="select2"
                                    data-placeholder="Select license type" required>
                                    <option value="">Select Type</option>
                                    <option value="TRIAL" {{ old('license_type') == 'TRIAL' ? 'selected' : '' }}>
                                        Trial
                                    </option>
                                    <option value="PERSONAL" {{ old('license_type') == 'PERSONAL' ? 'selected' : '' }}>
                                        Personal
                                    </option>
                                    <option value="PROFESSIONAL"
                                        {{ old('license_type') == 'PROFESSIONAL' ? 'selected' : '' }}>
                                        Professional
                                    </option>
                                    <option value="ENTERPRISE" {{ old('license_type') == 'ENTERPRISE' ? 'selected' : '' }}>
                                        Enterprise
                                    </option>
                                    <option value="CUSTOM" {{ old('license_type') == 'CUSTOM' ? 'selected' : '' }}>
                                        Custom
                                    </option>
                                </select>
                                @error('license_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Max Devices -->
                            <div class="col-md-6 mb-7">
                                <label class="required form-label">Max Devices</label>
                                <input type="number" name="max_devices" id="max_devices"
                                    class="form-control @error('max_devices') is-invalid @enderror"
                                    placeholder="Enter maximum devices" value="{{ old('max_devices', 1) }}" min="1"
                                    max="1000" required />
                                @error('max_devices')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Number of devices allowed to use this license (1-1000)</div>
                            </div>

                            <!-- Issue Date -->
                            <div class="col-md-6 mb-7">
                                <label class="required form-label">Issue Date</label>
                                <input type="date" name="issue_date" id="issue_date"
                                    class="form-control @error('issue_date') is-invalid @enderror"
                                    value="{{ old('issue_date', date('Y-m-d')) }}" required />
                                @error('issue_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Expiry Date -->
                            <div class="col-md-6 mb-7">
                                <label class="required form-label">Expiry Date</label>
                                <input type="date" name="expiry_date" id="expiry_date"
                                    class="form-control @error('expiry_date') is-invalid @enderror"
                                    value="{{ old('expiry_date', date('Y-m-d', strtotime('+1 year'))) }}" required />
                                @error('expiry_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">License will expire on this date</div>
                            </div>

                            <!-- Hardware ID (Optional) -->
                            <div class="col-12 mb-7">
                                <label class="form-label">
                                    Hardware ID (Optional)
                                    <i class="ki-duotone ki-information-5 fs-6" data-bs-toggle="tooltip"
                                        title="MAC address or unique hardware identifier to bind this license to specific hardware">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                </label>
                                <input type="text" name="hardware_id" id="hardware_id"
                                    class="form-control @error('hardware_id') is-invalid @enderror"
                                    placeholder="00:1B:44:11:3A:B7" value="{{ old('hardware_id') }}"
                                    pattern="^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$" />
                                @error('hardware_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Leave empty for no hardware binding (MAC address format:
                                    XX:XX:XX:XX:XX:XX)</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Features Section -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">License Features (JSON Format)</h3>
                        <div class="card-toolbar">
                            <button type="button" class="btn btn-sm btn-light-primary" data-template="trial">
                                <i class="ki-duotone ki-file fs-5">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                Load Template
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-5">
                            <label class="required form-label">Features Configuration</label>
                            <textarea name="features" id="features" class="form-control @error('features') is-invalid @enderror font-monospace"
                                rows="15" required>{{ old('features') }}</textarea>
                            @error('features')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-danger" id="json-error" style="display: none;"></div>
                        </div>

                        <!-- JSON Validation Status -->
                        <div class="alert alert-light-info d-flex align-items-center p-5">
                            <i class="ki-duotone ki-shield-tick fs-2hx text-info me-4">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <div class="d-flex flex-column">
                                <h4 class="mb-1 text-dark">JSON Format Required</h4>
                                <span id="json-status">Valid JSON ✓</span>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-end py-6">
                        <a href="{{ route('license.index') }}" class="btn btn-light me-3">Cancel</a>
                        <button type="submit" class="btn btn-primary" id="submit-btn">
                            <i class="ki-duotone ki-check fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            Generate License
                        </button>
                    </div>
                </div>
            </div>

            <!-- Sidebar - Quick Templates -->
            <div class="col-xl-4">
                <div class="card mb-5" style="">
                    <div class="card-header">
                        <h3 class="card-title">Feature Templates</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-5">
                            <button type="button" class="btn btn-light-primary w-100 mb-3" data-template="trial">
                                <i class="ki-duotone ki-timer fs-2 me-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                                Trial License (14 days)
                            </button>
                            <button type="button" class="btn btn-light-info w-100 mb-3" data-template="personal">
                                <i class="ki-duotone ki-user fs-2 me-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                                Personal License
                            </button>
                            <button type="button" class="btn btn-light-success w-100 mb-3" data-template="professional">
                                <i class="ki-duotone ki-briefcase fs-2 me-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                Professional License
                            </button>
                            <button type="button" class="btn btn-light-warning w-100" data-template="enterprise">
                                <i class="ki-duotone ki-office-bag fs-2 me-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                                Enterprise License
                            </button>
                        </div>

                        <div class="separator my-5"></div>

                        <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed p-4">
                            <i class="ki-duotone ki-information-5 fs-2tx text-warning me-3">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                            <div class="d-flex flex-stack flex-grow-1">
                                <div class="fw-semibold">
                                    <div class="fs-6 text-gray-700">
                                        <strong>Important:</strong><br>
                                        • Features must be valid JSON<br>
                                        • Use lowercase for keys<br>
                                        • Boolean: true/false<br>
                                        • Numbers: no quotes
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('[data-control="select2"]').select2({
                placeholder: function() {
                    return $(this).data('placeholder');
                },
                allowClear: true
            });

            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();

            // Feature templates
            const templates = {
                trial: {
                    features: {
                        "UserLimit": 1,
                        "sso": false,
                        "api_access": false,
                        "storage_gb": 5,
                        "white_label": false,
                        "priority_support": false,
                        "custom_branding": false,
                        "advanced_analytics": false,
                        "export_data": false,
                        "mobile_app": true
                    },
                    max_devices: 1,
                    expiry_days: 14,
                    license_type: 'TRIAL'
                },
                personal: {
                    features: {
                        "UserLimit": 3,
                        "sso": false,
                        "api_access": true,
                        "storage_gb": 25,
                        "white_label": false,
                        "priority_support": false,
                        "custom_branding": false,
                        "advanced_analytics": false,
                        "export_data": true,
                        "mobile_app": true
                    },
                    max_devices: 3,
                    expiry_days: 365,
                    license_type: 'PERSONAL'
                },
                professional: {
                    features: {
                        "UserLimit": 10,
                        "sso": true,
                        "api_access": true,
                        "storage_gb": 100,
                        "white_label": true,
                        "priority_support": true,
                        "custom_branding": true,
                        "advanced_analytics": true,
                        "export_data": true,
                        "mobile_app": true
                    },
                    max_devices: 10,
                    expiry_days: 365,
                    license_type: 'PROFESSIONAL'
                },
                enterprise: {
                    features: {
                        "UserLimit": -1,
                        "sso": true,
                        "api_access": true,
                        "storage_gb": -1,
                        "white_label": true,
                        "priority_support": true,
                        "custom_branding": true,
                        "advanced_analytics": true,
                        "export_data": true,
                        "mobile_app": true,
                        "dedicated_support": true,
                        "custom_integration": true,
                        "audit_logs": true
                    },
                    max_devices: 999,
                    expiry_days: 365,
                    license_type: 'ENTERPRISE'
                }
            };

            // Template button click
            $('[data-template]').click(function() {
                const templateName = $(this).data('template');
                const template = templates[templateName];

                if (template) {
                    // Set features JSON
                    $('#features').val(JSON.stringify(template.features, null, 4));

                    // Set other fields
                    $('#max_devices').val(template.max_devices);
                    $('#license_type').val(template.license_type).trigger('change');

                    // Calculate expiry date
                    const issueDate = new Date($('#issue_date').val());
                    const expiryDate = new Date(issueDate);
                    expiryDate.setDate(expiryDate.getDate() + template.expiry_days);
                    $('#expiry_date').val(expiryDate.toISOString().split('T')[0]);

                    // Validate JSON
                    validateJSON();

                    // Show success message
                    toastr.success(
                        `${templateName.charAt(0).toUpperCase() + templateName.slice(1)} template loaded successfully!`
                    );
                }
            });

            // JSON validation
            function validateJSON() {
                const jsonText = $('#features').val();
                const statusEl = $('#json-status');
                const errorEl = $('#json-error');
                const submitBtn = $('#submit-btn');

                try {
                    JSON.parse(jsonText);
                    statusEl.html('<span class="text-success">✓ Valid JSON</span>');
                    errorEl.hide();
                    submitBtn.prop('disabled', false);
                    return true;
                } catch (e) {
                    statusEl.html('<span class="text-danger">✗ Invalid JSON</span>');
                    errorEl.text('JSON Error: ' + e.message).show();
                    submitBtn.prop('disabled', true);
                    return false;
                }
            }

            // Validate JSON on input
            $('#features').on('input', function() {
                validateJSON();
            });

            // Validate on page load
            validateJSON();

            // Date validation
            $('#issue_date, #expiry_date').on('change', function() {
                const issueDate = new Date($('#issue_date').val());
                const expiryDate = new Date($('#expiry_date').val());

                if (expiryDate <= issueDate) {
                    toastr.warning('Expiry date must be after issue date');
                    $('#expiry_date').val('');
                }
            });

            // Form submission validation
            $('#license-form').on('submit', function(e) {
                if (!validateJSON()) {
                    e.preventDefault();
                    toastr.error('Please fix the JSON errors before submitting');
                    return false;
                }

                // Show loading state
                $('#submit-btn').prop('disabled', true).html(`
                    <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                    Generating License...
                `);
            });

            // Auto-format JSON on blur
            $('#features').on('blur', function() {
                try {
                    const json = JSON.parse($(this).val());
                    $(this).val(JSON.stringify(json, null, 4));
                } catch (e) {
                    // Invalid JSON, don't format
                }
            });
        });
    </script>
@endpush
