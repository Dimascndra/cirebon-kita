<!DOCTYPE html>

<!--
Template Name: Metronic - Bootstrap 4 HTML, React, Angular 11 & VueJS Admin Dashboard Theme
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: https://1.envato.market/EA4JP
Renew Support: https://1.envato.market/EA4JP
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<!--begin::Head-->

<head>
    <meta charset="utf-8" />
    <title>{{ trim($__env->yieldContent('title')) ?: getPageTitle() }} | Metronic729-Larvel12</title>

    <!-- SEO & Meta Tags -->
    <meta name="description" content="@yield('meta_description', 'Cirebon Kita - Portal Berita dan informasi terpercaya.')" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="canonical" href="{{ url()->current() }}" />

    <!-- Open Graph -->
    <meta property="og:title" content="@yield('title', 'Cirebon Kita')" />
    <meta property="og:description" content="@yield('meta_description', 'Cirebon Kita - Portal Berita dan informasi terpercaya.')" />
    <meta property="og:image" content="@yield('meta_image', asset('assets/media/logos/logo-letter-1.png'))" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:type" content="@yield('meta_type', 'website')" />

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="@yield('title', 'Cirebon Kita')" />
    <meta name="twitter:description" content="@yield('meta_description', 'Cirebon Kita - Portal Berita dan informasi terpercaya.')" />
    <meta name="twitter:image" content="@yield('meta_image', asset('assets/media/logos/logo-letter-1.png'))" />

    <!-- Schema.org JSON-LD -->
    @yield('schema_json_ld')

    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />

    <!--end::Fonts-->

    <!--begin::Page Vendors Styles(used by this page)-->
    @yield('styles')

    <!--end::Page Vendors Styles-->

    <!--begin::Global Theme Styles(used by all pages)-->
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/custom/prismjs/prismjs.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />

    <!--end::Global Theme Styles-->

    <!--begin::Layout Themes(used by all pages)-->
    @if (!empty($isDarkHeader) && $isDarkHeader === true)
        <link href="{{ asset('assets/css/themes/layout/header/base/dark.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/css/themes/layout/header/menu/dark.css') }}" rel="stylesheet" type="text/css" />
    @else
        <link href="{{ asset('assets/css/themes/layout/header/base/light.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/css/themes/layout/header/menu/light.css') }}" rel="stylesheet" type="text/css" />
    @endif
    @if (!empty($isLight) && $isLight === true)
        <link href="{{ asset('assets/css/themes/layout/brand/light.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/css/themes/layout/aside/light.css') }}" rel="stylesheet" type="text/css" />
    @else
        <link href="{{ asset('assets/css/themes/layout/brand/dark.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/css/themes/layout/aside/dark.css') }}" rel="stylesheet" type="text/css" />
    @endif
    <!--end::Layout Themes-->
    <link rel="shortcut icon" href="{{ asset('assets/media/logos/favicon.ico') }}" />

    <base href="{{ url('/') }}/">

</head>

<!--end::Head-->

<!--begin::Body-->

@php
    // Default
    $bodyClass =
        'header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading';
    $layout = 'layouts.layout';

    if (!empty($isAuth) && $isAuth === true) {
        $bodyClass = 'header-fixed header-mobile-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading';
        $layout = 'layouts.layout-auth';
    } elseif (!empty($isNoSubheader) && $isNoSubheader === true) {
        $bodyClass = 'header-fixed header-mobile-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading';
        $layout = 'layouts.layout-no-subheader';
    } elseif (!empty($isMiniAside) && $isMiniAside === true) {
        $bodyClass =
            'header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize aside-minimize-hoverable page-loading';
        $layout = 'layouts.layout';
    } elseif (!empty($isNoAside) && $isNoAside === true) {
        $bodyClass = 'header-fixed header-mobile-fixed subheader-enabled subheader-fixed page-loading';
        $layout = 'layouts.layout-no-aside';
    } elseif (!empty($isFooterFixed) && $isFooterFixed === true) {
        $bodyClass =
            'header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable footer-fixed page-loading';
        $layout = 'layouts.layout';
    }
@endphp

<body id="kt_body" class="{{ $bodyClass }}">
    @include($layout)

    <!--[html-partial:include:{"file":"partials/_extras/offcanvas/quick-panel.html"}]/-->
    @include('layouts.partials._extras.offcanvas.quick-panel')
    <!--[html-partial:include:{"file":"partials/_extras/chat.html"}]/-->
    @include('layouts.partials._extras.chat')
    <!--[html-partial:include:{"file":"partials/_extras/scrolltop.html"}]/-->
    @include('layouts.partials._extras.scrolltop')
    <!--[html-partial:include:{"file":"partials/_extras/toolbar.html"}]/-->
    @include('layouts.partials._extras.toolbar')
    <!--[html-partial:include:{"file":"partials/_extras/offcanvas/demo-panel.html"}]/-->
    @include('layouts.partials._extras.offcanvas.demo-panel')

    <script>
        var HOST_URL = "https://preview.keenthemes.com/metronic/theme/html/tools/preview";
    </script>

    <!--begin::Global Config(global config for global JS scripts)-->
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

    <!--end::Global Config-->

    <!--begin::Global Theme Bundle(used by all pages)-->
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/plugins/custom/prismjs/prismjs.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
    <script src="https://keenthemes.com/metronic/assets/js/engage_code.js"></script>

    <!--end::Global Theme Bundle-->

    @yield('scripts')

    <script>
        // Mencegah semua href="#" reload page karena <base>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('a[href="#"]').forEach(function(el) {
                el.addEventListener('click', function(e) {
                    e.preventDefault();
                });
            });
        });
    </script>
    <!--end::Page Scripts-->
</body>

<!--end::Body-->

</html>
