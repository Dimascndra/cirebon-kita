// Auth Logic

function handleLogin(email, password) {
    const btn = document.getElementById("kt_login_signin_submit");
    const originalText = btn.innerHTML;
    btn.innerHTML = "Loading...";
    btn.disabled = true;

    ajaxPost(
        "/login-web",
        { email, password },
        function (response) {
            localStorage.setItem("auth_token", response.token);
            toastr.success("Login berhasil");
            setTimeout(() => {
                window.location.href = "/";
            }, 1000);
        },
        function (error) {
            btn.innerHTML = originalText;
            btn.disabled = false;

            const msg = error.message || "Login gagal";
            toastr.error(msg);
        },
    );
}

function handleRegister(name, email, password, password_confirmation) {
    const btn = document.getElementById("kt_login_signup_submit");
    const originalText = btn.innerHTML;
    btn.innerHTML = "Loading...";
    btn.disabled = true;

    ajaxPost(
        "/api/register",
        { name, email, password, password_confirmation },
        function (response) {
            localStorage.setItem("auth_token", response.token);
            toastr.success("Registrasi berhasil! Mengalihkan...");
            setTimeout(() => {
                window.location.href = "/";
            }, 1500);
        },
        function (error) {
            btn.innerHTML = originalText;
            btn.disabled = false;

            let msg = error.message;
            if (error.errors) {
                msg = Object.values(error.errors).flat().join("<br>"); // Combine all errors
            }
            toastr.error(msg);
        },
    );
}

function handleLogout() {
    ajaxPost(
        "/api/logout",
        {},
        function (response) {
            localStorage.removeItem("auth_token");
            toastr.info("Anda telah keluar.");
            setTimeout(() => {
                window.location.href = "/login";
            }, 1000);
        },
        function (error) {
            localStorage.removeItem("auth_token");
            window.location.href = "/login";
        },
    );
}

function fetchProfile() {
    ajaxGet(
        "/api/me",
        function (user) {
            // Populate Profile Page
            const nameEl = document.getElementById("profile-name");
            if (nameEl) nameEl.innerText = user.name;

            const emailEl = document.getElementById("profile-email");
            if (emailEl) emailEl.innerText = user.email;

            const initialEl = document.getElementById("profile-initials");
            if (initialEl)
                initialEl.innerText = user.name.charAt(0).toUpperCase();

            const inputName = document.getElementById("input-name");
            if (inputName) inputName.value = user.name;

            const inputEmail = document.getElementById("input-email");
            if (inputEmail) inputEmail.value = user.email;
        },
        function (error) {
            console.error("Profile Error:", error);
            const msg = error.message || "Gagal memuat profil";
            toastr.error(msg);
            if (error.status === 401) {
                setTimeout(() => (window.location.href = "/login"), 2000);
            }
        },
    );
}

// Global Auth Check for Header
document.addEventListener("DOMContentLoaded", function () {
    checkNavBarAuth();
});

function checkNavBarAuth() {
    const token = localStorage.getItem("auth_token");
    const navContainer = document.querySelector(".topbar-item");

    if (!navContainer) return;

    if (token) {
        // Fetch User Data for Header
        ajaxGet(
            "/api/me",
            function (user) {
                renderLoggedInHeader(navContainer, user);
            },
            function (error) {
                // If token is invalid (401), clear it and show Guest header
                if (error.status === 401) {
                    localStorage.removeItem("auth_token");
                    renderGuestHeader(navContainer);
                }
                // If offline or other error, maybe keep cached user or do nothing
            },
        );
    } else {
        renderGuestHeader(navContainer);
    }
}

function renderLoggedInHeader(container, user) {
    const initial = user.name.charAt(0).toUpperCase();
    container.innerHTML = `
        <div class="dropdown">
            <div class="topbar-item" data-toggle="dropdown" data-offset="10px,0px">
                <div class="btn btn-icon btn-icon-mobile w-auto btn-clean d-flex align-items-center btn-lg px-2">
                    <span class="text-dark-50 font-weight-bolder font-size-base d-none d-md-inline mr-3">Akun Saya</span>
                    <span class="symbol symbol-35 symbol-light-success">
                        <span class="symbol-label font-size-h5 font-weight-bold">${initial}</span>
                    </span>
                </div>
            </div>
            <div class="dropdown-menu p-0 m-0 dropdown-menu-right dropdown-menu-anim-up dropdown-menu-lg">
                <div class="d-flex align-items-center justify-content-between flex-wrap p-8 bgi-size-cover bgi-no-repeat rounded-top" style="background-image: url('${window.location.origin}/assets/media/bg/bg-1.jpg')">
                    <div class="d-flex align-items-center mr-2">
                        <div class="symbol bg-white-o-15 mr-3">
                            <span class="symbol-label text-success font-weight-bold font-size-h4">${initial}</span>
                        </div>
                        <div class="text-white m-0 flex-grow-1 mr-3 font-size-h5">${user.name}</div>
                    </div>
                </div>
                <div class="navi navi-spacer-x-0 pt-5">
                    <a href="/dashboard" class="navi-item px-8">
                        <div class="navi-link">
                            <div class="navi-icon mr-2"><i class="flaticon2-pie-chart text-success"></i></div>
                            <div class="navi-text"><div class="font-weight-bold">Dashboard</div><div class="text-muted">Ke halaman utama</div></div>
                        </div>
                    </a>
                    <a href="/profile" class="navi-item px-8">
                        <div class="navi-link">
                            <div class="navi-icon mr-2"><i class="flaticon2-user-outline-symbol text-warning"></i></div>
                            <div class="navi-text"><div class="font-weight-bold">Profil Saya</div><div class="text-muted">Pengaturan akun</div></div>
                        </div>
                    </a>
                    <div class="navi-separator mt-3"></div>
                    <div class="navi-footer  px-8 py-5">
                        <a href="#" onclick="handleLogout(); return false;" class="btn btn-light-primary font-weight-bold">Sign Out</a>
                    </div>
                </div>
            </div>
        </div>
    `;
}

function renderGuestHeader(container) {
    // Current "Masuk/Daftar" button is already there in HTML initially,
    // but if we need to restore it after logout without refresh:
    // ... Or just ensure existing button works.
    // For now, assume if checkNavBarAuth is called on page load,
    // and token is missing, the static HTML from Blade (Guest view) is correct.
    // However, if we are handling dynamic state, we might need to verify the DOM.

    // The Blade template likely renders the Guest button by default if we haven't manipulated it.
    // But since we are replacing innerHTML, we should restore it if needed.
    // Let's assume the server-side Blade check might not be reliable if we are using SPA auth only.
    // Wait, the Blade layout doesn't use @auth check for this part, it's fully static?
    // Let's check layouts/public.blade.php content if needed.

    // For safety, let's keep the existing behaviour:
    const btn = container.querySelector(".btn");
    if (btn) {
        btn.onclick = function () {
            window.location.href = "/login";
        };
    }
}
