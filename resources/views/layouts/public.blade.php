<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <title>Cirebon Kita | Portal Berita & Lowongan Kerja</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />

    <!-- Global Theme Styles -->
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />

    <style>
        .header-fixed {
            z-index: 99;
        }

        .hero-section {
            background-size: cover;
            background-position: center;
            min-height: 400px;
            position: relative;
        }

        .hero-overlay {
            background: rgba(0, 0, 0, 0.5);
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .job-card:hover {
            transform: translateY(-5px);
            transition: 0.3s;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed page-loading">

    <!-- Header -->
    <div id="kt_header" class="header header-fixed bg-white">
        <div class="container d-flex align-items-stretch justify-content-between">
            <div class="header-menu-wrapper header-menu-wrapper-left" id="kt_header_menu_wrapper">
                <div class="header-logo">
                    <a href="{{ url('/') }}">
                        <h2 class="text-dark font-weight-bolder mt-3">Cirebon<span class="text-primary">Kita</span></h2>
                    </a>
                </div>
                <div id="kt_header_menu" class="header-menu header-menu-mobile header-menu-layout-default">
                    <ul class="menu-nav">
                        <li class="menu-item {{ Request::is('/') ? 'menu-item-active' : '' }}"><a
                                href="{{ url('/') }}" class="menu-link"><span class="menu-text">Home</span></a>
                        </li>
                        <li class="menu-item {{ Request::is('berita') ? 'menu-item-active' : '' }}"><a
                                href="{{ route('news.index') }}" class="menu-link"><span
                                    class="menu-text">Berita</span></a></li>
                        <li class="menu-item {{ Request::is('lowongan') ? 'menu-item-active' : '' }}"><a
                                href="{{ route('jobs.index') }}" class="menu-link"><span
                                    class="menu-text">Lowongan</span></a></li>
                        <li class="menu-item {{ Request::is('perusahaan') ? 'menu-item-active' : '' }}"><a
                                href="{{ route('companies.index') }}" class="menu-link"><span
                                    class="menu-text">Perusahaan</span></a></li>
                        <li class="menu-item {{ Request::is('tentang') ? 'menu-item-active' : '' }}"><a
                                href="{{ route('pages.about') }}" class="menu-link"><span
                                    class="menu-text">Tentang</span></a></li>
                    </ul>
                </div>
            </div>
            <div class="topbar">
                <div class="topbar-item">
                    <div class="btn btn-icon btn-icon-mobile w-auto btn-clean d-flex align-items-center btn-lg px-2">
                        <span class="text-dark-50 font-weight-bolder font-size-base d-none d-md-inline mr-3">Masuk /
                            Daftar</span>
                        <span class="symbol symbol-35 symbol-light-primary">
                            <span class="symbol-label font-size-h5 font-weight-bold">A</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="d-flex flex-column flex-root">
        <div class="d-flex flex-row flex-column-fluid page">
            <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
                <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                    @yield('content')
                </div>

                <!-- Footer -->
                <div class="footer bg-white py-4 d-flex flex-lg-column" id="kt_footer">
                    <div class="container d-flex flex-column flex-md-row align-items-center justify-content-between">
                        <div class="text-dark order-2 order-md-1">
                            <span class="text-muted font-weight-bold mr-2">2026©</span>
                            <a href="#" target="_blank" class="text-dark-75 text-hover-primary">Cirebon Kita</a>
                        </div>
                        <div class="nav nav-dark order-1 order-md-2">
                            <a href="#" target="_blank" class="nav-link pr-3 pl-0">About</a>
                            <a href="#" target="_blank" class="nav-link px-3">Team</a>
                            <a href="#" target="_blank" class="nav-link pl-3 pr-0">Contact</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Global Config -->
    <script>
        var KTAppSettings = {
            "breakpoints": {
                "sm": 576,
                "md": 768,
                "lg": 992,
                "xl": 1200,
                "xxl": 1400
            },
            "colors": {
                "theme": {
                    "base": {
                        "white": "#ffffff",
                        "primary": "#3699FF",
                        "secondary": "#E5EAEE",
                        "success": "#1BC5BD",
                        "info": "#8950FC",
                        "warning": "#FFA800",
                        "danger": "#F64E60",
                        "light": "#E4E6EF",
                        "dark": "#181C32"
                    },
                    "light": {
                        "white": "#ffffff",
                        "primary": "#E1F0FF",
                        "secondary": "#EBEDF3",
                        "success": "#C9F7F5",
                        "info": "#EEE5FF",
                        "warning": "#FFF4DE",
                        "danger": "#FFE2E5",
                        "light": "#F3F6F9",
                        "dark": "#D6D6E0"
                    },
                    "inverse": {
                        "white": "#ffffff",
                        "primary": "#ffffff",
                        "secondary": "#3F4254",
                        "success": "#ffffff",
                        "info": "#ffffff",
                        "warning": "#ffffff",
                        "danger": "#ffffff",
                        "light": "#464E5F",
                        "dark": "#ffffff"
                    }
                },
                "gray": {
                    "gray-100": "#F3F6F9",
                    "gray-200": "#EBEDF3",
                    "gray-300": "#E4E6EF",
                    "gray-400": "#D1D3E0",
                    "gray-500": "#B5B5C3",
                    "gray-600": "#7E8299",
                    "gray-700": "#5E6278",
                    "gray-800": "#3F4254",
                    "gray-900": "#181C32"
                }
            },
            "font-family": "Poppins"
        };
    </script>

    <!-- Global Theme Bundle -->
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/plugins/custom/prismjs/prismjs.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>

    <!-- Page Scripts -->
    @yield('scripts')
    <script src="{{ asset('js/ajax.js') }}"></script>
    <script src="{{ asset('js/auth.js') }}"></script>
</body>

</html>
