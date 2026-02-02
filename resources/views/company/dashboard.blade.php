@extends('layouts.index')

@section('title', 'Company Dashboard')

@section('subheader')
    @component('layouts.partials._breadcrumbs')
        @slot('title')
            Company Dashboard
        @endslot
        @slot('action')
            <span class="text-muted font-weight-bold">{{ now()->format('d M Y') }}</span>
        @endslot
    @endcomponent
@endsection

@section('content')
    <div class="container-fluid">

        {{-- ================= STATS CARDS ================= --}}
        <div class="row g-5 mb-5">

            {{-- Total Jobs --}}
            <div class="col-md-6 col-xl-3">
                <div class="card card-custom card-stretch">
                    <div class="card-body text-center py-8">
                        <div class="symbol symbol-50 symbol-light-primary mb-4">
                            <span class="symbol-label">
                                <i class="flaticon2-list-2 text-primary icon-xl"></i>
                            </span>
                        </div>
                        <div class="font-size-h2 font-weight-bolder">
                            {{ number_format($totalJobs) }}
                        </div>
                        <div class="text-muted">Total Jobs</div>
                    </div>
                </div>
            </div>

            {{-- Active Jobs --}}
            <div class="col-md-6 col-xl-3">
                <div class="card card-custom card-stretch">
                    <div class="card-body text-center py-8">
                        <div class="symbol symbol-50 symbol-light-success mb-4">
                            <span class="symbol-label">
                                <i class="flaticon2-check-mark text-success icon-xl"></i>
                            </span>
                        </div>
                        <div class="font-size-h2 font-weight-bolder">
                            {{ number_format($activeJobs) }}
                        </div>
                        <div class="text-muted">Active Jobs</div>
                    </div>
                </div>
            </div>

            {{-- Total Applications --}}
            <div class="col-md-6 col-xl-3">
                <div class="card card-custom card-stretch">
                    <div class="card-body text-center py-8">
                        <div class="symbol symbol-50 symbol-light-info mb-4">
                            <span class="symbol-label">
                                <i class="flaticon-users text-info icon-xl"></i>
                            </span>
                        </div>
                        <div class="font-size-h2 font-weight-bolder">
                            {{ number_format($totalApplications) }}
                        </div>
                        <div class="text-muted">Total Applications</div>
                    </div>
                </div>
            </div>

            {{-- Pending Review --}}
            <div class="col-md-6 col-xl-3">
                <div class="card card-custom card-stretch">
                    <div class="card-body text-center py-8">
                        <div class="symbol symbol-50 symbol-light-warning mb-4">
                            <span class="symbol-label">
                                <i class="flaticon2-hourglass text-warning icon-xl"></i>
                            </span>
                        </div>
                        <div class="font-size-h2 font-weight-bolder">
                            {{ number_format($pendingApplications) }}
                        </div>
                        <div class="text-muted">Pending Review</div>
                    </div>
                </div>
            </div>

        </div>

        {{-- ================= APPLICATION STATUS BREAKDOWN ================= --}}
        <div class="row g-5 mb-5">
            <div class="col-xl-8">
                {{-- Application Trend Chart --}}
                <div class="card card-custom card-stretch">
                    <div class="card-header border-0">
                        <h3 class="card-title font-weight-bolder">Application Trend (Last 7 Days)</h3>
                    </div>
                    <div class="card-body">
                        <div id="chart_applications" style="height:350px;"></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                {{-- Status Breakdown --}}
                <div class="card card-custom card-stretch">
                    <div class="card-header border-0">
                        <h3 class="card-title font-weight-bolder">Application Status</h3>
                    </div>
                    <div class="card-body pt-2">
                        <div class="mb-7">
                            <div class="d-flex align-items-center mb-3">
                                <span class="bullet bullet-bar bg-warning mr-3" style="width: 10px; height: 20px;"></span>
                                <span class="text-dark-75 font-weight-bold flex-grow-1">Pending</span>
                                <span class="font-weight-bolder">{{ $pendingApplications }}</span>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <span class="bullet bullet-bar bg-info mr-3" style="width: 10px; height: 20px;"></span>
                                <span class="text-dark-75 font-weight-bold flex-grow-1">Reviewing</span>
                                <span class="font-weight-bolder">{{ $reviewingApplications }}</span>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <span class="bullet bullet-bar bg-primary mr-3" style="width: 10px; height: 20px;"></span>
                                <span class="text-dark-75 font-weight-bold flex-grow-1">Shortlisted</span>
                                <span class="font-weight-bolder">{{ $shortlistedApplications }}</span>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <span class="bullet bullet-bar bg-success mr-3" style="width: 10px; height: 20px;"></span>
                                <span class="text-dark-75 font-weight-bold flex-grow-1">Accepted</span>
                                <span class="font-weight-bolder">{{ $acceptedApplications }}</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="bullet bullet-bar bg-danger mr-3" style="width: 10px; height: 20px;"></span>
                                <span class="text-dark-75 font-weight-bold flex-grow-1">Rejected</span>
                                <span class="font-weight-bolder">{{ $rejectedApplications }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= RECENT APPLICATIONS + POPULAR JOBS ================= --}}
        <div class="row g-5">
            <div class="col-xl-7">
                {{-- Recent Applications --}}
                <div class="card card-custom card-stretch">
                    <div class="card-header border-0">
                        <h3 class="card-title font-weight-bolder">Recent Applications</h3>
                        <div class="card-toolbar">
                            <a href="{{ route('company.applicants.index') }}"
                                class="btn btn-sm btn-light-primary font-weight-bold">
                                View All
                            </a>
                        </div>
                    </div>
                    <div class="card-body pt-2">
                        @forelse($recentApplications as $app)
                            <div class="d-flex align-items-center mb-6">
                                <div class="symbol symbol-40 symbol-light-primary mr-4">
                                    <span class="symbol-label font-weight-bold">
                                        {{ substr($app->user->name, 0, 1) }}
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <a href="{{ route('company.applicants.show', $app) }}"
                                        class="text-dark font-weight-bold text-hover-primary">
                                        {{ $app->user->name }}
                                    </a>
                                    <div class="text-muted font-size-sm">
                                        Applied for {{ $app->job->title }}
                                    </div>
                                </div>
                                <div class="text-right">
                                    {!! $app->statusBadge !!}
                                    <div class="text-muted font-size-sm mt-1">{{ $app->applied_at->diffForHumans() }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-10">No applications yet</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-xl-5">
                {{-- Popular Jobs --}}
                <div class="card card-custom card-stretch">
                    <div class="card-header border-0">
                        <h3 class="card-title font-weight-bolder">Most Applied Jobs</h3>
                    </div>
                    <div class="card-body pt-2">
                        @forelse($popularJobs as $job)
                            <div class="d-flex align-items-center mb-6">
                                <div class="symbol symbol-40 symbol-light-success mr-4">
                                    <span class="symbol-label">
                                        <i class="flaticon2-list text-success"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <a href="#" class="text-dark font-weight-bold text-hover-primary">
                                        {{ Str::limit($job->title, 30) }}
                                    </a>
                                    <div class="text-muted font-size-sm">{{ $job->location }}</div>
                                </div>
                                <span class="label label-light-primary label-inline font-weight-bold">
                                    {{ $job->applications_count }} applicants
                                </span>
                            </div>
                        @empty
                            <div class="text-center text-muted py-10">No jobs yet</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var options = {
                series: [{
                    name: 'Applications',
                    data: @json($counts)
                }],
                chart: {
                    height: 350,
                    type: 'area',
                    toolbar: {
                        show: false
                    }
                },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                dataLabels: {
                    enabled: false
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.4,
                        opacityTo: 0.05,
                    }
                },
                xaxis: {
                    categories: @json($dates),
                },
                tooltip: {
                    theme: 'light'
                },
                colors: ['#3699FF']
            };

            new ApexCharts(
                document.querySelector("#chart_applications"),
                options
            ).render();
        });
    </script>
@endsection
