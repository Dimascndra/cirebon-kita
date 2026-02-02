@extends('layouts.public')

@section('title', $company->name . ' - Cirebon Kita')

@section('content')
    <!-- HEADER -->
    <div class="subheader py-5 py-lg-10 subheader-transparent bg-white border-bottom mb-10">
        <div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex flex-column">
                    <h2 class="text-dark font-weight-bold my-2 mr-5">{{ $company->name }}</h2>
                    <div class="d-flex align-items-center font-weight-bold my-2">
                        @if ($company->website)
                            <a href="{{ $company->website }}" target="_blank" class="opacity-75 hover-opacity-100">
                                <i class="flaticon2-website text-primary mr-1"></i> {{ $company->website }}
                            </a>
                        @endif
                        @if ($company->verified)
                            <span class="label label-inline label-light-success font-weight-bold ml-3">
                                <i class="flaticon2-check-mark text-success mr-1 font-size-sm"></i> Verified
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="container">
        <div class="row">
            <!-- Sidebar / Info -->
            <div class="col-lg-4 mb-8">
                <div class="card card-custom gutter-b shadow-sm">
                    <div class="card-body text-center pt-10">
                        <div class="symbol symbol-120 symbol-circle mb-5 border symbol-light-primary">
                            @if ($company->logo)
                                <div class="symbol-label"
                                    style="background-image:url('{{ asset('storage/' . $company->logo) }}')"></div>
                            @else
                                <span class="symbol-label font-size-h1 font-weight-bold text-primary">
                                    {{ substr($company->name, 0, 1) }}
                                </span>
                            @endif
                        </div>
                        <h3 class="font-size-h4 font-weight-bold">{{ $company->name }}</h3>
                        <p class="text-muted mb-5 font-size-lg">{{ $company->industry ?? 'Perusahaan Umum' }}</p>

                        <div class="mt-5">
                            @if ($company->website)
                                <a href="{{ $company->website }}" target="_blank"
                                    class="btn btn-sm btn-light-primary font-weight-bold mb-2">
                                    <i class="flaticon2-website mr-1"></i> Website
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="card card-custom gutter-b shadow-sm">
                    <div class="card-header border-0">
                        <h3 class="card-title font-weight-bold text-dark">Kontak & Lokasi</h3>
                    </div>
                    <div class="card-body pt-0">
                        @if ($company->email)
                            <div class="d-flex align-items-center mb-5">
                                <div class="symbol symbol-40 symbol-light-primary mr-5">
                                    <span class="symbol-label"><i class="flaticon2-email text-primary"></i></span>
                                </div>
                                <div class="d-flex flex-column font-weight-bold">
                                    <a href="mailto:{{ $company->email }}"
                                        class="text-dark text-hover-primary mb-1 font-size-lg">{{ $company->email }}</a>
                                    <span class="text-muted">Email</span>
                                </div>
                            </div>
                        @endif

                        @if ($company->phone)
                            <div class="d-flex align-items-center mb-5">
                                <div class="symbol symbol-40 symbol-light-success mr-5">
                                    <span class="symbol-label"><i class="flaticon2-phone text-success"></i></span>
                                </div>
                                <div class="d-flex flex-column font-weight-bold">
                                    <span class="text-dark mb-1 font-size-lg">{{ $company->phone }}</span>
                                    <span class="text-muted">Telepon</span>
                                </div>
                            </div>
                        @endif

                        @if ($company->address)
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-40 symbol-light-warning mr-5">
                                    <span class="symbol-label"><i class="flaticon2-location text-warning"></i></span>
                                </div>
                                <div class="d-flex flex-column font-weight-bold">
                                    <span class="text-dark mb-1">{{ $company->address }}</span>
                                    <span class="text-muted">Alamat</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Description -->
                <div class="card card-custom gutter-b shadow-sm">
                    <div class="card-header border-0">
                        <h3 class="card-title font-weight-bold text-dark">Tentang Perusahaan</h3>
                    </div>
                    <div class="card-body pt-0 text-justify">
                        @if ($company->description)
                            {!! nl2br(e($company->description)) !!}
                        @else
                            <p class="text-muted">Belum ada deskripsi perusahaan.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Active Jobs -->
            <div class="col-lg-8">
                <div class="d-flex justify-content-between align-items-center mb-5">
                    <h3 class="text-dark font-weight-bold m-0">Lowongan Aktif</h3>
                </div>

                @forelse($company->jobs as $job)
                    <div class="card card-custom gutter-b job-card shadow-sm border-0">
                        <div class="card-body p-5">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h4 class="font-size-h5 font-weight-bolder mb-2">
                                        <a href="{{ route('jobs.show', $job->slug) }}"
                                            class="text-dark text-hover-primary">
                                            {{ $job->title }}
                                        </a>
                                    </h4>
                                    <div class="d-flex align-items-center font-size-sm text-muted">
                                        <span class="mr-3">
                                            <i class="flaticon2-pin mr-1"></i> {{ $job->location }}
                                        </span>
                                        <span class="mr-3">
                                            <i class="flaticon-price-tag mr-1"></i> {{ $job->payment_type ?? 'Full Time' }}
                                        </span>
                                        <span>
                                            <i class="flaticon-time-2 mr-1"></i> {{ $job->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    <div class="mt-3">
                                        @foreach (explode(',', $job->tags ?? '') as $tag)
                                            @if (trim($tag))
                                                <span
                                                    class="label label-light-primary label-inline font-weight-bold mr-2">{{ trim($tag) }}</span>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                <div>
                                    <a href="{{ route('jobs.show', $job->slug) }}"
                                        class="btn btn-primary btn-sm font-weight-bold">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-custom alert-light-warning fade show mb-5" role="alert">
                        <div class="alert-icon"><i class="flaticon-warning"></i></div>
                        <div class="alert-text">Belum ada lowongan aktif saat ini dari {{ $company->name }}.</div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
