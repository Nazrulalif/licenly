@extends('layouts.app')

@section('title', 'Users Management')

@section('page-title', 'Users Management')

@section('breadcrumb')
    <li class="breadcrumb-item text-gray-900">Users</li>
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
                        placeholder="Search" />
                </div>
            </div>
            <div class="card-toolbar">
                <div class="d-flex justify-content-end" toolbar-table-1="base">
                    <a href="{{ route('users.create') }}" class="btn btn-primary me-3">
                        <i class="ki-duotone ki-plus-square fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>Add User</a>
                </div>
                <!--begin::Group actions-->
                <div class="d-flex justify-content-end align-items-center d-none" toolbar-table-1="selected">
                    <div class="fw-bold me-5">
                        <span class="me-2" toolbar-table-1="count"></span>Selected
                    </div>
                    <button type="button" class="btn btn-danger" action-select-table-1="delete">Delete
                        Selected</button>
                </div>
                <!--end::Group actions-->
            </div>
        </div>
        <div class="card-body table-responsive py-4">
            <!--begin::Table-->
            <table class="table-row-dashed fs-6 gy-5 table align-middle" id="table-1">
                <thead>
                    <tr class="text-muted fw-bold fs-7 text-uppercase gs-0 text-start">
                        <th class="w-10px pe-2">
                            <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                <input class="form-check-input" type="checkbox" data-kt-check="true"
                                    data-kt-check-target="#table-1 .form-check-input" value="1" />
                            </div>
                        </th>
                        <th class="min-w-200px">User</th>
                        <th class="min-w-200px">Role</th>
                        <th class="min-w-200px">Status</th>
                        <th class="min-w-100px text-end">Actions</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                </tbody>
            </table>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('web/js/users/index-table.js') }}"></script>
@endpush
