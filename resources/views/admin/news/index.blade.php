@extends('layouts.index')

@section('title', 'News Management')

@section('subheader')
    @component('layouts.partials._breadcrumbs')
        @slot('title')
            News & Articles
        @endslot
        @slot('action')
            <a href="{{ route('admin.news.create') }}" class="btn btn-primary font-weight-bolder">
                <i class="flaticon2-plus"></i> New Article
            </a>
        @endslot
    @endcomponent
@endsection

@section('content')

    <div class="container-fluid">
        <style>
            .datatable-toggle-detail {
                display: none !important;
            }
        </style>

        <div class="row justify-content-center">
            <div class="col-12 col-xl-11">

                <div class="card card-custom shadow-sm">

                    {{-- HEADER --}}
                    <div class="card-header flex-wrap border-0 py-5">

                        <div class="card-title">
                            <h3 class="card-label mb-0">
                                Articles List
                                <span class="d-block text-muted font-size-sm mt-1">
                                    Manage news content and articles
                                </span>
                            </h3>
                        </div>

                        <div class="card-toolbar">

                            {{-- Export --}}
                            <div class="dropdown dropdown-inline">
                                <button class="btn btn-light-primary font-weight-bolder dropdown-toggle"
                                    data-toggle="dropdown">
                                    <i class="la la-download"></i> Export
                                </button>

                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="#"><i class="la la-print"></i> Print</a>
                                    <a class="dropdown-item" href="#"><i class="la la-file-excel-o"></i> Excel</a>
                                    <a class="dropdown-item" href="#"><i class="la la-file-text-o"></i> CSV</a>
                                    <a class="dropdown-item" href="#"><i class="la la-file-pdf-o"></i> PDF</a>
                                </div>
                            </div>

                        </div>

                    </div>


                    {{-- BODY --}}
                    <div class="card-body">

                        {{-- Search --}}
                        <div class="row mb-6 justify-content-between">

                            <div class="col-md-4">
                                <div class="input-icon">
                                    <input type="text" class="form-control" placeholder="Search articles..."
                                        id="kt_datatable_search_query" />
                                    <span><i class="flaticon2-search-1 text-muted"></i></span>
                                </div>
                            </div>

                            <div class="col-auto">
                                <a href="{{ route('admin.news.create') }}" class="btn btn-primary font-weight-bolder">
                                    <i class="flaticon2-plus"></i> New Article
                                </a>
                            </div>

                        </div>


                        {{-- Datatable --}}
                        <div class="table-responsive">
                            <div id="kt_datatable" class="datatable datatable-bordered datatable-head-custom">
                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </div>

    </div>

@endsection

@section('scripts')
    <script>
        "use strict";
        var KTDatatableNews = function() {
            var demo = function() {
                var datatable = $('#kt_datatable').KTDatatable({
                    data: {
                        type: 'remote',
                        source: {
                            read: {
                                url: '{{ route('admin.news.data') }}',
                                method: 'GET',
                                map: function(raw) {
                                    var dataSet = raw;
                                    if (typeof raw.data !== 'undefined') {
                                        dataSet = raw.data;
                                    }
                                    return dataSet;
                                },
                            },
                        },
                        pageSize: 10,
                        serverPaging: false,
                        serverFiltering: false,
                        serverSorting: false,
                    },

                    layout: {
                        scroll: true,
                        height: 550,
                        footer: false,
                        class: 'datatable-bordered',
                    },

                    sortable: true,
                    pagination: true,

                    search: {
                        input: $('#kt_datatable_search_query'),
                        key: 'generalSearch'
                    },

                    columns: [{
                        field: 'id',
                        title: 'ID',
                        sortable: 'asc',
                        width: 30,
                        type: 'number',
                        selector: false,
                        autoHide: false,
                        textAlign: 'center',
                    }, {
                        field: 'image',
                        title: 'Image',
                        width: 80,
                        autoHide: false,
                        textAlign: 'center',
                        template: function(row) {
                            return '<div class="symbol symbol-50 flex-shrink-0"><div class="symbol-label" style="background-image:url(\'' +
                                row.image + '\')"></div></div>';
                        }
                    }, {
                        field: 'title',
                        title: 'Title',
                        width: 450, // Force wider column
                        autoHide: false,
                        template: function(row) {
                            return '<div><a href="#" class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">' +
                                row.title + '</a></div>' +
                                '<div class="text-muted font-size-sm">' + (row.category ? row
                                    .category : '-') + '</div>';
                        }
                    }, {
                        field: 'category',
                        title: 'Category',
                        visible: false
                    }, {
                        field: 'status',
                        title: 'Status',
                        width: 100,
                        autoHide: false,
                        textAlign: 'center',
                        template: function(row) {
                            var status = {
                                'Published': {
                                    'title': 'Published',
                                    'class': ' label-light-success'
                                },
                                'Draft': {
                                    'title': 'Draft',
                                    'class': ' label-light-warning'
                                },
                            };
                            return '<span class="label font-weight-bold label-lg ' + status[row
                                    .status].class + ' label-inline">' + status[row.status]
                                .title + '</span>';
                        }
                    }, {
                        field: 'views',
                        title: 'Views',
                        width: 60,
                        autoHide: false,
                        textAlign: 'center',
                    }, {
                        field: 'published_at',
                        title: 'Published At',
                        width: 150,
                        autoHide: false,
                        textAlign: 'center',
                        template: function(row) {
                            return '<span class="text-muted font-weight-bold">' + row
                                .published_at + '</span>';
                        }
                    }, {
                        field: 'actions',
                        title: 'Actions',
                        sortable: false,
                        width: 100,
                        overflow: 'visible',
                        autoHide: false,
                        textAlign: 'center',
                        template: function(row) {
                            return row.actions;
                        },
                    }],
                });
            };

            return {
                init: function() {
                    demo();
                },
            };
        }();

        jQuery(document).ready(function() {
            KTDatatableNews.init();
        });

        function deleteNews(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/news/${id}`,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(res) {
                            toastr.success(res.message);
                            $('#kt_datatable').KTDatatable().reload();
                        },
                        error: function(err) {
                            toastr.error('Failed to delete');
                        }
                    });
                }
            });
        }
    </script>
@endsection
