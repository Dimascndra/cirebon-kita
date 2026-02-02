// Helper definition for placeholder (reused)
function getPlaceholder(w, h, text) {
    const svg = `<svg width="${w}" height="${h}" xmlns="http://www.w3.org/2000/svg"><rect width="100%" height="100%" fill="#E4E6EF"/><text x="50%" y="50%" font-family="Arial" font-size="14" fill="#7E8299" dy=".3em" text-anchor="middle">${text}</text></svg>`;
    return "data:image/svg+xml;charset=UTF-8," + encodeURIComponent(svg);
}

const state = {
    page: 1,
    search: "",
    category: "",
    sort: "newest",
};

document.addEventListener("DOMContentLoaded", function () {
    // Initial Load
    fetchNews();

    // Event Listeners
    document
        .getElementById("btn-search")
        .addEventListener("click", function () {
            state.page = 1; // Reset to page 1 on filter
            state.search = document.getElementById("search-input").value;
            state.category = document.getElementById("category-filter").value;
            state.sort = document.getElementById("sort-filter").value;
            fetchNews();
        });

    document
        .getElementById("search-input")
        .addEventListener("keypress", function (e) {
            if (e.key === "Enter") {
                document.getElementById("btn-search").click();
            }
        });
});

function fetchNews() {
    const grid = document.getElementById("news-grid");
    grid.innerHTML =
        '<div class="col-12 text-center py-10"><div class="spinner spinner-primary spinner-lg"></div></div>'; // Skeleton/Loading

    const query = new URLSearchParams(state).toString();

    ajaxGet(
        `/api/news?${query}`,
        function (response) {
            renderNewsGrid(response.data);
            renderPagination(response);
        },
        function (error) {
            grid.innerHTML =
                '<div class="col-12 text-center"><div class="alert alert-danger">Gagal memuat berita.</div></div>';
        },
    );
}

function renderNewsGrid(posts) {
    const grid = document.getElementById("news-grid");

    if (posts.length === 0) {
        grid.innerHTML =
            '<div class="col-12 text-center py-10"><h3 class="text-muted">Tidak ada berita ditemukan.</h3></div>';
        return;
    }

    let html = "";
    posts.forEach((item) => {
        const date = new Date(item.published_at).toLocaleDateString("id-ID", {
            day: "numeric",
            month: "long",
            year: "numeric",
        });
        const image = item.image
            ? `/storage/${item.image}`
            : getPlaceholder(400, 250, "News Image");
        const category = item.category ? item.category.name : "Umum";

        html += `
            <div class="col-md-4 mb-5">
                <div class="card card-custom h-100 shadow-sm card-stretch">
                    <div class="card-body p-0">
                        <div class="overlay-wrapper p-4">
                            <img src="${image}" alt="${item.title}" class="w-100 rounded mb-4" style="height: 200px; object-fit: cover;">
                        </div>
                        <div class="px-5 pb-5">
                            <div class="d-flex align-items-center mb-3">
                                <span class="label label-light-primary label-inline font-weight-bold mr-2">${category}</span>
                                <span class="text-muted font-size-sm font-weight-bold">${date}</span>
                            </div>
                            <a href="/news/${item.slug}" class="text-dark-75 text-hover-primary font-size-h5 font-weight-bold mb-2 d-block text-truncate" title="${item.title}">${item.title}</a>
                            <p class="text-muted font-size-sm mb-0 line-clamp-3">${item.excerpt || ""}</p>
                        </div>
                    </div>
                     <div class="card-footer d-flex justify-content-between">
                         <a href="/news/${item.slug}" class="btn btn-text-primary btn-sm font-weight-bold text-uppercase">Baca Selengkapnya</a>
                         <span class="text-muted font-size-sm font-weight-bold align-self-center"><i class="flaticon-eye text-muted mr-1"></i>${item.views}</span>
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

    let html = '<ul class="pagination pagination-circle pagination-primary">';

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

// Global function to be called from onclick
window.changePage = function (page) {
    if (page < 1) return;
    state.page = page;
    fetchNews();

    // Scroll to top
    document
        .getElementById("kt_subheader")
        .scrollIntoView({ behavior: "smooth" });
};
