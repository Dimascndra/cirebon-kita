@extends('layouts.public')

@section('content')
    <div class="subheader py-5 py-lg-10 subheader-transparent bg-primary mb-10" id="kt_subheader">
        <div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <h2 class="text-white font-weight-bold my-2 mr-5">Berita & Informasi</h2>
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
                            <input type="text" class="form-control form-control-solid" placeholder="Cari berita..."
                                id="search-input">
                            <span><i class="flaticon2-search-1 text-muted"></i></span>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                        <select class="form-control form-control-solid" id="category-filter">
                            <option value="">Semua Kategori</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->slug }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                        <select class="form-control form-control-solid" id="sort-filter">
                            <option value="newest">Terbaru</option>
                            <option value="popular">Terpopuler</option>
                            <option value="oldest">Terlama</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-12 col-sm-12">
                        <button class="btn btn-primary btn-block font-weight-bold" id="btn-search">Filter</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- NEWS GRID -->
    <div class="container">
        <div class="row" id="news-grid">
            <!-- Loaded via AJAX -->
            <div class="col-12 text-center py-10">
                <div class="spinner spinner-primary spinner-lg mr-15"></div>
            </div>
        </div>

        <!-- PAGINATION -->
        <div class="d-flex justify-content-center py-5" id="pagination">
            <!-- Buttons rendered via JS -->
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/ajax.js') }}"></script>
    <script src="{{ asset('js/news.js') }}"></script>
@endsection
