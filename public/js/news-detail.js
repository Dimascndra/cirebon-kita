// Helper for placeholders
function getPlaceholder(w, h, text) {
    const svg = `<svg width="${w}" height="${h}" xmlns="http://www.w3.org/2000/svg"><rect width="100%" height="100%" fill="#E4E6EF"/><text x="50%" y="50%" font-family="Arial" font-size="14" fill="#7E8299" dy=".3em" text-anchor="middle">${text}</text></svg>`;
    return "data:image/svg+xml;charset=UTF-8," + encodeURIComponent(svg);
}

document.addEventListener("DOMContentLoaded", function () {
    const slug = document.getElementById("post-slug").value;
    if (slug) {
        loadPost(slug);
    }

    // Ads placeholder
    // Ads placeholder
    ajaxGet("/api/home/banners", function (data) {
        if (data.sidebar && data.sidebar.length > 0) {
            // Pick random ad
            const ad =
                data.sidebar[Math.floor(Math.random() * data.sidebar.length)];
            const imgEl = document.getElementById("sidebar-ad");

            if (imgEl) {
                const img = ad.image
                    ? `/storage/${ad.image}`
                    : getPlaceholder(300, 250, "Advertisement");

                // Create link wrapper
                const link = document.createElement("a");
                link.href = ad.link || "#";
                link.target = "_blank";

                // Clone img to keep classes/styles but update src
                const newImg = imgEl.cloneNode(true);
                newImg.src = img;
                newImg.removeAttribute("id");

                link.appendChild(newImg);

                // Replace original img with link
                imgEl.parentNode.replaceChild(link, imgEl);
            }
        } else {
            document.getElementById("sidebar-ad").src = getPlaceholder(
                300,
                250,
                "Sidebar Ad",
            );
        }
    });
});

function loadPost(slug) {
    ajaxGet(
        `/api/news/${slug}`,
        function (data) {
            const { post, related, trending } = data;

            renderArticle(post);
            renderRelated(related);
            renderTrending(trending);

            // Update Page Title
            document.title = `${post.title} - Cirebon Kita`;
        },
        function (error) {
            document.getElementById("article-content").innerHTML =
                '<div class="alert alert-danger text-center">Berita tidak ditemukan atau telah dihapus.</div>';
        },
    );
}

function renderArticle(post) {
    const container = document.getElementById("article-content");
    const image = post.image
        ? `/storage/${post.image}`
        : getPlaceholder(800, 400, "Headline Image");
    const date = new Date(post.published_at).toLocaleDateString("id-ID", {
        weekday: "long",
        day: "numeric",
        month: "long",
        year: "numeric",
    });
    const category = post.category ? post.category.name : "Umum";

    const html = `
        <div class="mb-5">
            <span class="label label-light-primary label-inline font-weight-bold mr-2 mb-2">${category}</span>
            <span class="text-muted font-weight-bold">${date}</span>
        </div>
        <h1 class="text-dark font-weight-bolder display-4 mb-5">${post.title}</h1>

        <div class="mb-10">
            <img src="${image}" class="w-100 rounded" style="max-height: 500px; object-fit: cover;" alt="${post.title}">
            <div class="text-muted font-size-sm mt-2">Dilihat ${post.views} kali</div>
        </div>

        <div class="font-size-lg text-dark-75 line-height-xl mb-10 text-justify">
            <p class="font-weight-bold font-size-h5 mb-5">${post.excerpt || ""}</p>
            ${post.content || "<p>Konten berita belum tersedia lengkap.</p>"}
        </div>

        <div class="separator separator-solid mb-5"></div>

        <div class="d-flex align-items-center">
            <span class="font-weight-bold mr-5">Bagikan:</span>
            <a href="#" class="btn btn-icon btn-circle btn-light-facebook mr-2"><i class="socicon-facebook"></i></a>
            <a href="#" class="btn btn-icon btn-circle btn-light-twitter mr-2"><i class="socicon-twitter"></i></a>
            <a href="#" class="btn btn-icon btn-circle btn-light-whatsapp"><i class="socicon-whatsapp"></i></a>
        </div>
    `;
    container.innerHTML = html;
}

function renderRelated(posts) {
    const container = document.getElementById("related-posts");
    if (!posts || posts.length === 0) {
        container.innerHTML =
            '<div class="col-12"><p class="text-muted">Tidak ada berita terkait.</p></div>';
        return;
    }

    let html = "";
    posts.forEach((item) => {
        const image = item.image
            ? `/storage/${item.image}`
            : getPlaceholder(400, 200, "Related");
        html += `
            <div class="col-md-4 mb-5">
                <div class="card card-custom shadow-xs h-100">
                    <div class="card-body p-3">
                         <img src="${image}" class="w-100 rounded mb-3" style="height: 120px; object-fit: cover;">
                         <a href="/news/${item.slug}" class="text-dark-75 text-hover-primary font-weight-bold font-size-xm mb-0 line-clamp-2">${item.title}</a>
                    </div>
                </div>
            </div>
        `;
    });
    container.innerHTML = html;
}

function renderTrending(posts) {
    const container = document.getElementById("trending-posts");
    if (!posts || posts.length === 0) {
        container.innerHTML =
            '<p class="text-muted">Belum ada data populer.</p>';
        return;
    }

    let html = "";
    posts.forEach((item, index) => {
        html += `
            <div class="d-flex align-items-center mb-5">
                <span class="font-weight-bolder text-dark-50 font-size-h2 mr-4">${index + 1}</span>
                <div class="d-flex flex-column">
                    <a href="/news/${item.slug}" class="text-dark py-0 mb-1 font-weight-bold text-hover-primary line-clamp-2">${item.title}</a>
                    <span class="text-muted font-size-sm">${item.views} Views</span>
                </div>
            </div>
        `;
    });
    container.innerHTML = html;
}
