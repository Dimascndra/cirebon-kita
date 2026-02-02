@extends('layouts.index')
@section('title', 'Dashboard Analytics')

@section('subheader')
    @component('layouts.partials._subheader.subheader-v1')
        @slot('title')
            Admin Dashboard
        @endslot
        @slot('action')
            <span class="text-muted font-weight-bold">{{ now()->format('d M Y') }}</span>
        @endslot
    @endcomponent
@endsection


@section('content')

    <div class="container-fluid">

        {{-- ================= STATS ================= --}}
        <div class="row g-5 mb-5">

            {{-- Users --}}
            <div class="col-md-4">
                <div class="card card-custom card-stretch">
                    <div class="card-body text-center py-8">

                        <div class="symbol symbol-50 symbol-light-primary mb-4">
                            <span class="symbol-label">
                                <i class="flaticon-users-1 text-primary icon-xl"></i>
                            </span>
                        </div>

                        <div class="font-size-h2 font-weight-bolder">
                            {{ number_format($totalUsers ?? 0) }}
                        </div>

                        <div class="text-muted">Total Users</div>
                    </div>
                </div>
            </div>


            {{-- Jobs --}}
            <div class="col-md-4">
                <div class="card card-custom card-stretch">
                    <div class="card-body text-center py-8">

                        <div class="symbol symbol-50 symbol-light-success mb-4">
                            <span class="symbol-label">
                                <i class="flaticon2-briefcase text-success icon-xl"></i>
                            </span>
                        </div>

                        <div class="font-size-h2 font-weight-bolder">
                            {{ number_format($activeJobs ?? 0) }}
                        </div>

                        <div class="text-muted">Active Jobs</div>
                    </div>
                </div>
            </div>


            {{-- Views --}}
            <div class="col-md-4">
                <div class="card card-custom card-stretch">
                    <div class="card-body text-center py-8">

                        <div class="symbol symbol-50 symbol-light-danger mb-4">
                            <span class="symbol-label">
                                <i class="flaticon-eye text-danger icon-xl"></i>
                            </span>
                        </div>

                        <div class="font-size-h2 font-weight-bolder">
                            {{ number_format($totalViews ?? 0) }}
                        </div>

                        <div class="text-muted">Article Views</div>
                    </div>
                </div>
            </div>

        </div>



        {{-- ================= CHART + TRENDING ================= --}}
        <div class="row g-5">

            {{-- Chart (Lebih lebar) --}}
            <div class="col-xl-8">
                <div class="card card-custom card-stretch">

                    <div class="card-header border-0">
                        <h3 class="card-title font-weight-bolder">
                            User Growth (Last 7 Days)
                        </h3>
                    </div>

                    <div class="card-body">
                        <div id="chart_user_growth" style="height:380px;"></div>
                    </div>

                </div>
            </div>



            {{-- Trending Sidebar --}}
            <div class="col-xl-4">
                <div class="card card-custom card-stretch">

                    <div class="card-header border-0">
                        <h3 class="card-title font-weight-bolder">
                            Trending News
                        </h3>
                    </div>

                    <div class="card-body pt-3">

                        @forelse($trendingPosts as $post)
                            <div class="d-flex align-items-center mb-6">

                                <div class="symbol symbol-35 symbol-light-primary mr-4">
                                    <span class="symbol-label">
                                        <i class="flaticon2-paper text-primary"></i>
                                    </span>
                                </div>

                                <div class="flex-grow-1">
                                    <a href="#" class="text-dark font-weight-bold text-hover-primary d-block">
                                        {{ Str::limit($post->title, 35) }}
                                    </a>
                                    <span class="text-muted font-size-sm">
                                        {{ number_format($post->views) }} Views
                                    </span>
                                </div>

                                <span class="badge badge-light-primary">
                                    #{{ $loop->iteration }}
                                </span>

                            </div>

                        @empty
                            <div class="text-center text-muted py-10">
                                No data
                            </div>
                        @endforelse

                    </div>

                </div>
            </div>

        </div>

    </div>

@endsection
s


@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            var options = {
                series: [{
                    name: 'New Users',
                    data: @json($counts ?? [])
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
                    categories: @json($dates ?? []),
                },
                tooltip: {
                    theme: 'light'
                },
                colors: ['#3699FF']
            };

            new ApexCharts(
                document.querySelector("#chart_user_growth"),
                options
            ).render();
        });
    </script>
@endsection
