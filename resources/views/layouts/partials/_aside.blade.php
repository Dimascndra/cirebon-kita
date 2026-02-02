<!--begin::Aside-->
<div class="aside aside-left  aside-fixed  d-flex flex-column flex-row-auto" id="kt_aside">

    <!--begin::Brand-->
    <div class="brand flex-column-auto " id="kt_brand">

        <!--begin::Logo-->
        <a href="/dashboard" class="brand-logo">
            @if (!empty($isLight) && $isLight === true)
                <img alt="Logo" src="assets/media/logos/logo-dark.png" />
            @else
                <img alt="Logo" src="assets/media/logos/logo-light.png" />
            @endif
        </a>

        <!--end::Logo-->

        <!--begin::Toggle-->
        <button class="brand-toggle btn btn-sm px-0" id="kt_aside_toggle">
            <span class="svg-icon svg-icon svg-icon-xl">

                <!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Angle-double-left.svg-->
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                    height="24px" viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <polygon points="0 0 24 0 24 24 0 24" />
                        <path
                            d="M5.29288961,6.70710318 C4.90236532,6.31657888 4.90236532,5.68341391 5.29288961,5.29288961 C5.68341391,4.90236532 6.31657888,4.90236532 6.70710318,5.29288961 L12.7071032,11.2928896 C13.0856821,11.6714686 13.0989277,12.281055 12.7371505,12.675721 L7.23715054,18.675721 C6.86395813,19.08284 6.23139076,19.1103429 5.82427177,18.7371505 C5.41715278,18.3639581 5.38964985,17.7313908 5.76284226,17.3242718 L10.6158586,12.0300721 L5.29288961,6.70710318 Z"
                            fill="#000000" fill-rule="nonzero"
                            transform="translate(8.999997, 11.999999) scale(-1, 1) translate(-8.999997, -11.999999) " />
                        <path
                            d="M10.7071009,15.7071068 C10.3165766,16.0976311 9.68341162,16.0976311 9.29288733,15.7071068 C8.90236304,15.3165825 8.90236304,14.6834175 9.29288733,14.2928932 L15.2928873,8.29289322 C15.6714663,7.91431428 16.2810527,7.90106866 16.6757187,8.26284586 L22.6757187,13.7628459 C23.0828377,14.1360383 23.1103407,14.7686056 22.7371482,15.1757246 C22.3639558,15.5828436 21.7313885,15.6103465 21.3242695,15.2371541 L16.0300699,10.3841378 L10.7071009,15.7071068 Z"
                            fill="#000000" fill-rule="nonzero" opacity="0.3"
                            transform="translate(15.999997, 11.999999) scale(-1, 1) rotate(-270.000000) translate(-15.999997, -11.999999) " />
                    </g>
                </svg>

                <!--end::Svg Icon-->
            </span>
        </button>

        <!--end::Toolbar-->
    </div>

    <!--end::Brand-->

    <!--begin::Aside Menu-->
    <div class="aside-menu-wrapper flex-column-fluid" id="kt_aside_menu_wrapper">

        <!--begin::Menu Container-->
        <div id="kt_aside_menu" class="aside-menu my-4 " data-menu-vertical="1" data-menu-scroll="1"
            data-menu-dropdown-timeout="500">

            <!--begin::Menu Nav-->
            <ul class="menu-nav">
                {{-- Dashboard --}}
                <li class="menu-item {{ request()->routeIs('admin.dashboard') ? 'menu-item-active' : '' }}"
                    aria-haspopup="true">
                    <a href="{{ route('admin.dashboard') }}" class="menu-link">
                        <span class="svg-icon menu-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24" />
                                    <path
                                        d="M4,4 L11,4 C11.5522847,4 12,4.44771525 12,5 L12,11 C12,11.5522847 11.5522847,12 11,12 L4,12 C3.44771525,12 3,11.5522847 3,11 L3,5 C3,4.44771525 3.44771525,4 4,4 Z M4,13 L11,13 C11.5522847,13 12,13.4477153 12,14 L12,20 C12,20.5522847 11.5522847,21 11,21 L4,21 C3.44771525,21 3,20.5522847 3,20 L3,14 C3,13.4477153 3.44771525,13 4,13 Z M13,4 L20,4 C20.5522847,4 21,4.44771525 21,5 L21,11 C21,11.5522847 20.5522847,12 20,12 L13,12 C12.4477153,12 12,11.5522847 12,11 L12,5 C12,4.44771525 12.4477153,4 13,4 Z M13,13 L20,13 C20.5522847,13 21,13.4477153 21,14 L21,20 C21,20.5522847 20.5522847,21 20,21 L13,21 C12.4477153,21 12,20.5522847 12,20 L12,14 C12,13.4477153 12.4477153,13 13,13 Z"
                                        fill="#000000" opacity="0.3" />
                                </g>
                            </svg>
                        </span>
                        <span class="menu-text">Dashboard</span>
                    </a>
                </li>

                {{-- Menu Section --}}
                @canany(['user-list', 'role-list', 'ad-list'])
                    <li class="menu-section">
                        <h4 class="menu-text">Management</h4>
                        <i class="menu-icon ki ki-bold-more-hor icon-md"></i>
                    </li>
                @endcanany

                {{-- Users --}}
                @can('user-list')
                    <li class="menu-item {{ request()->routeIs('admin.users.*') ? 'menu-item-active' : '' }}"
                        aria-haspopup="true">
                        <a href="{{ route('admin.users.index') }}" class="menu-link">
                            <span class="svg-icon menu-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <polygon points="0 0 24 0 24 24 0 24" />
                                        <path
                                            d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z"
                                            fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                        <path
                                            d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z"
                                            fill="#000000" fill-rule="nonzero" />
                                    </g>
                                </svg>
                            </span>
                            <span class="menu-text">Users</span>
                        </a>
                    </li>
                @endcan

                {{-- Roles --}}
                @can('role-list')
                    <li class="menu-item {{ request()->routeIs('admin.roles.*') ? 'menu-item-active' : '' }}"
                        aria-haspopup="true">
                        <a href="{{ route('admin.roles.index') }}" class="menu-link">
                            <span class="svg-icon menu-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24" />
                                        <path
                                            d="M18,2 L20,2 C21.6568542,2 23,3.34314575 23,5 L23,19 C23,20.6568542 21.6568542,22 20,22 L18,22 L18,2 Z"
                                            fill="#000000" opacity="0.3" />
                                        <path
                                            d="M5,2 L17,2 C18.6568542,2 20,3.34314575 20,5 L20,19 C20,20.6568542 18.6568542,22 17,22 L5,22 C4.44771525,22 4,21.5522847 4,21 L4,3 C4,2.44771525 4.44771525,2 5,2 Z M12,11 C13.1045695,11 14,10.1045695 14,9 C14,7.8954305 13.1045695,7 12,7 C10.8954305,7 10,7.8954305 10,9 C10,10.1045695 10.8954305,11 12,11 Z M7.00036205,16.4995035 C6.98863236,16.6619875 7.26484009,17 7.4041679,17 C11.463736,17 14.5228466,17 16.5815,17 C16.9988413,17 17,16.6359896 17,16.4995035 C17,14.7602746 15.683488,13 12,13 C8.316512,13 7.00036205,14.7602746 7.00036205,16.4995035 Z"
                                            fill="#000000" />
                                    </g>
                                </svg>
                            </span>
                            <span class="menu-text">Roles & Permissions</span>
                        </a>
                    </li>
                @endcan

                {{-- Banner Ads --}}
                @can('ad-list')
                    <li class="menu-item {{ request()->routeIs('admin.ads.*') ? 'menu-item-active' : '' }}"
                        aria-haspopup="true">
                        <a href="{{ route('admin.ads.index') }}" class="menu-link">
                            <span class="svg-icon menu-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"
                                    viewBox="0 0 24 24">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24" />
                                        <path
                                            d="M4,4 L20,4 C21.1045695,4 22,4.8954305 22,6 L22,18 C22,19.1045695 21.1045695,20 20,20 L4,20 C2.8954305,20 2,19.1045695 2,18 L2,6 C2,4.8954305 2.8954305,4 4,4 Z"
                                            fill="#000000" opacity="0.3" />
                                        <path
                                            d="M18.5,13 C19.3284271,13 20,13.6715729 20,14.5 C20,15.3284271 19.3284271,16 18.5,16 C17.6715729,16 17,15.3284271 17,14.5 C17,13.6715729 17.6715729,13 18.5,13 Z M14.5,13 C15.3284271,13 16,13.6715729 16,14.5 C16,15.3284271 15.3284271,16 14.5,16 C13.6715729,16 13,15.3284271 13,14.5 C13,13.6715729 13.6715729,13 14.5,13 Z M10.5,13 C11.3284271,13 12,13.6715729 12,14.5 C12,15.3284271 11.3284271,16 10.5,16 C9.67157288,16 9,15.3284271 9,14.5 C9,13.6715729 9.67157288,13 10.5,13 Z M6.5,13 C7.32842712,13 8,13.6715729 8,14.5 C8,15.3284271 7.32842712,16 6.5,16 C5.67157288,16 5,15.3284271 5,14.5 C5,13.6715729 5.67157288,13 6.5,13 Z"
                                            fill="#000000" />
                                    </g>
                                </svg>
                            </span>
                            <span class="menu-text">Banner Ads</span>
                        </a>
                    </li>
                @endcan
            </ul>

            <ul class="menu-nav">
                @canany(['news-list', 'job-list'])
                    <li class="menu-section">
                        <h4 class="menu-text">Content</h4>
                        <i class="menu-icon ki ki-bold-more-hor icon-md"></i>
                    </li>
                @endcanany

                {{-- News --}}
                @can('news-list')
                    <li class="menu-item {{ request()->routeIs('admin.news.*') ? 'menu-item-active' : '' }}"
                        aria-haspopup="true">
                        <a href="{{ route('admin.news.index') }}" class="menu-link">
                            <span class="svg-icon menu-icon">
                                <i class="flaticon2-paper text-warning"></i>
                            </span>
                            <span class="menu-text">News & Articles</span>
                        </a>
                    </li>
                @endcan

                {{-- Jobs --}}
                @can('job-list')
                    <li class="menu-item {{ request()->routeIs('admin.jobs.*') ? 'menu-item-active' : '' }}"
                        aria-haspopup="true">
                        <a href="{{ route('admin.jobs.index') }}" class="menu-link">
                            <span class="svg-icon menu-icon">
                                <i class="flaticon2-contract text-success"></i>
                            </span>
                            <span class="menu-text">Jobs & Vacancies</span>
                        </a>
                    </li>
                @endcan
            </ul>

            <!--end::Menu Nav-->
        </div>

        <!--end::Menu Container-->
    </div>

    <!--end::Aside Menu-->
</div>

<!--end::Aside-->
