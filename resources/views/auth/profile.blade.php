@extends('layouts.public')

@section('content')
    <!-- SUBHEADER -->
    <div class="subheader py-5 py-lg-10 subheader-transparent bg-primary mb-10">
        <div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <h2 class="text-white font-weight-bold my-2 mr-5">Profil Saya</h2>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card card-custom gutter-b shadow-sm">
                    <div class="card-body text-center p-10">
                        <div class="symbol symbol-120 symbol-circle mb-5">
                            <span class="symbol-label font-size-h1 font-weight-bold bg-light-primary text-primary"
                                id="profile-initials">?</span>
                        </div>
                        <h3 class="font-weight-bold text-dark mb-2" id="profile-name">Loading...</h3>
                        <div class="text-muted mb-10" id="profile-email">...</div>

                        <div class="separator separator-solid mb-10"></div>

                        <div class="row text-left">
                            <div class="col-md-6 mb-5">
                                <label class="font-weight-bold">Nama Lengkap</label>
                                <input type="text" class="form-control form-control-solid" id="input-name" readonly>
                            </div>
                            <div class="col-md-6 mb-5">
                                <label class="font-weight-bold">Email</label>
                                <input type="text" class="form-control form-control-solid" id="input-email" readonly>
                            </div>
                        </div>

                        <button class="btn btn-danger font-weight-bold px-10 mt-5" onclick="handleLogout()">Keluar
                            (Logout)</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/ajax.js') }}"></script>
    <script src="{{ asset('js/auth.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check auth immediately
            const token = localStorage.getItem('auth_token');
            if (!token) {
                window.location.href = '/login';
                return;
            }
            fetchProfile();
        });
    </script>
@endsection
