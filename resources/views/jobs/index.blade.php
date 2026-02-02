@extends('layouts.public')

@section('content')
    <!-- HEADER -->
    <div class="subheader py-5 py-lg-10 subheader-transparent bg-success mb-10" id="kt_subheader">
        <div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <h2 class="text-white font-weight-bold my-2 mr-5">Lowongan Kerja</h2>
            </div>
        </div>
    </div>

    <!-- FILTER TOOLBAR -->
    <div class="container mb-5 mt-15">
        <div class="card card-custom gutter-b shadow-sm">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12 mb-3">
                        <div class="input-icon">
                            <input type="text" class="form-control form-control-solid"
                                placeholder="Posisi atau Perusahaan..." id="search-input">
                            <span><i class="flaticon2-search-1 text-muted"></i></span>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                        <select class="form-control form-control-solid" id="location-filter">
                            <option value="">Semua Lokasi</option>
                            @foreach ($locations as $loc)
                                <option value="{{ $loc }}">{{ $loc }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                        <select class="form-control form-control-solid" id="type-filter">
                            <option value="">Semua Tipe</option>
                            @foreach ($types as $type)
                                <option value="{{ $type }}">{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-12 col-sm-12">
                        <button class="btn btn-success btn-block font-weight-bold" id="btn-search">Filter</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- BANNER AD -->
    <div class="container mb-10">
        <div class="row">
            <div class="col-12">
                <img src="" id="top-banner-ad" class="w-100 rounded shadow-sm"
                    style="height: 120px; object-fit: cover; background: #eee;">
            </div>
        </div>
    </div>

    <!-- JOB LIST -->
    <div class="container">
        <div class="row">
            <div class="col-lg-12" id="jobs-grid">
                <!-- Loaded via AJAX -->
                <div class="text-center py-10">
                    <div class="spinner spinner-success spinner-lg mr-15"></div>
                </div>
            </div>
        </div>

        <!-- PAGINATION -->
        <div class="d-flex justify-content-center py-5" id="pagination">
            <!-- Buttons via JS -->
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/ajax.js') }}"></script>
    <script src="{{ asset('js/jobs.js') }}"></script>
@endsection
