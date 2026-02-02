@extends('layouts.public')

@section('content')
    <!-- SECTION 1: HERO -->
    <div id="hero-section" class="hero-section d-flex align-items-center justify-content-center text-center">
        <!-- Loaded via AJAX -->
        <div class="spinner spinner-primary spinner-lg"></div>
    </div>

    <!-- SECTION 2: TOP BANNER -->
    <div class="container mt-5 mb-5" id="top-banner-section">
        <!-- Loaded via AJAX -->
    </div>

    <!-- SECTION 3: JOB SEARCH -->
    <div class="container mb-5">
        <div class="card card-custom gutter-b shadow-sm">
            <div class="card-body">
                <form class="form">
                    <div class="form-row align-items-center">
                        <div class="col-md-5 my-1">
                            <input type="text" class="form-control form-control-solid form-control-lg"
                                placeholder="Cari posisi atau kata kunci...">
                        </div>
                        <div class="col-md-5 my-1">
                            <input type="text" class="form-control form-control-solid form-control-lg"
                                placeholder="Lokasi (Cirebon, Kuningan...)">
                        </div>
                        <div class="col-md-2 my-1">
                            <button type="button" class="btn btn-primary btn-lg btn-block font-weight-bold">Cari</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT ROW -->
    <div class="container">
        <div class="row">
            <!-- LEFT COLUMN -->
            <div class="col-lg-8">

                <!-- SECTION 4: LATEST NEWS -->
                <div class="mb-10">
                    <div class="d-flex justify-content-between align-items-center mb-5">
                        <h3 class="text-dark font-weight-bold m-0">Berita Terbaru</h3>
                        <a href="#" class="btn btn-light-primary btn-sm font-weight-bold">Lihat Semua</a>
                    </div>
                    <div id="news-section" class="row">
                        <!-- Loaded via AJAX -->
                        <div class="col-12 text-center">
                            <div class="spinner spinner-primary"></div>
                        </div>
                    </div>
                </div>

                <!-- SECTION: HOMEPAGE BANNER (Between News and Jobs) -->
                <div id="homepage-banner-section" class="mb-10">
                    <!-- Loaded via AJAX -->
                </div>

                <!-- SECTION 5: LATEST JOBS -->
                <div class="mb-10">
                    <div class="d-flex justify-content-between align-items-center mb-5">
                        <h3 class="text-dark font-weight-bold m-0">Lowongan Kerja Terbaru</h3>
                        <a href="#" class="btn btn-light-success btn-sm font-weight-bold">Lihat Semua</a>
                    </div>
                    <div id="jobs-section">
                        <!-- Loaded via AJAX -->
                        <div class="text-center">
                            <div class="spinner spinner-success"></div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- RIGHT COLUMN (SIDEBAR) -->
            <div class="col-lg-4">

                <!-- SECTION 8: SIDEBAR ADS -->
                <div id="sidebar-ads-section" class="mb-10">
                    <!-- Loaded via AJAX -->
                </div>

                <!-- SECTION 6: CATEGORIES (Placed in sidebar for better flow, or bottom) -->
                <div class="card card-custom gutter-b">
                    <div class="card-header border-0">
                        <h3 class="card-title font-weight-bolder text-dark">Kategori</h3>
                    </div>
                    <div class="card-body pt-0" id="categories-section">
                        <!-- Loaded via AJAX -->
                        <div class="spinner spinner-dark"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- SECTION: FOOTER BANNER -->
    <div class="container mb-5" id="footer-banner-section">
        <!-- Loaded via AJAX -->
    </div>

    <!-- SECTION 7: FEATURED COMPANIES -->
    <div class="bg-secondary py-10">
        <div class="container">
            <h3 class="text-center font-weight-bold mb-10">Perusahaan Unggulan</h3>
            <div id="companies-section" class="row justify-content-center">
                <!-- Loaded via AJAX -->
            </div>
        </div>
    </div>

    <!-- SECTION 9: NEWSLETTER -->
    <div class="container py-10">
        <div class="card card-custom bg-primary">
            <div class="card-body py-10 px-10 text-center">
                <h2 class="text-white font-weight-bolder mb-5">Jangan Lewatkan Info Terbaru</h2>
                <p class="text-white-50 font-size-lg mb-8">Dapatkan berita dan lowongan kerja terbaru langsung di inbox
                    Anda.</p>
                <form class="form-inline justify-content-center">
                    <input type="email" class="form-control form-control-lg w-300px mr-2"
                        placeholder="Masukkan email Anda">
                    <button type="submit" class="btn btn-white btn-lg font-weight-bold text-primary">Langganan</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/ajax.js') }}"></script>
    <script src="{{ asset('js/home.js') }}"></script>
@endsection
