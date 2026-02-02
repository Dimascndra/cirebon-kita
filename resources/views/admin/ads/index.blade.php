@extends('layouts.index')

@section('title', 'Banner Ads Management')

@section('subheader')
    @component('layouts.partials._breadcrumbs')
        @slot('title')
            Banner Ads
        @endslot
        @slot('action')
            <button type="button" class="btn btn-primary font-weight-bolder" data-toggle="modal" data-target="#adModal">
                <i class="flaticon2-plus"></i> New Banner Ad
            </button>
        @endslot
    @endcomponent
@endsection

@section('content')
    <div class="container-fluid">
        {{-- Stats Cards --}}
        <div class="row mb-5">
            <div class="col-xl-3">
                <div class="card card-custom gutter-b">
                    <div class="card-body">
                        <span class="svg-icon svg-icon-3x svg-icon-success">
                            <i class="flaticon2-chart2 text-success icon-3x"></i>
                        </span>
                        <div class="text-dark font-weight-bolder font-size-h2 mt-3" id="totalAds">0</div>
                        <div class="font-weight-bold text-muted font-size-sm">Total Ads</div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3">
                <div class="card card-custom gutter-b">
                    <div class="card-body">
                        <span class="svg-icon svg-icon-3x svg-icon-primary">
                            <i class="flaticon2-check-mark text-primary icon-3x"></i>
                        </span>
                        <div class="text-dark font-weight-bolder font-size-h2 mt-3" id="activeAds">0</div>
                        <div class="font-weight-bold text-muted font-size-sm">Active Ads</div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3">
                <div class="card card-custom gutter-b">
                    <div class="card-body">
                        <span class="svg-icon svg-icon-3x svg-icon-warning">
                            <i class="flaticon2-cursor text-warning icon-3x"></i>
                        </span>
                        <div class="text-dark font-weight-bolder font-size-h2 mt-3" id="totalClicks">0</div>
                        <div class="font-weight-bold text-muted font-size-sm">Total Clicks</div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3">
                <div class="card card-custom gutter-b">
                    <div class="card-body">
                        <span class="svg-icon svg-icon-3x svg-icon-info">
                            <i class="flaticon2-pie-chart-3 text-info icon-3x"></i>
                        </span>
                        <div class="text-dark font-weight-bolder font-size-h2 mt-3" id="avgCtr">0%</div>
                        <div class="font-weight-bold text-muted font-size-sm">Avg CTR</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Datatable Card --}}
        <div class="card card-custom">
            <div class="card-header">
                <div class="card-title">
                    <h3 class="card-label">Banner Ads List</h3>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover" id="adsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Preview</th>
                            <th>Title</th>
                            <th>Placement</th>
                            <th>Schedule</th>
                            <th>Status</th>
                            <th>Clicks</th>
                            <th>Impressions</th>
                            <th>CTR</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    {{-- Create/Edit Modal --}}
    <div class="modal fade" id="adModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">New Banner Ad</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form id="adForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="adId" name="ad_id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>

                        <div class="form-group">
                            <label>Banner Image <span class="text-danger">*</span></label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="image" name="image"
                                    accept="image/*">
                                <label class="custom-file-label" for="image">Choose file</label>
                            </div>
                            <span class="form-text text-muted">Max file size: 2MB. Formats: JPG, PNG, GIF</span>
                            <div id="imagePreview" class="mt-3" style="display:none;">
                                <img src="" alt="Preview" class="img-thumbnail" style="max-height: 200px;">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Target URL</label>
                            <input type="url" class="form-control" id="url" name="url"
                                placeholder="https://example.com">
                        </div>

                        <div class="form-group">
                            <label>Placement <span class="text-danger">*</span></label>
                            <select class="form-control" id="placement" name="placement" required>
                                <option value="header">Header (Top Banner)</option>
                                <option value="sidebar">Sidebar (Right Column)</option>
                                <option value="homepage">Homepage Feed (Between Sections)</option>
                                <option value="footer">Footer (Bottom Wide)</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Start Date</label>
                                    <input type="datetime-local" class="form-control" id="start_date" name="start_date">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>End Date</label>
                                    <input type="datetime-local" class="form-control" id="end_date" name="end_date">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="checkbox-inline">
                                <label class="checkbox">
                                    <input type="checkbox" id="is_active" name="is_active" checked>
                                    <span></span>
                                    Active
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary font-weight-bold"
                            data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary font-weight-bold" id="submitBtn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let table;
        let isEdit = false;

        $(document).ready(function() {
            // Initialize DataTable
            table = $('#adsTable').DataTable({
                ajax: {
                    url: '{{ route('admin.ads.data') }}',
                    dataSrc: 'data'
                },
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'image',
                        render: function(data, type, row) {
                            return `<img src="${data}" alt="${row.title}" style="max-width: 100px; max-height: 60px;" class="img-thumbnail">`;
                        },
                        orderable: false
                    },
                    {
                        data: 'title'
                    },
                    {
                        data: 'placement'
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return `${row.start_date} <br> ${row.end_date}`;
                        }
                    },
                    {
                        data: 'status',
                        render: function(data, type, row) {
                            const badge = data === 'Active' ? 'success' : 'secondary';
                            return `<span class="label label-${badge} label-inline">${data}</span>`;
                        }
                    },
                    {
                        data: 'clicks'
                    },
                    {
                        data: 'impressions'
                    },
                    {
                        data: 'ctr'
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return `
                            <button class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit" onclick="editAd(${row.id}, '${row.title}', '${row.url}', '${row.placement}', '${row.start_date}', '${row.end_date}', ${row.is_active})">
                                <i class="la la-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Delete" onclick="deleteAd(${row.id})">
                                <i class="la la-trash"></i>
                            </button>
                        `;
                        },
                        orderable: false
                    }
                ],
                order: [
                    [0, 'desc']
                ],
                responsive: true,
            });

            // Image preview
            $('#image').on('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#imagePreview img').attr('src', e.target.result);
                        $('#imagePreview').show();
                    }
                    reader.readAsDataURL(file);
                    $('.custom-file-label').text(file.name);
                }
            });

            // Form submit
            $('#adForm').on('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const url = isEdit ? `/admin/ads/${$('#adId').val()}` : '{{ route('admin.ads.store') }}';

                if (isEdit) {
                    formData.append('_method', 'PUT');
                }

                $('#submitBtn').html('<i class="spinner spinner-white spinner-sm"></i> Saving...').prop(
                    'disabled', true);

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        toastr.success(response.message);
                        $('#adModal').modal('hide');
                        table.ajax.reload();
                        resetForm();
                    },
                    error: function(xhr) {
                        let message = 'Failed to save ad';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            message = Object.values(xhr.responseJSON.errors).flat().join(
                                '<br>');
                        }
                        toastr.error(message);
                    },
                    complete: function() {
                        $('#submitBtn').html('Save').prop('disabled', false);
                    }
                });
            });

            // Reset modal on close
            $('#adModal').on('hidden.bs.modal', function() {
                resetForm();
            });
        });

        function editAd(id, title, url, placement, startDate, endDate, isActive) {
            isEdit = true;
            $('#modalTitle').text('Edit Banner Ad');
            $('#adId').val(id);
            $('#title').val(title);
            $('#url').val(url);
            $('#placement').val(placement);
            $('#start_date').val(startDate !== '-' ? startDate.replace(' ', 'T') : '');
            $('#end_date').val(endDate !== '-' ? endDate.replace(' ', 'T') : '');
            $('#is_active').prop('checked', isActive);
            $('#image').removeAttr('required');
            $('#adModal').modal('show');
        }

        function deleteAd(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/ads/${id}`,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            toastr.success(response.message);
                            table.ajax.reload();
                        },
                        error: function(xhr) {
                            toastr.error('Failed to delete ad');
                        }
                    });
                }
            });
        }

        function resetForm() {
            isEdit = false;
            $('#modalTitle').text('New Banner Ad');
            $('#adForm')[0].reset();
            $('#adId').val('');
            $('#imagePreview').hide();
            $('.custom-file-label').text('Choose file');
            $('#image').attr('required', 'required');
        }
    </script>
@endsection
