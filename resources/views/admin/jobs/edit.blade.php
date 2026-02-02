@extends('layouts.index')

@section('title', 'Edit Job')

@section('subheader')
    @component('layouts.partials._breadcrumbs')
        @slot('title')
            Edit Job Vacancy
        @endslot
        @slot('action')
            <a href="{{ route('admin.jobs.index') }}" class="btn btn-light-primary font-weight-bolder">
                Back
            </a>
        @endslot
    @endcomponent
@endsection

@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <h3 class="card-title">Edit Job: {{ $job->title }}</h3>
        </div>
        <form id="jobForm">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="form-group">
                    <label>Job Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="title" value="{{ $job->title }}" required>
                </div>

                <div class="form-group">
                    <label>Company <span class="text-danger">*</span></label>
                    <select class="form-control" name="company_id" required>
                        <option value="">Select Company</option>
                        @foreach ($companies as $company)
                            <option value="{{ $company->id }}" {{ $job->company_id == $company->id ? 'selected' : '' }}>
                                {{ $company->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Location <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="location" value="{{ $job->location }}"
                                required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Job Type <span class="text-danger">*</span></label>
                            <select class="form-control" name="type" required>
                                @foreach (['Full-time', 'Part-time', 'Contract', 'Internship', 'Freelance'] as $type)
                                    <option value="{{ $type }}" {{ $job->type == $type ? 'selected' : '' }}>
                                        {{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Salary Range</label>
                    <input type="text" class="form-control" name="salary_range" value="{{ $job->salary_range }}">
                </div>

                <div class="form-group">
                    <label>Description <span class="text-danger">*</span></label>
                    <textarea class="summernote" name="description" id="kt_summernote_1">{!! $job->description !!}</textarea>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <div class="radio-inline">
                        <label class="radio">
                            <input type="radio" name="status" value="active"
                                {{ $job->status == 'active' ? 'checked' : '' }}>
                            <span></span>
                            Active
                        </label>
                        <label class="radio">
                            <input type="radio" name="status" value="closed"
                                {{ $job->status == 'closed' ? 'checked' : '' }}>
                            <span></span>
                            Closed
                        </label>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary mr-2" id="submitBtn">Update</button>
                <button type="reset" class="btn btn-secondary">Cancel</button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Init Summernote
            $('#kt_summernote_1').summernote({
                height: 400,
                tabsize: 2
            });

            // Form Submit
            $('#jobForm').on('submit', function(e) {
                e.preventDefault();

                let formData = new FormData(this);
                $('#submitBtn').addClass('spinner spinner-white spinner-right').prop('disabled', true);

                $.ajax({
                    url: '{{ route('admin.jobs.update', $job->id) }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        toastr.success(response.message);
                        setTimeout(() => {
                            window.location.href = '{{ route('admin.jobs.index') }}';
                        }, 1000);
                    },
                    error: function(xhr) {
                        $('#submitBtn').removeClass('spinner spinner-white spinner-right').prop(
                            'disabled', false);
                        let errors = xhr.responseJSON.errors;
                        let msg = 'Validation Error';
                        if (errors) {
                            msg = Object.values(errors).flat().join('<br>');
                        } else if (xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }
                        toastr.error(msg);
                    }
                });
            });
        });
    </script>
@endsection
