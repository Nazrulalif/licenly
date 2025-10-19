@extends('layouts.app')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item text-muted">Dashboard</li>
@endsection

@section('content')
    <div class="row g-5 g-xl-10">
        <!-- Total Customers Card -->
        <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 mb-xl-10 h-100">
            <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-md-50 mb-5 mb-xl-10"
                style="background-color: #F1416C;background-image:url('{{ asset('assets/media/patterns/vector-1.png') }}')">
                <div class="card-header pt-5">
                    <div class="card-title d-flex flex-column">
                        <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ $totalCustomers }}</span>
                        <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Total Customers</span>
                    </div>
                </div>
                <div class="card-body d-flex align-items-end pt-0">
                    <div class="d-flex align-items-center flex-column mt-3 w-100">
                        <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-75 w-100 mt-auto mb-2">
                            <span>Active Customers</span>
                            <span>{{ $activeCustomers }}</span>
                        </div>
                        <div class="h-8px mx-3 w-100 bg-white bg-opacity-50 rounded">
                            <div class="bg-white rounded h-8px" role="progressbar"
                                style="width: {{ $totalCustomers > 0 ? ($activeCustomers / $totalCustomers) * 100 : 0 }}%;"
                                aria-valuenow="{{ $activeCustomers }}" aria-valuemin="0"
                                aria-valuemax="{{ $totalCustomers }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Licenses Card -->
        <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 mb-xl-10 h-100">
            <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-md-50 mb-5 mb-xl-10"
                style="background-color: #7239EA;background-image:url('{{ asset('assets/media/patterns/vector-1.png') }}')">
                <div class="card-header pt-5">
                    <div class="card-title d-flex flex-column">
                        <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ $totalLicenses }}</span>
                        <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Total Licenses</span>
                    </div>
                </div>
                <div class="card-body d-flex align-items-end pt-0">
                    <div class="d-flex align-items-center flex-column mt-3 w-100">
                        <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-75 w-100 mt-auto mb-2">
                            <span>Active</span>
                            <span>{{ $activeLicenses }}</span>
                        </div>
                        <div class="h-8px mx-3 w-100 bg-white bg-opacity-50 rounded">
                            <div class="bg-white rounded h-8px" role="progressbar"
                                style="width: {{ $totalLicenses > 0 ? ($activeLicenses / $totalLicenses) * 100 : 0 }}%;"
                                aria-valuenow="{{ $activeLicenses }}" aria-valuemin="0"
                                aria-valuemax="{{ $totalLicenses }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Expired Licenses Card -->
        <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 mb-xl-10 h-100">
            <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-md-50 mb-5 mb-xl-10"
                style="background-color: #FFC700;background-image:url('{{ asset('assets/media/patterns/vector-1.png') }}')">
                <div class="card-header pt-5">
                    <div class="card-title d-flex flex-column">
                        <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ $expiredLicenses }}</span>
                        <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Expired Licenses</span>
                    </div>
                </div>
                <div class="card-body d-flex align-items-end pt-0">
                    <div class="d-flex align-items-center flex-column mt-3 w-100">
                        <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-75 w-100 mt-auto mb-2">
                            <span>Expiring Soon (30d)</span>
                            <span>{{ $expiringSoon }}</span>
                        </div>
                        <div class="h-8px mx-3 w-100 bg-white bg-opacity-50 rounded">
                            <div class="bg-white rounded h-8px" role="progressbar"
                                style="width: {{ $totalLicenses > 0 ? ($expiringSoon / $totalLicenses) * 100 : 0 }}%;"
                                aria-valuenow="{{ $expiringSoon }}" aria-valuemin="0"
                                aria-valuemax="{{ $totalLicenses }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RSA Keys Card -->
        <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 mb-xl-10 h-100">
            <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-md-50 mb-5 mb-xl-10"
                style="background-color: #50CD89;background-image:url('{{ asset('assets/media/patterns/vector-1.png') }}')">
                <div class="card-header pt-5">
                    <div class="card-title d-flex flex-column">
                        <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ $totalRsaKeys }}</span>
                        <span class="text-white opacity-75 pt-1 fw-semibold fs-6">RSA Keys</span>
                    </div>
                </div>
                <div class="card-body d-flex align-items-end pt-0">
                    <div class="d-flex align-items-center flex-column mt-3 w-100">
                        <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-75 w-100 mt-auto mb-2">
                            <span>Active Key</span>
                            <span>{{ $activeRsaKey ? 1 : 0 }}</span>
                        </div>
                        <div class="h-8px mx-3 w-100 bg-white bg-opacity-50 rounded">
                            <div class="bg-white rounded h-8px" role="progressbar"
                                style="width: {{ $activeRsaKey ? 100 : 0 }}%;" aria-valuenow="1" aria-valuemin="0"
                                aria-valuemax="1">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Row -->
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        <!-- License Expiration Alerts -->
        <div class="col-xl-4">
            <div class="card card-flush h-xl-100">
                <div class="card-header pt-7">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-gray-800">License Expiration Alerts</span>
                        <span class="text-gray-500 mt-1 fw-semibold fs-6">Upcoming expirations</span>
                    </h3>
                </div>
                <div class="card-body pt-6">
                    <div class="d-flex align-items-center mb-7">
                        <div class="symbol symbol-50px me-5">
                            <span class="symbol-label bg-light-danger">
                                <i class="ki-duotone ki-calendar fs-2x text-danger">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <a href="#" class="text-gray-900 fw-bold text-hover-primary fs-6">Next 7 Days</a>
                            <span class="text-muted d-block fw-semibold">Urgent attention required</span>
                        </div>
                        <span class="badge badge-light-danger fs-5">{{ $expiringIn7Days }}</span>
                    </div>
                    <div class="d-flex align-items-center mb-7">
                        <div class="symbol symbol-50px me-5">
                            <span class="symbol-label bg-light-warning">
                                <i class="ki-duotone ki-calendar fs-2x text-warning">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <a href="#" class="text-gray-900 fw-bold text-hover-primary fs-6">Next 14 Days</a>
                            <span class="text-muted d-block fw-semibold">Should review soon</span>
                        </div>
                        <span class="badge badge-light-warning fs-5">{{ $expiringIn14Days }}</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-50px me-5">
                            <span class="symbol-label bg-light-info">
                                <i class="ki-duotone ki-calendar fs-2x text-info">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <a href="#" class="text-gray-900 fw-bold text-hover-primary fs-6">Next 30 Days</a>
                            <span class="text-muted d-block fw-semibold">Plan for renewal</span>
                        </div>
                        <span class="badge badge-light-info fs-5">{{ $expiringIn30Days }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- License by Type -->
        <div class="col-xl-4">
            <div class="card card-flush h-xl-100">
                <div class="card-header pt-7">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-gray-800">License Types</span>
                        <span class="text-gray-500 mt-1 fw-semibold fs-6">Distribution by type</span>
                    </h3>
                </div>
                <div class="card-body pt-6">
                    <div id="kt_license_by_type_chart" style="height: 250px;"></div>
                </div>
            </div>
        </div>

        <!-- License by Status -->
        <div class="col-xl-4">
            <div class="card card-flush h-xl-100">
                <div class="card-header pt-7">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-gray-800">License Status</span>
                        <span class="text-gray-500 mt-1 fw-semibold fs-6">Current status breakdown</span>
                    </h3>
                </div>
                <div class="card-body pt-6">
                    <div id="kt_license_by_status_chart" style="height: 250px;"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Recent Licenses Table -->
    <div class="row g-5 g-xl-10">
        <div class="col-xl-12">
            <div class="card card-flush h-xl-100">
                <div class="card-header pt-7">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-gray-800">Recent Licenses</span>
                        <span class="text-gray-500 mt-1 fw-semibold fs-6">Latest 10 licenses created</span>
                    </h3>
                </div>
                <div class="card-body pt-6">
                    <div class="table-responsive">
                        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                            <thead>
                                <tr class="fw-bold text-muted">
                                    <th class="min-w-150px">License ID</th>
                                    <th class="min-w-140px">Customer</th>
                                    <th class="min-w-120px">Type</th>
                                    <th class="min-w-120px">Status</th>
                                    <th class="min-w-100px">Issue Date</th>
                                    <th class="min-w-100px">Expiry Date</th>
                                    <th class="min-w-100px text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentLicenses as $license)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="d-flex justify-content-start flex-column">
                                                    <a href="#"
                                                        class="text-gray-900 fw-bold text-hover-primary fs-6">
                                                        {{ $license->license_id }}
                                                    </a>
                                                    <span class="text-muted fw-semibold text-muted d-block fs-7">
                                                        {{ $license->product_key }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="#"
                                                class="text-gray-900 fw-bold text-hover-primary d-block fs-6">
                                                {{ $license->customer->company_name ?? 'N/A' }}
                                            </a>
                                            <span class="text-muted fw-semibold text-muted d-block fs-7">
                                                {{ $license->customer->contact_name ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td>
                                            {!! $license->license_type_badge !!}
                                        </td>
                                        <td>
                                            {!! $license->status_badge !!}
                                        </td>
                                        <td>
                                            <span class="text-gray-900 fw-bold d-block fs-6">
                                                {{ $license->issue_date ? $license->issue_date->format('M d, Y') : 'N/A' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-gray-900 fw-bold d-block fs-6">
                                                {{ $license->expiry_date ? $license->expiry_date->format('M d, Y') : 'N/A' }}
                                            </span>
                                            @if ($license->status === 'ACTIVE' && $license->expiry_date)
                                                <span class="text-muted fw-semibold d-block fs-7">
                                                    {{ $license->days_remaining }} days left
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('license.show', $license->id) }}"
                                                class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary">
                                                <i class="ki-duotone ki-eye fs-2">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                </i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-10">
                                            No licenses found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script src="{{ asset('assets/plugins/custom/apexcharts/apexcharts.bundle.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            /** ================================
             *  Utility Functions
             *  ================================ */
            function hasData(dataObj) {
                return Object.keys(dataObj).length > 0 && Object.values(dataObj).some(v => v > 0);
            }

            function showEmptyMessage(selector, message = 'No data available') {
                const el = document.querySelector(selector);
                if (el) el.innerHTML = `<div class="text-center text-muted pt-5">${message}</div>`;
            }

            /** ================================
             *  License by Type Chart
             *  ================================ */
            const licenseByTypeData = @json($licensesByType);
            const typeLabels = Object.keys(licenseByTypeData);
            const typeValues = Object.values(licenseByTypeData);
            const typeSelector = "#kt_license_by_type_chart";

            if (document.querySelector(typeSelector)) {
                if (!hasData(licenseByTypeData)) {
                    showEmptyMessage(typeSelector);
                } else {
                    const typeOptions = {
                        series: typeValues,
                        chart: {
                            type: 'donut',
                            height: 250
                        },
                        labels: typeLabels,
                        colors: ['#7239EA', '#50CD89', '#FFC700', '#F1416C', '#009EF7'],
                        legend: {
                            position: 'bottom'
                        },
                        plotOptions: {
                            pie: {
                                donut: {
                                    size: '65%'
                                }
                            }
                        },
                        dataLabels: {
                            enabled: true,
                            style: {
                                fontSize: '12px'
                            }
                        },
                        tooltip: {
                            y: {
                                formatter: val => val.toLocaleString()
                            }
                        }
                    };
                    window.typeChart = new ApexCharts(document.querySelector(typeSelector), typeOptions);
                    window.typeChart.render();
                }
            }

            /** ================================
             *  License by Status Chart
             *  ================================ */
            const licenseByStatusData = @json($licensesByStatus);
            const statusLabels = Object.keys(licenseByStatusData);
            const statusValues = Object.values(licenseByStatusData);
            const statusSelector = "#kt_license_by_status_chart";

            if (document.querySelector(statusSelector)) {
                if (!hasData(licenseByStatusData)) {
                    showEmptyMessage(statusSelector);
                } else {
                    const statusOptions = {
                        series: statusValues,
                        chart: {
                            type: 'donut',
                            height: 250
                        },
                        labels: statusLabels,
                        colors: ['#50CD89', '#F1416C', '#FFC700', '#009EF7'],
                        legend: {
                            position: 'bottom'
                        },
                        plotOptions: {
                            pie: {
                                donut: {
                                    size: '65%'
                                }
                            }
                        },
                        dataLabels: {
                            enabled: true,
                            style: {
                                fontSize: '12px'
                            }
                        },
                        tooltip: {
                            y: {
                                formatter: val => val.toLocaleString()
                            }
                        }
                    };
                    window.statusChart = new ApexCharts(document.querySelector(statusSelector), statusOptions);
                    window.statusChart.render();
                }
            }

            /** ================================
             *  License Trend Chart (Last 6 months)
             *  ================================ */
            const monthlyLicensesData = @json($monthlyLicenses);
            const trendLabels = monthlyLicensesData.map(item => item.month);
            const trendValues = monthlyLicensesData.map(item => item.total);
            const trendSelector = "#kt_license_trend_chart";

            if (document.querySelector(trendSelector)) {
                if (!monthlyLicensesData.length) {
                    showEmptyMessage(trendSelector);
                } else {
                    const trendOptions = {
                        series: [{
                            name: 'Licenses Created',
                            data: trendValues
                        }],
                        chart: {
                            type: 'area',
                            height: 350,
                            toolbar: {
                                show: false
                            }
                        },
                        dataLabels: {
                            enabled: false
                        },
                        stroke: {
                            curve: 'smooth',
                            width: 3
                        },
                        colors: ['#7239EA'],
                        fill: {
                            type: 'gradient',
                            gradient: {
                                shadeIntensity: 1,
                                opacityFrom: 0.4,
                                opacityTo: 0.1,
                                stops: [0, 90, 100]
                            }
                        },
                        xaxis: {
                            categories: trendLabels,
                            labels: {
                                style: {
                                    colors: '#a1a5b7',
                                    fontSize: '12px'
                                }
                            },
                            axisBorder: {
                                show: false
                            },
                            axisTicks: {
                                show: false
                            }
                        },
                        yaxis: {
                            labels: {
                                style: {
                                    colors: '#a1a5b7',
                                    fontSize: '12px'
                                }
                            },
                            min: 0
                        },
                        grid: {
                            borderColor: '#e7e7e7',
                            strokeDashArray: 4
                        },
                        tooltip: {
                            theme: 'light',
                            y: {
                                formatter: val => val.toLocaleString()
                            }
                        }
                    };
                    window.trendChart = new ApexCharts(document.querySelector(trendSelector), trendOptions);
                    window.trendChart.render();
                }
            }

            /** ================================
             *  Responsive Resize
             *  ================================ */
            window.addEventListener('resize', () => {
                clearTimeout(window.__resizeTimer);
                window.__resizeTimer = setTimeout(() => {
                    if (window.typeChart) window.typeChart.render();
                    if (window.statusChart) window.statusChart.render();
                    if (window.trendChart) window.trendChart.render();
                }, 250);
            });
        });
    </script>
@endpush
