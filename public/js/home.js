// Helper to generate SVG placeholders locally (works offline)
function getPlaceholder(w, h, text) {
    const svg = `<svg width="${w}" height="${h}" xmlns="http://www.w3.org/2000/svg"><rect width="100%" height="100%" fill="#E4E6EF"/><text x="50%" y="50%" font-family="Arial" font-size="14" fill="#7E8299" dy=".3em" text-anchor="middle">${text}</text></svg>`;
    return "data:image/svg+xml;charset=UTF-8," + encodeURIComponent(svg);
}

document.addEventListener("DOMContentLoaded", function () {
    loadHero();
    loadNews();
    loadJobs();
    loadCategories();
    loadCompanies();
    loadBanners();
});

function loadHero() {
    ajaxGet("/api/home/hero", function (data) {
        if (!data) {
            document.getElementById("hero-section").innerHTML =
                '<div class="alert alert-light">Belum ada headline.</div>';
            return;
        }

        const imageUrl = data.image
            ? `/storage/${data.image}`
            : getPlaceholder(1500, 600, "Cirebon Kita");
        const html = `
            <div class="hero-overlay"></div>
            <div class="container position-relative zindex-1">
                <h1 class="text-white font-weight-bolder display-3 mb-4">${data.title}</h1>
                <p class="text-white-50 font-size-h4 mb-8" style="max-width: 700px; margin: 0 auto;">
                    ${data.excerpt || ""}
                </p>
                <a href="/news/${data.slug}" class="btn btn-primary btn-lg font-weight-bold px-10">Baca Selengkapnya</a>
            </div>
        `;
        const heroSection = document.getElementById("hero-section");
        heroSection.style.backgroundImage = `url('${imageUrl}')`;
        heroSection.innerHTML = html;
    });
}

