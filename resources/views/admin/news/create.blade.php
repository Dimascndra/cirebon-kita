@extends('layouts.index')

@section('title', 'Create News')

@section('subheader')
    @component('layouts.partials._breadcrumbs')
        @slot('title')
            Create Article
        @endslot
        @slot('action')
            <a href="{{ route('admin.news.index') }}" class="btn btn-light-primary font-weight-bolder">
                Back
            </a>
        @endslot
    @endcomponent
@endsection

@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <h3 class="card-title">Write New Article</h3>
        </div>
        <form id="newsForm" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label>Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="title" required>
                </div>

                <div class="form-group">
                    <label>Category <span class="text-danger">*</span></label>
                    <select class="form-control" name="category_id" required>
                        <option value="">Select Category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Excerpt (Short Description) <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="excerpt" rows="3"></textarea>
                    <span class="form-text text-muted">Brief summary of the article (max 500 chars).</span>
                </div>

                <div class="form-group">
                    <label>Content <span class="text-danger">*</span></label>
                    <textarea class="summernote" name="content" id="kt_summernote_1"></textarea>
                </div>

                <div class="form-group">
                    <label>Featured Image <span class="text-danger">*</span></label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="image" id="customFile" accept="image/*"
                            required>
                        <label class="custom-file-label" for="customFile">Choose file</label>
                    </div>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <div class="radio-inline">
                        <label class="radio">
                            <input type="radio" name="status" value="published" checked="checked">
                            <span></span>
                            Published
                        </label>
                        <label class="radio">
                            <input type="radio" name="status" value="draft">
                            <span></span>
                            Draft
                        </label>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary mr-2" id="submitBtn">Submit</button>
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

            // Image input
            $('#customFile').on('change', function() {
                var fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').addClass("selected").html(fileName);
            });

            // Form Submit
            $('#newsForm').on('submit', function(e) {
                e.preventDefault();

                let formData = new FormData(this);
                $('#submitBtn').addClass('spinner spinner-white spinner-right').prop('disabled', true);

                $.ajax({
                    url: '{{ route('admin.news.store') }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        toastr.success(response.message);
                        setTimeout(() => {
                            window.location.href = '{{ route('admin.news.index') }}';
                        }, 1000);
                    },
                    error: function(xhr) {
                        $('#submitBtn').removeClass('spinner spinner-white spinner-right').prop(
                            'disabled', false);
                        let errors = xhr.responseJSON.errors;
                        let msg = 'Validation Error';
                        if (errors) {
                            msg = Object.values(errors).flat().join('<br>'); // Simple join
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
