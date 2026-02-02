@extends('layouts.public')

@section('content')
    <div class="d-flex flex-column flex-root">
        <div class="login login-4 login-signin-on d-flex flex-row-fluid" id="kt_login">
            <div class="d-flex flex-center flex-row-fluid bgi-size-cover bgi-position-top bgi-no-repeat">
                <div class="login-form text-center p-7 position-relative overflow-hidden"
                    style="background-image: url('{{ asset('assets/media/bg/bg-3.jpg') }}');">
                    <div class="d-flex flex-center mb-15">
                        <a href="#">
                            <h1 class="text-dark font-weight-bolder">Cirebon<span class="text-primary">Kita</span></h1>
                        </a>
                    </div>
                    <div class="login-signin">
                        <div class="mb-20">
                            <h3>Masuk ke Akun</h3>
                            <div class="text-muted font-weight-bold">Masukkan detail akun Anda untuk masuk:</div>
                        </div>
                        <form class="form" id="kt_login_signin_form">
                            <div class="form-group mb-5">
                                <input class="form-control h-auto form-control-solid py-4 px-8" type="text"
                                    placeholder="Email" name="email" id="email" autocomplete="off" />
                            </div>
                            <div class="form-group mb-5">
                                <input class="form-control h-auto form-control-solid py-4 px-8" type="password"
                                    placeholder="Password" name="password" id="password" />
                            </div>
                            <div class="form-group d-flex flex-wrap justify-content-between align-items-center">
                                <div class="checkbox-inline">
                                    <label class="checkbox m-0 text-muted">
                                        <input type="checkbox" name="remember" />
                                        <span></span>Ingat Saya</label>
                                </div>
                                <a href="javascript:;" class="text-muted text-hover-primary">Lupa Password?</a>
                            </div>
                            <button id="kt_login_signin_submit"
                                class="btn btn-primary font-weight-bold px-9 py-4 my-3 mx-4">Masuk</button>
                        </form>
                        <div class="mt-10">
                            <span class="opacity-70 mr-4">Belum punya akun?</span>
                            <a href="{{ route('register') }}"
                                class="text-muted text-hover-primary font-weight-bold">Daftar</a>
                        </div>
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
        document.getElementById('kt_login_signin_submit').addEventListener('click', function(e) {
            e.preventDefault();
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            handleLogin(email, password);
        });
    </script>
@endsection
