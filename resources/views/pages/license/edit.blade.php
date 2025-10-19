@extends('layouts.app')

@section('title', 'Extend License')

@section('page-title', 'Extend License')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('license.index') }}">Licenses</a></li>
    <li class="breadcrumb-item text-gray-900">Extend {{ $license->license_id }}</li>
@endsection

@section('content')

    <form action="{{ route('license.update', $license->id) }}" method="POST" id="license-form">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- Main Form -->
            <div class="col-xl-8">
                <!-- License Information (Read-Only) -->
                <div class="card mb-5">
                    <div class="card-header">
                        <h3 class="card-title">Current License Information</h3>
                        <div class="card-toolbar">
                            {!! $license->status_badge !!}
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- License ID -->
                            <div class="col-md-6 mb-7">
                                <label class="form-label fw-bold">License ID</label>
                                <div class="fs-5">{{ $license->license_id }}</div>
                            </div>

                            <!-- Product Key -->
                            <div class="col-md-6 mb-7">
                                <label class="form-label fw-bold">Product Key</label>
                                <div class="fs-6 font-monospace">{{ $license->product_key }}</div>
                            </div>

                            <!-- Customer -->
                            <div class="col-md-6 mb-7">
                                <label class="form-label fw-bold">Customer</label>
                                <div class="d-flex flex-column">
                                    <span class="fs-6">{{ $license->customer->company_name }}</span>
                                    <span class="text-muted fs-7">{{ $license->customer->email }}</span>
                                </div>
                            </div>

                            <!-- License Type -->
                            <div class="col-md-6 mb-7">
                                <label class="form-label fw-bold">License Type</label>
                                <div>{!! $license->license_type_badge !!}</div>
                            </div>

                            <!-- Max Devices -->
                            <div class="col-md-6 mb-7">
                                <label class="form-label fw-bold">Max Devices</label>
                                <div class="fs-6">{{ $license->max_devices }}</div>
                            </div>

                            <!-- Hardware ID -->
                            <div class="col-md-6 mb-7">
                                <label class="form-label fw-bold">Hardware ID</label>
                                <div class="fs-6">
                                    @if ($license->hardware_id)
                                        <span class="font-monospace">{{ $license->hardware_id }}</span>
                                        <span class="badge badge-light-warning ms-2">Hardware Bound</span>
                                    @else
                                        <span class="text-muted">Not bound to hardware</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Issue Date -->
                            <div class="col-md-6 mb-7">
                                <label class="form-label fw-bold">Issue Date</label>
                                <div class="fs-6">{{ $license->issue_date->format('M d, Y') }}</div>
                            </div>

                            <!-- Current Expiry Date -->
                            <div class="col-md-6 mb-7">
                                <label class="form-label fw-bold">Current Expiry Date</label>
                                <div class="d-flex flex-column">
                                    <span class="fs-6">{{ $license->expiry_date->format('M d, Y') }}</span>
                                    @if ($license->status === 'ACTIVE')
                                        <span class="text-muted fs-7">
                                            {{ $license->days_remaining }} days remaining
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Extend License Section -->
                <div class="card mb-5">
                    <div class="card-header bg-light-primary">
                        <h3 class="card-title text-primary">
                            <i class="ki-duotone ki-time fs-2 text-primary me-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                            Extend License Validity
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- New Expiry Date -->
                            <div class="col-12 mb-7">
                                <label class="required form-label">New Expiry Date</label>
                                <input type="date" name="expiry_date" id="expiry_date"
                                    class="form-control form-control-lg @error('expiry_date') is-invalid @enderror"
                                    value="{{ old('expiry_date', $license->expiry_date->addYear()->format('Y-m-d')) }}"
                                    min="{{ $license->issue_date->format('Y-m-d') }}" required />
                                @error('expiry_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    New expiry date must be after the issue date
                                    ({{ $license->issue_date->format('M d, Y') }})
                                </div>
                            </div>

                            <!-- Quick Extension Buttons -->
                            <div class="col-12 mb-7">
                                <label class="form-label fw-bold">Quick Extension</label>
                                <div class="d-flex flex-wrap gap-3">
                                    <button type="button" class="btn btn-light-primary btn-sm" data-extend="30">
                                        <i class="ki-duotone ki-calendar-add fs-4 me-1">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        +30 Days
                                    </button>
                                    <button type="button" class="btn btn-light-primary btn-sm" data-extend="90">
                                        <i class="ki-duotone ki-calendar-add fs-4 me-1">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        +90 Days
                                    </button>
                                    <button type="button" class="btn btn-light-success btn-sm" data-extend="180">
                                        <i class="ki-duotone ki-calendar-add fs-4 me-1">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        +6 Months
                                    </button>
                                    <button type="button" class="btn btn-light-success btn-sm" data-extend="365">
                                        <i class="ki-duotone ki-calendar-add fs-4 me-1">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        +1 Year
                                    </button>
                                    <button type="button" class="btn btn-light-warning btn-sm" data-extend="730">
                                        <i class="ki-duotone ki-calendar-add fs-4 me-1">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        +2 Years
                                    </button>
                                </div>
                                <div class="form-text">Click to quickly add time to the current expiry date</div>
                            </div>

                            <!-- Extension Summary -->
                            <div class="col-12">
                                <div class="alert alert-primary d-flex align-items-center p-5" id="extension-summary">
                                    <i class="ki-duotone ki-information-5 fs-2hx text-primary me-4">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                    <div class="d-flex flex-column">
                                        <h4 class="mb-1 text-dark">Extension Summary</h4>
                                        <span id="summary-text">Select a new expiry date to see extension details</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between py-6">
                        <a href="{{ route('license.index') }}" class="btn btn-light">Cancel</a>
                        <button type="submit" class="btn btn-primary" id="submit-btn">
                            <i class="ki-duotone ki-check fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            Extend License & Generate New PEM
                        </button>
                    </div>
                </div>

                <!-- License Features (Read-Only) -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">License Features</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @php
                                $features = is_array($license->features)
                                    ? $license->features
                                    : json_decode($license->features, true);
                            @endphp
                            @if ($features)
                                @foreach ($features as $key => $value)
                                    <div class="col-md-6 mb-4">
                                        <div class="d-flex align-items-center">
                                            @if (is_bool($value))
                                                @if ($value)
                                                    <i class="ki-duotone ki-check-circle fs-2 text-success me-3">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                @else
                                                    <i class="ki-duotone ki-cross-circle fs-2 text-danger me-3">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                @endif
                                            @else
                                                <i class="ki-duotone ki-information-5 fs-2 text-primary me-3">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                </i>
                                            @endif
                                            <div>
                                                <span class="fw-bold">{{ ucwords(str_replace('_', ' ', $key)) }}</span>
                                                <div class="text-muted fs-7">
                                                    @if (is_bool($value))
                                                        {{ $value ? 'Enabled' : 'Disabled' }}
                                                    @else
                                                        {{ $value === -1 ? 'Unlimited' : $value }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-12">
                                    <span class="text-muted">No features configured</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar - Information & Warnings -->
            <div class="col-xl-4">
                <!-- Important Notice -->
                <div class="card mb-5" style="">
                    <div class="card-header bg-light-warning">
                        <h3 class="card-title text-warning">
                            <i class="ki-duotone ki-shield-tick fs-2 text-warning me-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            Important Notice
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed p-4 mb-5">
                            <i class="ki-duotone ki-information-5 fs-2tx text-warning me-3">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                            <div class="d-flex flex-stack flex-grow-1">
                                <div class="fw-semibold">
                                    <div class="fs-6 text-gray-700">
                                        <strong>What happens when you extend:</strong><br><br>
                                        • New PEM file will be generated<br>
                                        • RSA signature will be updated<br>
                                        • License status remains unchanged<br>
                                        • Customer must use the new PEM file<br>
                                        • Old PEM file will be replaced
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="separator my-5"></div>

                        <!-- License Statistics -->
                        <div class="mb-5">
                            <h4 class="fw-bold mb-4">License Statistics</h4>
                            <div class="mb-3">
                                <span class="text-muted">Created:</span>
                                <span class="fw-bold d-block">{{ $license->created_at->format('M d, Y H:i') }}</span>
                            </div>
                            <div class="mb-3">
                                <span class="text-muted">Last Updated:</span>
                                <span class="fw-bold d-block">{{ $license->updated_at->format('M d, Y H:i') }}</span>
                            </div>
                            @if ($license->status === 'ACTIVE')
                                <div class="mb-3">
                                    <span class="text-muted">Days Remaining:</span>
                                    <span
                                        class="fw-bold d-block
                                        @if ($license->days_remaining <= 7) text-danger
                                        @elseif($license->days_remaining <= 30) text-warning
                                        @else text-success @endif">
                                        {{ $license->days_remaining }} days
                                    </span>
                                </div>
                            @endif
                            <div class="mb-3">
                                <span class="text-muted">RSA Key:</span>
                                <span class="fw-bold d-block">{{ $license->rsaKey->name }}</span>
                            </div>
                        </div>

                        <div class="separator my-5"></div>

                        <!-- Actions -->
                        <div class="d-grid gap-2">
                            <a href="{{ route('license.download', $license->id) }}" class="btn btn-light-primary">
                                <i class="ki-duotone ki-file-down fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                Download Current PEM
                            </a>
                            <a href="{{ route('license.index') }}" class="btn btn-light">
                                <i class="ki-duotone ki-arrow-left fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                Back to Licenses
                            </a>
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
            const currentExpiryDate = new Date('{{ $license->expiry_date->format('Y-m-d') }}');
            const issueDate = new Date('{{ $license->issue_date->format('Y-m-d') }}');

            // Quick extension buttons
            $('[data-extend]').click(function() {
                const days = parseInt($(this).data('extend'));
                const newDate = new Date(currentExpiryDate);
                newDate.setDate(newDate.getDate() + days);

                $('#expiry_date').val(newDate.toISOString().split('T')[0]);
                updateSummary();

                // Visual feedback
                $(this).addClass('active');
                setTimeout(() => {
                    $(this).removeClass('active');
                }, 200);

                toastr.success(`Extended by ${days} days`);
            });

            // Update extension summary
            function updateSummary() {
                const newExpiryDate = new Date($('#expiry_date').val());
                const today = new Date();
                today.setHours(0, 0, 0, 0);

                if (!$('#expiry_date').val() || newExpiryDate <= issueDate) {
                    $('#summary-text').html('Please select a valid expiry date');
                    $('#extension-summary').removeClass('alert-success').addClass('alert-primary');
                    return;
                }

                // Calculate differences
                const currentDiff = Math.ceil((currentExpiryDate - today) / (1000 * 60 * 60 * 24));
                const newDiff = Math.ceil((newExpiryDate - today) / (1000 * 60 * 60 * 24));
                const extensionDays = Math.ceil((newExpiryDate - currentExpiryDate) / (1000 * 60 * 60 * 24));

                // Format dates
                const currentExpiryFormatted = currentExpiryDate.toLocaleDateString('en-US', {
                    month: 'short',
                    day: 'numeric',
                    year: 'numeric'
                });
                const newExpiryFormatted = newExpiryDate.toLocaleDateString('en-US', {
                    month: 'short',
                    day: 'numeric',
                    year: 'numeric'
                });

                let summaryHTML = `
                    <strong>Current Expiry:</strong> ${currentExpiryFormatted}
                    ${currentDiff > 0 ? `(${currentDiff} days from today)` : '(Expired)'}
                    <br>
                    <strong>New Expiry:</strong> ${newExpiryFormatted} (${newDiff} days from today)
                    <br>
                    <strong class="text-success">Extension:</strong> +${extensionDays} days
                `;

                $('#summary-text').html(summaryHTML);
                $('#extension-summary').removeClass('alert-primary').addClass('alert-success');
            }

            // Update summary on date change
            $('#expiry_date').on('change', function() {
                updateSummary();
            });

            // Initialize summary
            updateSummary();

            // Date validation
            $('#expiry_date').on('change', function() {
                const newExpiryDate = new Date($(this).val());

                if (newExpiryDate <= issueDate) {
                    toastr.error(
                        'Expiry date must be after the issue date ({{ $license->issue_date->format('M d, Y') }})'
                    );
                    $(this).val('{{ $license->expiry_date->addYear()->format('Y-m-d') }}');
                    updateSummary();
                }
            });

            // Form submission
            $('#license-form').on('submit', function(e) {
                const newExpiryDate = new Date($('#expiry_date').val());

                if (newExpiryDate <= issueDate) {
                    e.preventDefault();
                    toastr.error('Please select a valid expiry date');
                    return false;
                }

                if (newExpiryDate <= currentExpiryDate) {
                    e.preventDefault();

                    Swal.fire({
                        title: 'Are you sure?',
                        html: 'The new expiry date is earlier than or equal to the current expiry date.<br>Do you want to continue?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, continue',
                        cancelButtonText: 'No, cancel',
                        customClass: {
                            confirmButton: 'btn btn-warning',
                            cancelButton: 'btn btn-light'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#submit-btn').prop('disabled', true).html(`
                                <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                Extending License...
                            `);
                            $('#license-form').off('submit').submit();
                        }
                    });
                    return false;
                }

                // Show loading state
                $('#submit-btn').prop('disabled', true).html(`
                    <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                    Extending License...
                `);
            });
        });
    </script>
@endpush
