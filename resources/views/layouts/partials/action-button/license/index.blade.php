<a href="#" class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm" data-kt-menu-trigger="click"
    data-kt-menu-placement="bottom-end">
    Actions
    <i class="ki-duotone ki-down fs-5 ms-1"></i>
</a>
<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-200px py-4"
    data-kt-menu="true">
    <div class="menu-item px-3">
        <a href="#" class="menu-link px-3" action-row-table-1="download"
            data-id="{{ route('license.download', $row->id) }}">
            <i class="ki-duotone ki-file-down fs-5 me-2">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>Download PEM
        </a>
    </div>

    @if ($row->canBeExtended())
        <div class="menu-item px-3">
            <a href="{{ route('license.edit', $row->id) }}" class="menu-link px-3">
                <i class="ki-duotone ki-time fs-5 me-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>Extend License
            </a>
        </div>
    @endif

    @if ($row->canBeRevoked())
        <div class="menu-item px-3">
            <a href="#" class="menu-link px-3" action-row-table-1="revoke"
                data-id="{{ route('license.revoke', $row->id) }}">
                <i class="ki-duotone ki-cross-circle fs-5 me-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>Revoke License
            </a>
        </div>
    @endif

    @if ($row->canBeDeleted())
        <div class="separator my-2"></div>

        <div class="menu-item px-3">
            <a href="#" class="menu-link px-3 text-danger" action-row-table-1="delete"
                data-id="{{ route('license.destroy', $row->id) }}">
                <i class="ki-duotone ki-trash fs-5 me-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                    <span class="path3"></span>
                    <span class="path4"></span>
                    <span class="path5"></span>
                </i>Delete
            </a>
        </div>
    @endif
</div>
