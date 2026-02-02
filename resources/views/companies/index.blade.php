@extends('layouts.public')

@section('title', 'Perusahaan - Cirebon Kita')

@section('content')
    <!-- HEADER -->
    <div class="subheader py-5 py-lg-10 subheader-transparent bg-primary mb-10">
        <div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <h2 class="text-white font-weight-bold my-2 mr-5">Direktori Perusahaan</h2>
                <p class="text-white-50 font-size-lg mb-0">Temukan perusahaan terbaik di Cirebon dan sekitarnya.</p>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="container mb-5">
        <!-- FILTER -->
        <div class="card card-custom gutter-b shadow-sm">
            <div class="card-body">
                <form action="{{ route('companies.index') }}" method="GET">
                    <div class="row align-items-center">
                        <div class="col-lg-6 mb-3">
                            <div class="input-icon">
                                <input type="text" name="search" class="form-control form-control-solid"
                                    placeholder="Cari nama perusahaan..." value="{{ request('search') }}">
                                <span><i class="flaticon2-search-1 text-muted"></i></span>
                            </div>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <select name="sort" class="form-control form-control-solid" onchange="this.form.submit()">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru Bergabung
                                </option>
                                <option value="most_jobs" {{ request('sort') == 'most_jobs' ? 'selected' : '' }}>Lowongan
                                    Terbanyak</option>
                                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama (A-Z)
                                </option>
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <button type="submit" class="btn btn-primary btn-block font-weight-bold">Cari</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- LIST -->
        <div class="row">
            @forelse($companies as $company)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card card-custom card-stretch shadow-sm hover-effect">
                        <div class="card-body text-center pt-8">
                            <!-- Logo -->
                            <div class="symbol symbol-100 symbol-circle mb-5 symbol-light-primary">
                                @if ($company->logo)
                                    <div class="symbol-label"
                                        style="background-image:url('{{ asset('storage/' . $company->logo) }}')"></div>
                                @else
                                    <span class="symbol-label font-size-h1 font-weight-bold text-primary">
                                        {{ substr($company->name, 0, 1) }}
                                    </span>
                                @endif
                            </div>

                            <!-- Name -->
                            <h4 class="font-size-h5 font-weight-bolder text-dark mb-1">
                                {{ $company->name }}
                            </h4>
                            @if ($company->verified)
                                <span class="label label-inline label-light-success font-weight-bold mb-3">
                                    <i class="flaticon2-check-mark text-success mr-1 font-size-sm"></i> Verified
                                </span>
                            @endif

                            <!-- Jobs Count -->
                            <div class="mt-2">
                                <span class="text-muted font-weight-bold font-size-sm">
                                    {{ $company->jobs_count ?? 0 }} Lowongan Aktif
                                </span>
                            </div>

                            <!-- Action -->
                            <div class="mt-5">
                                <a href="{{ route('companies.show', $company->slug) }}"
                                    class="btn btn-sm btn-light-primary font-weight-bold">Lihat Profil</a>
                                {{-- Link to detail can be added later if route exists --}}
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-10">
                    <div class="symbol symbol-150 mb-5">
                        <img src="{{ asset('assets/media/svg/illustrations/working.svg') }}" alt="No Data" />
                    </div>
                    <h3 class="font-weight-bolder text-dark">Belum ada perusahaan.</h3>
                    <p class="text-muted font-size-lg">Coba kata kunci lain atau reset filter.</p>
                </div>
            @endforelse
        </div>

        <!-- PAGINATION -->
        <div class="d-flex justify-content-center py-5">
            {{ $companies->appends(request()->query())->links() }}
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .hover-effect:hover {
            transform: translateY(-5px);
            transition: all 0.3s;
            border-color: #3699FF !important;
        }
    </style>
@endsection
