<a href="#" class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm" data-kt-menu-trigger="click"
    data-kt-menu-placement="bottom-end">
    Actions
    <i class="ki-duotone ki-down fs-5 ms-1"></i>
</a>
<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-175px py-4"
    data-kt-menu="true">
    <div class="menu-item px-3">
        <a href="#" class="menu-link px-3" action-row-table-1="view" data-id="{{ $row->id }}"
            data-name="{{ $row->name }}" data-status="{{ $row->is_active ? '1' : '0' }}"
            data-key-size="{{ $row->key_size }}" data-generated="{{ $row->generated_at->format('M d, Y H:i') }}">
            <i class="ki-duotone ki-eye fs-5 me-2">
                <span class="path1"></span>
                <span class="path2"></span>
                <span class="path3"></span>
            </i>View Details
        </a>
    </div>

    @if (!$row->is_active)
        <div class="menu-item px-3">
            <a href="#" class="menu-link px-3" action-row-table-1="activate"
                data-id="{{ route('rsakey.activate', $row->id) }}">
                <i class="ki-duotone ki-check-circle fs-5 me-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>Set as Active
            </a>
        </div>
    @endif

    <div class="menu-item px-3">
        <a href="#" class="menu-link px-3 text-danger" action-row-table-1="delete"
            data-id="{{ route('rsakey.destroy', $row->id) }}">
            <i class="ki-duotone ki-trash fs-5 me-2">
                <span class="path1"></span>
                <span class="path2"></span>
                <span class="path3"></span>
                <span class="path4"></span>
                <span class="path5"></span>
            </i>Delete
        </a>
    </div>
</div>
