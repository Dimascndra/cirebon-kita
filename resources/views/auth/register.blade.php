@extends('layouts.public')

@section('content')
    <div class="d-flex flex-column flex-root">
        <div class="login login-4 login-signin-on d-flex flex-row-fluid" id="kt_login">
            <div class="d-flex flex-center flex-row-fluid bgi-size-cover bgi-position-top bgi-no-repeat">
                <div class="login-form text-center p-7 position-relative overflow-hidden"
                    style="background-image: url('{{ asset('assets/media/bg/bg-3.jpg') }}');">
                    <div class="d-flex flex-center mb-15">
                        <h1 class="text-dark font-weight-bolder">Cirebon<span class="text-primary">Kita</span></h1>
                    </div>
                    <div class="login-signin">
                        <div class="mb-20">
                            <h3>Daftar Akun Baru</h3>
                            <div class="text-muted font-weight-bold">Lengkapi data berikut untuk mendaftar:</div>
                        </div>
                        <form class="form" id="kt_login_signup_form">
                            <div class="form-group mb-5">
                                <input class="form-control h-auto form-control-solid py-4 px-8" type="text"
                                    placeholder="Nama Lengkap" name="fullname" id="fullname" />
                            </div>
                            <div class="form-group mb-5">
                                <input class="form-control h-auto form-control-solid py-4 px-8" type="text"
                                    placeholder="Email" name="email" id="email" autocomplete="off" />
                            </div>
                            <div class="form-group mb-5">
                                <input class="form-control h-auto form-control-solid py-4 px-8" type="password"
                                    placeholder="Password" name="password" id="password" />
                            </div>
                            <div class="form-group mb-5">
                                <input class="form-control h-auto form-control-solid py-4 px-8" type="password"
                                    placeholder="Konfirmasi Password" name="cpassword" id="cpassword" />
                            </div>
                            <div class="form-group d-flex flex-wrap flex-center mt-10">
                                <button id="kt_login_signup_submit"
                                    class="btn btn-primary font-weight-bold px-9 py-4 my-3 mx-2">Daftar</button>
                                <a href="{{ route('login') }}"
                                    class="btn btn-light-primary font-weight-bold px-9 py-4 my-3 mx-2">Batal</a>
                            </div>
                        </form>
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
        document.getElementById('kt_login_signup_submit').addEventListener('click', function(e) {
            e.preventDefault();
            const name = document.getElementById('fullname').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const cpassword = document.getElementById('cpassword').value;

            if (password !== cpassword) {
                toastr.error("Password konfirmasi tidak sama");
                return;
            }

            handleRegister(name, email, password, cpassword);
        });
    </script>
@endsection
