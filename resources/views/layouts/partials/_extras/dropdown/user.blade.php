<!--begin::Header-->
<div class="d-flex align-items-center justify-content-between flex-wrap p-8 bgi-size-cover bgi-no-repeat rounded-top"
    style="background-image: url({{ asset('assets/media/misc/bg-1.jpg') }})">
    <div class="d-flex align-items-center mr-2">

        <!--begin::Symbol-->
        <div class="symbol bg-white-o-15 mr-3">
            <span
                class="symbol-label text-success font-weight-bold font-size-h4">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
        </div>

        <!--end::Symbol-->

        <!--begin::Text-->
        <div class="text-white m-0 flex-grow-1 mr-3 font-size-h5">{{ auth()->user()->name }}</div>

        <!--end::Text-->
    </div>
</div>

<!--end::Header-->

<!--begin::Nav-->
<div class="navi navi-spacer-x-0 pt-5">

    <!--begin::Item-->
    <a href="{{ route('dashboard') }}" class="navi-item px-8">
        <div class="navi-link">
            <div class="navi-icon mr-2">
                <i class="flaticon2-pie-chart text-success"></i>
            </div>
            <div class="navi-text">
                <div class="font-weight-bold">
                    Dashboard
                </div>
                <div class="text-muted">
                    Ke halaman utama
                </div>
            </div>
        </div>
    </a>

    <!--end::Item-->

    <!--begin::Item-->
    <a href="{{ route('profile.index') }}" class="navi-item px-8">
        <div class="navi-link">
            <div class="navi-icon mr-2">
                <i class="flaticon2-user-outline-symbol text-warning"></i>
            </div>
            <div class="navi-text">
                <div class="font-weight-bold">
                    Profil Saya
                </div>
                <div class="text-muted">
                    Pengaturan akun
                </div>
            </div>
        </div>
    </a>

    <!--end::Item-->

    <!--begin::Footer-->
    <div class="navi-separator mt-3"></div>
    <div class="navi-footer  px-8 py-5">
        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <a href="{{ route('logout') }}" class="btn btn-light-primary font-weight-bold"
                onclick="event.preventDefault(); this.closest('form').submit();">Sign Out</a>
        </form>
    </div>

    <!--end::Footer-->
</div>

<!--end::Nav-->
