@extends('layouts.index')

@section('title', 'Applicant Detail')

@section('subheader')
    @component('layouts.partials._breadcrumbs')
        @slot('title')
            Applicant Detail
        @endslot
        @slot('action')
            <a href="{{ route('company.applicants.index') }}" class="btn btn-light-primary btn-sm font-weight-bold">
                <i class="flaticon2-back"></i> Back to List
            </a>
        @endslot
    @endcomponent
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <!-- Applicant Info -->
            <div class="card card-custom gutter-b">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="card-label">Applicant Information</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-7">
                        <div class="symbol symbol-60 symbol-light-primary mr-5">
                            <span class="symbol-label font-size-h1 font-weight-bold">
                                {{ substr($application->user->name, 0, 1) }}
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <h4 class="font-weight-bolder mb-1">{{ $application->user->name }}</h4>
                            <p class="text-muted mb-0"><i
                                    class="flaticon2-new-email mr-2"></i>{{ $application->user->email }}</p>
                        </div>
                    </div>

                    <div class="separator separator-solid my-7"></div>

                    <div class="mb-7">
                        <h5 class="font-weight-bold mb-3">Applied For</h5>
                        <p class="font-size-lg mb-1"><strong>{{ $application->job->title }}</strong></p>
                        <p class="text-muted mb-0">
                            <i class="flaticon2-pin mr-1"></i>{{ $application->job->location }} |
                            <i class="flaticon2-time mr-1"></i>{{ $application->job->type }} |
                            <i class="flaticon2-calendar-1 mr-1"></i>Applied on
                            {{ $application->applied_at->format('d M Y, H:i') }}
                        </p>
                    </div>

                    <div class="separator separator-solid my-7"></div>

                    @if ($application->cover_letter)
                        <div class="mb-7">
                            <h5 class="font-weight-bold mb-3">Cover Letter</h5>
                            <div class="bg-light-primary p-5 rounded">
                                <p class="mb-0" style="white-space: pre-wrap;">{{ $application->cover_letter }}</p>
                            </div>
                        </div>
                    @else
                        <div class="mb-7">
                            <h5 class="font-weight-bold mb-3">Cover Letter</h5>
                            <p class="text-muted font-italic">No cover letter provided</p>
                        </div>
                    @endif

                    <div class="separator separator-solid my-7"></div>

                    <!-- CV Download -->
                    <div class="mb-3">
                        <h5 class="font-weight-bold mb-3">Curriculum Vitae (CV)</h5>
                        <a href="{{ route('company.applicants.downloadCv', $application) }}" target="_blank"
                            class="btn btn-primary font-weight-bold">
                            <i class="flaticon2-download-2"></i> Download CV
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Status Update -->
            <div class="card card-custom gutter-b">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="card-label">Application Status</h3>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('company.applicants.updateStatus', $application) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="form-group">
                            <label class="font-weight-bold">Current Status</label>
                            <div class="mb-3">
                                {!! $application->statusBadge !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Update Status</label>
                            <select name="status" class="form-control form-control-lg" required>
                                <option value="pending" {{ $application->status == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="reviewing" {{ $application->status == 'reviewing' ? 'selected' : '' }}>
                                    Reviewing</option>
                                <option value="shortlisted" {{ $application->status == 'shortlisted' ? 'selected' : '' }}>
                                    Shortlisted</option>
                                <option value="rejected" {{ $application->status == 'rejected' ? 'selected' : '' }}>
                                    Rejected</option>
                                <option value="accepted" {{ $application->status == 'accepted' ? 'selected' : '' }}>
                                    Accepted</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Internal Notes (Optional)</label>
                            <textarea name="notes" class="form-control" rows="4" placeholder="Add notes about this applicant...">{{ $application->notes }}</textarea>
                            <small class="form-text text-muted">This note is only visible to your company</small>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block font-weight-bold">
                            <i class="flaticon-check"></i> Update Status
                        </button>
                    </form>
                </div>
            </div>

            <!-- Timeline -->
            <div class="card card-custom">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="card-label">Timeline</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="timeline timeline-3">
                        <div class="timeline-items">
                            <div class="timeline-item">
                                <div class="timeline-media bg-light-success">
                                    <i class="flaticon2-paper-plane text-success"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="font-weight-bold">Applied</div>
                                    <div class="text-muted font-size-sm">
                                        {{ $application->applied_at->format('d M Y, H:i') }}</div>
                                </div>
                            </div>

                            @if ($application->updated_at != $application->created_at)
                                <div class="timeline-item">
                                    <div class="timeline-media bg-light-primary">
                                        <i class="flaticon2-refresh text-primary"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <div class="font-weight-bold">Last Updated</div>
                                        <div class="text-muted font-size-sm">
                                            {{ $application->updated_at->format('d M Y, H:i') }}</div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @if (session('success'))
        <script>
            toastr.success('{{ session('success') }}', 'Success');
        </script>
    @endif
@endsection
