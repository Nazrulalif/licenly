@extends('layouts.app')

@section('title', 'RSA Keys Management')

@section('page-title', 'RSA Keys Management')

@section('breadcrumb')
    <li class="breadcrumb-item text-gray-900">RSA Keys</li>
@endsection

@section('content')

    <div class="card">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    <input type="text" filter-table-1="search" class="form-control form-control-solid w-250px ps-13"
                        placeholder="Search RSA Keys..." />
                </div>
            </div>
            <div class="card-toolbar">
                <div class="d-flex justify-content-end" toolbar-table-1="base">
                    <form action="{{ route('rsakey.generate') }}" method="POST" id="generate-key-form">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            <i class="ki-duotone ki-plus-square fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>Generate New Key Pair</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body table-responsive py-4">
            <!--begin::Table-->
            <table class="table-row-dashed fs-6 gy-5 table align-middle" id="table-1">
                <thead>
                    <tr class="text-muted fw-bold fs-7 text-uppercase gs-0 text-start">
                        <th class="w-10px pe-2">#</th>
                        <th class="min-w-200px">Key Name</th>
                        <th class="min-w-100px">Status</th>
                        <th class="min-w-100px">Key Size</th>
                        <th class="min-w-150px">Generated Date</th>
                        <th class="min-w-100px text-end">Actions</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                </tbody>
            </table>
        </div>
    </div>

    @include('pages.rsaKey.view-modal')

@endsection

@push('scripts')
    <script src="{{ asset('web/js/rsakey/index-table.js') }}"></script>
@endpush
