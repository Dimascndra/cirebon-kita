@extends('layouts.index')

@section('title', 'Applicants')

@section('subheader')
    @component('layouts.partials._breadcrumbs')
        @slot('title')
            Applicants Management
        @endslot
        @slot('action')
            <div class="d-flex align-items-center">
                <select class="form-control form-control-sm mr-2" id="statusFilter" onchange="filterByStatus(this.value)"
                    style="width: 150px;">
                    <option value="">All Status</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endslot
    @endcomponent
@endsection

@section('content')
    <div class="card card-custom">
        <div class="card-header border-0 py-5">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label font-weight-bolder text-dark">Job Applicants</span>
                <span class="text-muted mt-3 font-weight-bold font-size-sm">{{ $applications->total() }} total
                    applications</span>
            </h3>
        </div>

        <div class="card-body pt-0">
            @if ($applications->count() > 0)
                <div class="table-responsive">
                    <table class="table table-head-custom table-vertical-center table-hover">
                        <thead>
                            <tr class="text-left text-uppercase">
                                <th style="min-width: 200px" class="pl-7">Applicant</th>
                                <th style="min-width: 200px">Job Position</th>
                                <th style="min-width: 120px">Applied Date</th>
                                <th style="min-width: 100px">Status</th>
                                <th style="min-width: 120px" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($applications as $app)
                                <tr>
                                    <td class="pl-7">
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-40 symbol-light-primary mr-4">
                                                <span class="symbol-label font-size-h4 font-weight-bold">
                                                    {{ substr($app->user->name, 0, 1) }}
                                                </span>
                                            </div>
                                            <div>
                                                <a href="{{ route('company.applicants.show', $app) }}"
                                                    class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">
                                                    {{ $app->user->name }}
                                                </a>
                                                <span
                                                    class="text-muted font-weight-bold d-block">{{ $app->user->email }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-dark-75 font-weight-bold d-block font-size-lg">
                                            {{ $app->job->title }}
                                        </span>
                                        <span class="text-muted font-weight-bold">{{ $app->job->location }}</span>
                                    </td>
                                    <td>
                                        <span
                                            class="text-muted font-weight-bold">{{ $app->applied_at->format('d M Y') }}</span>
                                        <span
                                            class="text-muted font-weight-bold d-block font-size-sm">{{ $app->applied_at->diffForHumans() }}</span>
                                    </td>
                                    <td>
                                        {!! $app->statusBadge !!}
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('company.applicants.show', $app) }}"
                                            class="btn btn-sm btn-light-primary font-weight-bold">
                                            <i class="flaticon-eye"></i> View Details
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-5">
                    {{ $applications->links() }}
                </div>
            @else
                <div class="text-center py-20">
                    <i class="flaticon2-box-1 icon-4x text-muted"></i>
                    <h5 class="text-muted mt-5">No applications yet</h5>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function filterByStatus(status) {
            window.location.href = `{{ route('company.applicants.index') }}?status=${status}`;
        }
    </script>
@endsection
