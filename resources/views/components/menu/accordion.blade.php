@props([
    'title' => '',
    'icon' => '',
    'activeWhen' => '',
])

@php
    $isActive = $activeWhen ? request()->is($activeWhen) : false;
@endphp

<div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ $isActive ? 'here show' : '' }}">
    <span class="menu-link">
        @if($icon)
            <span class="menu-icon">
                <i class="{{ $icon }}">
                    @if(str_contains($icon, 'ki-duotone'))
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                        <span class="path4"></span>
                    @endif
                </i>
            </span>
        @endif
        <span class="menu-title">{{ $title }}</span>
        <span class="menu-arrow"></span>
    </span>
    <div class="menu-sub menu-sub-accordion">
        {{ $slot }}
    </div>
</div>
