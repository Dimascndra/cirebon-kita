@extends('layouts.public')

@section('content')
    <!-- SUBHEADER -->
    <div class="subheader py-5 py-lg-10 subheader-transparent bg-white border-bottom mb-10" id="kt_subheader">
        <div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <a href="{{ route('jobs.index') }}" class="btn btn-light-success btn-sm font-weight-bold mr-3"><i
                        class="flaticon2-back"></i> Kembali</a>
                <h5 class="text-dark font-weight-bold my-2 mr-5">Detail Lowongan</h5>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <!-- LEFT COLUMN: DETAIL -->
            <div class="col-lg-8">
                <div class="card card-custom gutter-b shadow-sm">
                    <div class="card-body" id="job-content">
                        <!-- AJAX LOADED -->
                        <div class="text-center py-10">
                            <div class="spinner spinner-success spinner-lg"></div>
                        </div>
                    </div>
                </div>

                <!-- RELATED JOBS -->
                <div class="mb-10">
                    <h4 class="font-weight-bold mb-5">Lowongan Serupa</h4>
                    <div class="row" id="related-jobs">
                        <!-- AJAX LOADED -->
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: SIDEBAR -->
            <div class="col-lg-4">
                <!-- COMPANY CARD -->
                <div class="card card-custom gutter-b shadow-sm">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title font-weight-bolder">Tentang Perusahaan</h3>
                    </div>
                    <div class="card-body text-center pt-0" id="company-card">
                        <!-- AJAX LOADED -->
                        <div class="spinner spinner-dark spinner-sm"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- APPLY MODAL -->
    <div class="modal fade" id="applyModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="applyForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title font-weight-bold">Lamar Pekerjaan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i aria-hidden="true" class="ki ki-close"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p class="font-size-lg mb-6">Anda akan melamar untuk <strong id="modal-job-title"></strong> di
                            <strong id="modal-company-name"></strong>
                        </p>

                        <div class="form-group">
                            <label class="font-weight-bolder">Upload CV <span class="text-danger">*</span></label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="cvFile" name="cv"
                                    accept=".pdf,.doc,.docx" required>
                                <label class="custom-file-label" for="cvFile">Pilih file CV...</label>
                            </div>
                            <small class="form-text text-muted">Format: PDF, DOC, DOCX (Max: 5MB)</small>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bolder">Cover Letter (Opsional)</label>
                            <textarea class="form-control" id="coverLetter" name="cover_letter" rows="4"
                                placeholder="Tulis pesan singkat untuk HRD, ceritakan mengapa Anda cocok untuk posisi ini..."></textarea>
                            <small class="form-text text-muted">Max 2000 karakter</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary font-weight-bold"
                            data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary font-weight-bold">
                            <i class="flaticon2-paper-plane"></i> Kirim Lamaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <input type="hidden" id="job-slug" value="{{ $slug }}">
@endsection

@section('title')
    {{ $job->title }}
@endsection

@section('meta_description')
    {{ Str::limit(strip_tags($job->description), 150) }}
@endsection

@section('meta_image')
    @if ($job->company && $job->company->logo)
        {{ asset('storage/' . $job->company->logo) }}
    @else
        {{ asset('assets/media/logos/logo-letter-1.png') }}
    @endif
@endsection

@section('meta_type', 'article')

{{-- Schema JSON-LD temporarily removed for debugging --}}

@section('scripts')
    <script src="{{ asset('js/ajax.js') }}"></script>
    <script src="{{ asset('js/job-detail.js') }}"></script>
@endsection
