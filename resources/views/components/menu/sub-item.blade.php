@props([
    'route' => '',
    'activeRoute' => '',
    'url' => '',
    'title' => '',
    'active' => false,
])

@php
    $link = $route ? route($route) : $url;
    $isActive = $active ?: ($route && request()->routeIs($activeRoute));
@endphp

<div class="menu-item">
    <a class="menu-link {{ $isActive ? 'active' : '' }}"
        href="{{ $link }}"
        {{ $attributes }}>
        <span class="menu-bullet">
            <span class="bullet bullet-dot"></span>
        </span>
        <span class="menu-title">{{ $title }}</span>
    </a>
</div>