function loadNews() {
    ajaxGet("/api/home/news", function (data) {
        const container = document.getElementById("news-section");
        if (!data || data.length === 0) {
            container.innerHTML =
                '<div class="col-12"><div class="alert alert-light text-center">Belum ada berita.</div></div>';
            return;
        }

        let html = "";
        data.forEach((item) => {
            const date = new Date(item.published_at).toLocaleDateString(
                "id-ID",
                { day: "numeric", month: "long", year: "numeric" },
            );
            const image = item.image
                ? `/storage/${item.image}`
                : getPlaceholder(400, 250, "News Image");

            html += `
                <div class="col-md-6 col-lg-6 mb-5">
                    <div class="card card-custom h-100 shadow-sm">
                        <div class="card-body p-0">
                            <div class="overlay-wrapper p-4">
                                <img src="${image}" alt="${item.title}" class="w-100 rounded mb-4" style="height: 200px; object-fit: cover;">
                            </div>
                            <div class="px-5 pb-5">
                                <span class="text-muted font-size-sm font-weight-bold mb-2 d-block">${date}</span>
                                <a href="/news/${item.slug}" class="text-dark-75 text-hover-primary font-size-h5 font-weight-bold mb-2 d-block">${item.title}</a>
                                <p class="text-muted font-size-sm mb-0">${item.excerpt ? item.excerpt.substring(0, 100) + "..." : ""}</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        container.innerHTML = html;
    });
}

function loadJobs() {
    ajaxGet("/api/home/jobs", function (data) {
        const container = document.getElementById("jobs-section");
        if (!data || data.length === 0) {
            container.innerHTML =
                '<div class="alert alert-light text-center">Belum ada lowongan.</div>';
            return;
        }

        let html = "";
        data.forEach((item) => {
            const companyParams = item.company
                ? item.company
                : { name: "Perusahaan", logo: null };
            const logo = companyParams.logo
                ? `/storage/${companyParams.logo}`
                : getPlaceholder(50, 50, companyParams.name.substring(0, 1));

            html += `
                <div class="card card-custom gutter-b job-card border-0 mb-4 bg-light-white">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-50 symbol-light mr-4">
                                <span class="symbol-label">
                                    <img src="${logo}" class="h-75 align-self-center" alt="${companyParams.name}"/>
                                </span>
                            </div>
                            <div class="d-flex flex-column flex-grow-1">
                                <a href="/jobs/${item.slug}" class="text-dark-75 text-hover-primary font-weight-bolder font-size-lg mb-1">${item.title}</a>
                                <span class="text-muted font-weight-bold">${companyParams.name} • ${item.location}</span>
                            </div>
                            <div class="d-flex flex-column align-items-end">
                                <span class="label label-light-success label-inline font-weight-bold mb-2">${item.type}</span>
                                <span class="text-dark-50 font-weight-bold font-size-sm">${item.salary_range}</span>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        container.innerHTML = html;
    });
}

function loadCategories() {
    ajaxGet("/api/home/categories", function (data) {
        const container = document.getElementById("categories-section");
        if (!data || data.length === 0) {
            container.innerHTML =
                '<span class="text-muted">No categories</span>';
            return;
        }

        let html = "";
        data.forEach((item) => {
            html += `
                <a href="/category/${item.slug}" class="btn btn-light-primary btn-sm font-weight-bold mr-2 mb-2">
                    ${item.name} <span class="label label-sm label-white text-primary label-inline ml-2">${item.posts_count + item.jobs_count}</span>
                </a>
            `;
        });
        container.innerHTML = html;
    });
}

function loadCompanies() {
    ajaxGet("/api/home/companies", function (data) {
        const container = document.getElementById("companies-section");
        if (!data || data.length === 0) return;

        let html = "";
        data.forEach((item) => {
            const logo = item.logo
                ? `/storage/${item.logo}`
                : getPlaceholder(100, 60, item.name);
            html += `
                <div class="col-6 col-md-3 mb-5 text-center">
                    <div class="card card-custom">
                        <div class="card-body p-4 d-flex align-items-center justify-content-center" style="height: 100px;">
                             <img src="${logo}" style="max-width: 100%; max-height: 80px;" alt="${item.name}">
                        </div>
                    </div>
                </div>
            `;
        });
        container.innerHTML = html;
    });
}

function loadBanners() {
    ajaxGet("/api/home/banners", function (data) {
        // Top Banner
        if (data.top && data.top.length > 0) {
            const topBanner = data.top[0];
            const img = topBanner.image
                ? `/storage/${topBanner.image}`
                : getPlaceholder(1200, 150, "ADV");
            const html = `
                <a href="${topBanner.link || "#"}" target="_blank">
                    <img src="${img}" class="w-100 rounded" style="max-height: 150px; object-fit: cover;">
                </a>
            `;
            document.getElementById("top-banner-section").innerHTML = html;
        }

        if (data.sidebar && data.sidebar.length > 0) {
            let html = "";
            data.sidebar.forEach((ad) => {
                const img = ad.image
                    ? `/storage/${ad.image}`
                    : getPlaceholder(300, 250, "Sidebar ADS");
                html += `
                    <div class="mb-5">
                         <a href="${ad.link || "#"}" target="_blank">
                            <img src="${img}" class="w-100 rounded" style="max-height: 300px; object-fit: cover;">
                        </a>
                    </div>
                `;
            });
            document.getElementById("sidebar-ads-section").innerHTML = html;
        }

        // Homepage/Feed Banner
        if (data.homepage && data.homepage.length > 0) {
            const ad = data.homepage[0];
            const img = ad.image
                ? `/storage/${ad.image}`
                : getPlaceholder(800, 150, "Feed ADS");
            const html = `
                <a href="${ad.link || "#"}" target="_blank">
                    <img src="${img}" class="w-100 rounded shadow-sm" style="max-height: 200px; object-fit: cover;">
                </a>
            `;
            const el = document.getElementById("homepage-banner-section");
            if (el) el.innerHTML = html;
        }

        // Footer Banner
        if (data.footer && data.footer.length > 0) {
            const ad = data.footer[0];
            const img = ad.image
                ? `/storage/${ad.image}`
                : getPlaceholder(1200, 150, "Footer ADS");
            const html = `
                <a href="${ad.link || "#"}" target="_blank">
                    <img src="${img}" class="w-100 rounded shadow-sm" style="max-height: 200px; object-fit: cover;">
                </a>
            `;
            const el = document.getElementById("footer-banner-section");
            if (el) el.innerHTML = html;
        }
    });
}
