@extends('layouts.public')

@section('title', 'Tentang Kami - Cirebon Kita')

@section('content')
    <!-- HERO -->
    <div class="hero-section d-flex align-items-center justify-content-center text-center text-white"
        style="background-image: url('{{ asset('assets/media/bg/bg-3.jpg') }}'); overlay: rgba(0,0,0,0.6);">
        <div class="zindex-1">
            <h1 class="display-3 font-weight-boldest mb-3 text-white">Tentang Cirebon Kita</h1>
            <p class="lead font-size-xl">Portal Berita dan Lowongan Kerja Terpercaya di Cirebon</p>
        </div>
        <div class="hero-overlay" style="background-color: rgba(0,0,0,0.5)"></div>
    </div>

    <!-- CONTENT -->
    <div class="container py-10 py-lg-20">
        <div class="row">
            <div class="col-lg-6 mb-10">
                <h2 class="font-weight-bolder text-dark mb-5">Siapa Kami?</h2>
                <div class="font-size-lg text-dark-50 line-height-xl">
                    <p>
                        <strong>Cirebon Kita</strong> adalah platform digital yang didedikasikan untuk menghubungkan
                        masyarakat Cirebon
                        dengan informasi terkini dan peluang karir terbaik. Didirikan pada tahun 2024, kami percaya bahwa
                        akses informasi yang cepat dan akurat adalah kunci kemajuan daerah.
                    </p>
                    <p>
                        Kami memadukan portal berita lokal yang independen dengan bursa kerja (Job Board) yang modern,
                        memudahkan pencari kerja menemukan perusahaan impian mereka di wilayah Cirebon, Indramayu,
                        Majalengka, dan Kuningan (Ciayumajakuning).
                    </p>
                </div>
            </div>
            <div class="col-lg-6 mb-10 text-center">
                <img src="{{ asset('assets/media/svg/illustrations/working.svg') }}" class="img-fluid" width="80%"
                    alt="About Us">
            </div>
        </div>

        <!-- VISI MISI -->
        <div class="row mt-10">
            <div class="col-md-6">
                <div class="card card-custom bg-light-primary border-0 mb-5">
                    <div class="card-body">
                        <h3 class="font-weight-bolder text-primary mb-3">Visi Kami</h3>
                        <p class="font-size-lg text-dark-75">
                            Menjadi ekosistem digital terdepan di Cirebon yang memberdayakan masyarakat melalui informasi
                            dan peluang ekonomi.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-custom bg-light-success border-0 mb-5">
                    <div class="card-body">
                        <h3 class="font-weight-bolder text-success mb-3">Misi Kami</h3>
                        <ul class="font-size-lg text-dark-75 pl-5">
                            <li>Menyajikan berita lokal yang akurat, berimbang, dan edukatif.</li>
                            <li>Menghubungkan talenta lokal dengan perusahaan terbaik di wilayah Cirebon.</li>
                            <li>Mendukung UMKM dan bisnis lokal melalui promosi digital.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CONTACT -->
    <div class="bg-light py-10 py-lg-20">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2 class="font-weight-bolder mb-5">Hubungi Kami</h2>
                    <p class="font-size-lg text-dark-50 mb-10">
                        Punya pertanyaan, saran, atau ingin bekerjasama? Jangan ragu untuk menghubungi tim kami.
                    </p>
                    <div class="row">
                        <div class="col-md-4 mb-5">
                            <div class="bg-white p-5 rounded shadow-sm">
                                <i class="flaticon2-location text-primary font-size-h1"></i>
                                <h5 class="font-weight-bold mt-3">Alamat</h5>
                                <p class="text-muted">Jl. Siliwangi No. 123, Cirebon</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-5">
                            <div class="bg-white p-5 rounded shadow-sm">
                                <i class="flaticon2-email text-primary font-size-h1"></i>
                                <h5 class="font-weight-bold mt-3">Email</h5>
                                <p class="text-muted">info@cirebonkita.com</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-5">
                            <div class="bg-white p-5 rounded shadow-sm">
                                <i class="flaticon2-phone text-primary font-size-h1"></i>
                                <h5 class="font-weight-bold mt-3">Telepon</h5>
                                <p class="text-muted">+62 231 123456</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
