@extends('layouts.index')

@section('title', 'Edit News')

@section('subheader')
    @component('layouts.partials._breadcrumbs')
        @slot('title')
            Edit Article
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
            <h3 class="card-title">Edit Article: {{ $post->title }}</h3>
        </div>
        <form id="newsForm" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="form-group">
                    <label>Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="title" value="{{ $post->title }}" required>
                </div>

                <div class="form-group">
                    <label>Category <span class="text-danger">*</span></label>
                    <select class="form-control" name="category_id" required>
                        <option value="">Select Category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ $post->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Excerpt (Short Description) <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="excerpt" rows="3">{{ $post->excerpt }}</textarea>
                    <span class="form-text text-muted">Brief summary of the article (max 500 chars).</span>
                </div>

                <div class="form-group">
                    <label>Content <span class="text-danger">*</span></label>
                    <textarea class="summernote" name="content" id="kt_summernote_1">{!! $post->content !!}</textarea>
                </div>

                <div class="form-group">
                    <label>Featured Image</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="image" id="customFile" accept="image/*">
                        <label class="custom-file-label" for="customFile">Change file</label>
                    </div>
                    @if ($post->image)
                        <div class="mt-3">
                            <img src="{{ asset('storage/' . $post->image) }}" width="150" class="img-thumbnail">
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <div class="radio-inline">
                        <label class="radio">
                            <input type="radio" name="status" value="published"
                                {{ $post->status == 'published' ? 'checked' : '' }}>
                            <span></span>
                            Published
                        </label>
                        <label class="radio">
                            <input type="radio" name="status" value="draft"
                                {{ $post->status == 'draft' ? 'checked' : '' }}>
                            <span></span>
                            Draft
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
                    url: '{{ route('admin.news.update', $post->id) }}',
                    type: 'POST', // Method PUT is handled by _method field, but file upload needs POST
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
