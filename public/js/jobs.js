// Helper for placeholders
function getPlaceholder(w, h, text) {
    const svg = `<svg width="${w}" height="${h}" xmlns="http://www.w3.org/2000/svg"><rect width="100%" height="100%" fill="#E4E6EF"/><text x="50%" y="50%" font-family="Arial" font-size="14" fill="#7E8299" dy=".3em" text-anchor="middle">${text}</text></svg>`;
    return "data:image/svg+xml;charset=UTF-8," + encodeURIComponent(svg);
}

const state = {
    page: 1,
    search: "",
    location: "",
    type: "",
};

document.addEventListener("DOMContentLoaded", function () {
    // Initial Load
    fetchJobs();

    // Event Listeners
    document
        .getElementById("btn-search")
        .addEventListener("click", function () {
            state.page = 1; // Reset
            state.search = document.getElementById("search-input").value;
            state.location = document.getElementById("location-filter").value;
            state.type = document.getElementById("type-filter").value;
            fetchJobs();
        });

    document
        .getElementById("search-input")
        .addEventListener("keypress", function (e) {
            if (e.key === "Enter") {
                document.getElementById("btn-search").click();
            }
        });

    document
        .getElementById("location-filter")
        .addEventListener("change", function () {
            document.getElementById("btn-search").click();
        });

    document
        .getElementById("type-filter")
        .addEventListener("change", function () {
            document.getElementById("btn-search").click();
        });

    // Render Banner
    // Render Banner
    ajaxGet("/api/home/banners", function (data) {
        if (data.top && data.top.length > 0) {
            // Pick random ad
            const ad = data.top[Math.floor(Math.random() * data.top.length)];
            const imgEl = document.getElementById("top-banner-ad");

            if (imgEl) {
                const img = ad.image
                    ? `/storage/${ad.image}`
                    : getPlaceholder(1200, 120, "Advertisement");

                // Create link wrapper
                const link = document.createElement("a");
                link.href = ad.link || "#";
                link.target = "_blank";

                // Clone img to keep classes/styles but update src
                const newImg = imgEl.cloneNode(true);
                newImg.src = img;
                newImg.removeAttribute("id"); // Remove ID to avoid duplicate if we keep it

                link.appendChild(newImg);

                // Replace original img with link
                imgEl.parentNode.replaceChild(link, imgEl);
            }
        } else {
            // Fallback placeholder
            const imgEl = document.getElementById("top-banner-ad");
            if (imgEl) {
                imgEl.src = getPlaceholder(
                    1200,
                    120,
                    "Iklan Lowongan Kerja (Premium Slot)",
                );
            }
        }
    });
});

function fetchJobs() {
    const grid = document.getElementById("jobs-grid");
    grid.innerHTML =
        '<div class="col-12 text-center py-10"><div class="spinner spinner-success spinner-lg"></div></div>';

    const query = new URLSearchParams(state).toString();

    ajaxGet(
        `/api/jobs?${query}`,
        function (response) {
            renderJobsGrid(response.data);
            renderPagination(response);
        },
        function (error) {
            grid.innerHTML =
                '<div class="col-12 text-center"><div class="alert alert-danger">Gagal memuat lowongan.</div></div>';
        },
    );
}

function renderJobsGrid(jobs) {
    const grid = document.getElementById("jobs-grid");

    if (jobs.length === 0) {
        grid.innerHTML =
            '<div class="col-12 text-center py-10"><h3 class="text-muted">Tidak ada lowongan ditemukan.</h3></div>';
        return;
    }

    let html = "";
    jobs.forEach((item) => {
        // Safe access to company
        const companyName = item.company ? item.company.name : "Perusahaan";
        const companyLogo =
            item.company && item.company.logo
                ? `/storage/${item.company.logo}`
                : getPlaceholder(80, 80, companyName);
        const date = new Date(item.created_at).toLocaleDateString("id-ID", {
            day: "numeric",
            month: "long",
            year: "numeric",
        });

        html += `
            <div class="card card-custom gutter-b shadow-sm job-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-70 mr-5">
                            <img alt="${companyName}" src="${companyLogo}" style="object-fit: cover;">
                        </div>
                        <div class="d-flex flex-column flex-grow-1">
                            <a href="/lowongan/${item.slug}" class="text-dark-75 text-hover-primary mb-1 font-size-lg font-weight-bolder">${item.title}</a>
                            <span class="text-muted font-weight-bold">${companyName}</span>
                            <div class="d-flex mt-2">
                                <span class="label label-light-success label-inline font-weight-bold mr-2">${item.type}</span>
                                <span class="label label-light-info label-inline font-weight-bold mr-2">${item.location}</span>
                            </div>
                        </div>
                        <div class="d-flex flex-column align-items-end">
                            <span class="text-dark-75 font-weight-bold font-size-h6 mb-2">${item.salary_range}</span>
                            <span class="text-muted font-size-sm mb-4">${date}</span>
                            <a href="/lowongan/${item.slug}" class="btn btn-primary btn-sm font-weight-bold">Lamar Sekarang</a>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
    grid.innerHTML = html;
}

function renderPagination(response) {
    const container = document.getElementById("pagination");
    const { current_page, last_page } = response;

    if (last_page <= 1) {
        container.innerHTML = "";
        return;
    }

    let html = '<ul class="pagination pagination-circle pagination-success">';

    // Prev
    const prevDisabled = current_page === 1 ? "disabled" : "";
    html += `<li class="page-item ${prevDisabled}"><a class="page-link" href="#" onclick="changePage(${current_page - 1}); return false;"><i class="flaticon2-back"></i></a></li>`;

    // Pages
    for (let i = 1; i <= last_page; i++) {
        const active = i === current_page ? "active" : "";
        html += `<li class="page-item ${active}"><a class="page-link" href="#" onclick="changePage(${i}); return false;">${i}</a></li>`;
    }

    // Next
    const nextDisabled = current_page === last_page ? "disabled" : "";
    html += `<li class="page-item ${nextDisabled}"><a class="page-link" href="#" onclick="changePage(${current_page + 1}); return false;"><i class="flaticon2-next"></i></a></li>`;

    html += "</ul>";
    container.innerHTML = html;
}

// Global function
window.changePage = function (page) {
    if (page < 1) return;
    state.page = page;
    fetchJobs();

    // Scroll to top
    document
        .getElementById("kt_subheader")
        .scrollIntoView({ behavior: "smooth" });
};
