<!--begin::Aside-->
<div id="kt_aside" class="aside py-9" data-kt-drawer="true" data-kt-drawer-name="aside"
    data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true"
    data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start"
    data-kt-drawer-toggle="#kt_aside_toggle">
    <!--begin::Brand-->
    <div class="aside-logo flex-column-auto px-9 mb-9" id="kt_aside_logo">

        <div class="logo-font fs-2hx">Licenly.</div>

        <!--begin::Logo-->
        {{-- <a href="{{ url('/') }}">
            <img alt="Logo" src="{{ asset('assets/media/logos/demo3.svg') }}"
                class="h-20px logo theme-light-show" />
            <img alt="Logo" src="{{ asset('assets/media/logos/demo3-dark.svg') }}"
                class="h-20px logo theme-dark-show" />
        </a> --}}
        <!--end::Logo-->
    </div>
    <!--end::Brand-->
    <!--begin::Aside menu-->
    <div class="aside-menu flex-column-fluid ps-5 pe-3 mb-9" id="kt_aside_menu">
        <!--begin::Aside Menu-->
        <div class="w-100 hover-scroll-overlay-y d-flex pe-3" id="kt_aside_menu_wrapper" data-kt-scroll="true"
            data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto"
            data-kt-scroll-dependencies="#kt_aside_logo, #kt_aside_footer"
            data-kt-scroll-wrappers="#kt_aside, #kt_aside_menu, #kt_aside_menu_wrapper" data-kt-scroll-offset="100">
            <!--begin::Menu-->
            <div class="menu menu-column menu-rounded menu-sub-indention menu-active-bg fw-semibold my-auto"
                id="#kt_aside_menu" data-kt-menu="true">

                <x-menu.item route="dashboard" title="Dashboards" icon="ki-duotone ki-element-11 fs-2"
                    id="menu-dashboard" activeRoute="dashboard" />

                <x-menu.item route="rsakey.index" title="RSA Keys" icon="ki-duotone ki-key fs-2" id="menu-rsa-key"
                    activeRoute="rsakey.*" />

                <x-menu.item route="customer.index" title="Customers" icon="ki-duotone ki-people fs-2"
                    id="menu-customers" activeRoute="customer.*" />

                <x-menu.item route="license.index" title="licenses" icon="ki-duotone ki-credit-cart fs-2"
                    id="menu-license" activeRoute="license.*" />

                <x-menu.accordion title="Control Panel" icon="ki-duotone ki-setting-4 fs-2" activeWhen="users*">
                    <x-menu.sub-item route="users.index" activeRoute="users.*" title="User Management" />
                </x-menu.accordion>

            </div>
            <!--end::Menu-->
        </div>
        <!--end::Aside Menu-->
    </div>
    <!--end::Aside menu-->
    <!--begin::Footer-->
    <div class="aside-footer flex-column-auto px-9" id="kt_aside_footer">
        <!--begin::User panel-->
        <div class="d-flex flex-stack">
            <!--begin::Wrapper-->
            <div class="d-flex align-items-center">
                <!--begin::Avatar-->
                <div class="symbol symbol-circle symbol-40px">
                    <img src="{{ Auth::user()->getAvatarAttribute() }}" alt="photo" />
                </div>
                <!--end::Avatar-->
                <!--begin::User info-->
                <div class="ms-2">
                    <!--begin::Name-->
                    <a href="#"
                        class="text-gray-800 text-hover-primary fs-6 fw-bold lh-1">{{ Auth::user()->name ?? 'User' }}</a>
                    <!--end::Name-->
                    <!--begin::Major-->
                    <span
                        class="text-muted fw-semibold d-block fs-7 lh-1">{{ Auth::user()->email ?? 'user@email.com' }}</span>
                    <!--end::Major-->
                </div>
                <!--end::User info-->
            </div>
            <!--end::Wrapper-->
            <!--begin::User menu-->
            <div class="ms-1">
                <div class="btn btn-sm btn-icon btn-active-color-primary position-relative me-n2"
                    data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-overflow="true"
                    data-kt-menu-placement="top-end">
                    <i class="ki-duotone ki-setting-2 fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </div>
                <!--begin::User account menu-->
                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px"
                    data-kt-menu="true">
                    <!--begin::Menu item-->
                    <div class="menu-item px-3">
                        <div class="menu-content d-flex align-items-center px-3">
                            <!--begin::Avatar-->
                            <div class="symbol symbol-50px me-5">
                                <img alt="Logo" src="{{ Auth::user()->getAvatarAttribute() }}" />
                            </div>
                            <!--end::Avatar-->
                            <!--begin::Username-->
                            <div class="d-flex flex-column">
                                <div class="fw-bold d-flex align-items-center fs-5">{{ Auth::user()->name ?? 'User' }}
                                </div>
                                <a href="#"
                                    class="fw-semibold text-muted text-hover-primary fs-7">{{ Auth::user()->email ?? 'user@email.com' }}</a>
                            </div>
                            <!--end::Username-->
                        </div>
                    </div>
                    <!--end::Menu item-->
                    <!--begin::Menu separator-->
                    <div class="separator my-2"></div>
                    <!--end::Menu separator-->
                    <!--begin::Menu item-->
                    <div class="menu-item px-5">
                        <a href="{{ route('users.edit', Auth::user()->id) }}" class="menu-link px-5">My Profile</a>
                    </div>
                    <!--end::Menu item-->
                    <!--begin::Menu item-->
                    <div class="menu-item px-5">
                        <form method="POST" action="{{ route('logout') }}" id="logout-form">
                            @csrf
                            <a href="{{ route('logout') }}" class="menu-link px-5"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Sign Out
                            </a>
                        </form>
                    </div>
                    <!--end::Menu item-->
                </div>
                <!--end::User account menu-->
            </div>
            <!--end::User menu-->
        </div>
        <!--end::User panel-->
    </div>
    <!--end::Footer-->
</div>
<!--end::Aside-->
