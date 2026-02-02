// Helper for placeholders
function getPlaceholder(w, h, text) {
    const svg = `<svg width="${w}" height="${h}" xmlns="http://www.w3.org/2000/svg"><rect width="100%" height="100%" fill="#E4E6EF"/><text x="50%" y="50%" font-family="Arial" font-size="14" fill="#7E8299" dy=".3em" text-anchor="middle">${text}</text></svg>`;
    return "data:image/svg+xml;charset=UTF-8," + encodeURIComponent(svg);
}

document.addEventListener("DOMContentLoaded", function () {
    const slug = document.getElementById("job-slug").value;
    if (slug) {
        loadJob(slug);
    }
});

function loadJob(slug) {
    ajaxGet(
        `/api/jobs/${slug}`,
        function (data) {
            const { job, related } = data; // already unwrapped

            renderJobContent(job);
            renderCompanyCard(job.company);
            renderRelatedJobs(related);

            // Modal Data
            document.getElementById("modal-job-title").innerText = job.title;
            document.getElementById("modal-company-name").innerText =
                job.company ? job.company.name : "Perusahaan";

            // Update Page Title
            document.title = `${job.title} - Cirebon Kita`;
        },
        function (error) {
            document.getElementById("job-content").innerHTML =
                '<div class="alert alert-danger text-center">Lowongan tidak ditemukan atau telah ditutup.</div>';
        },
    );
}

function renderJobContent(job) {
    const container = document.getElementById("job-content");
    const date = new Date(job.created_at).toLocaleDateString("id-ID", {
        day: "numeric",
        month: "long",
        year: "numeric",
    });

    // Store job ID for apply form
    window.currentJobId = job.id;

    const html = `
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                 <h1 class="text-dark font-weight-bolder display-4 mb-2">${job.title}</h1>
                 <span class="text-muted font-weight-bold font-size-lg mr-2"><i class="flaticon2-pin mr-1"></i>${job.location}</span>
                 <span class="text-muted font-weight-bold font-size-lg"><i class="flaticon2-time mr-1"></i>${job.type}</span>
            </div>
            <div class="text-right">
                 <span class="label label-xl label-light-success label-inline font-weight-bolder font-size-h4 px-5 py-5">${job.salary_range}</span>
            </div>
        </div>

        <div class="separator separator-solid mb-5"></div>

        <div class="font-size-lg text-dark-75 line-height-xl mb-10">
            <h5 class="font-weight-bold text-dark mb-3">Deskripsi Pekerjaan</h5>
            <div class="mb-5">${job.description || "<p>Deskripsi belum tersedia.</p>"}</div>

            <h5 class="font-weight-bold text-dark mb-3">Persyaratan</h5>
            <ul class="mb-5">
                <li>Pendidikan minimal SMA/SMK/D3/S1 sesuai posisi.</li>
                <li>Memiliki pengalaman relevan (jika ada).</li>
                <li>Jujur, teliti, dan bertanggung jawab.</li>
                <li>Mampu bekerja dalam tim maupun individu.</li>
            </ul>

            <div class="alert alert-custom alert-light-warning fade show mb-5" role="alert">
                <div class="alert-icon"><i class="flaticon-warning"></i></div>
                <div class="alert-text">Hati-hati terhadap penipuan! Proses rekrutmen ini tidak dipungut biaya apapun.</div>
            </div>
        </div>

        <div class="d-flex justify-content-center">
             ${
                 job.has_applied
                     ? '<button class="btn btn-secondary btn-lg font-weight-bold px-10" disabled><i class="flaticon2-check-mark"></i> Sudah Melamar</button>'
                     : '<button class="btn btn-primary btn-lg font-weight-bold px-10" data-toggle="modal" data-target="#applyModal">Lamar Sekarang</button>'
             }
        </div>
    `;
    container.innerHTML = html;
}

function renderCompanyCard(company) {
    const container = document.getElementById("company-card");
    if (!company) {
        container.innerHTML =
            '<p class="text-muted">Info perusahaan tidak tersedia.</p>';
        return;
    }

    const logo = company.logo
        ? `/storage/${company.logo}`
        : getPlaceholder(100, 100, company.name);

    const html = `
        <div class="symbol symbol-100 symbol-circle mb-5">
            <img src="${logo}" alt="${company.name}" style="object-fit: cover;">
        </div>
        <h4 class="font-weight-bold text-dark mb-2">${company.name}</h4>
        <div class="text-muted mb-4">Industri & Layanan</div>

        ${company.website ? `<a href="${company.website}" target="_blank" class="btn btn-block btn-sm btn-light-primary font-weight-bold mb-2">Kunjungi Website</a>` : ""}

    `;
    container.innerHTML = html;
}

function renderRelatedJobs(jobs) {
    const container = document.getElementById("related-jobs");
    if (!jobs || jobs.length === 0) {
        container.innerHTML =
            '<div class="col-12"><p class="text-muted">Tidak ada lowongan serupa saat ini.</p></div>';
        return;
    }

    let html = "";
    jobs.forEach((item) => {
        html += `
            <div class="col-md-6 mb-5">
                <div class="card card-custom shadow-xs h-100">
                    <div class="card-body p-4">
                         <a href="/lowongan/${item.slug}" class="text-dark-75 text-hover-primary font-weight-bold font-size-h6 mb-1 text-truncate d-block">${item.title}</a>
                         <span class="text-muted font-size-sm font-weight-bold d-block">${item.location}</span>
                         <span class="label label-light-success label-inline font-weight-bold mt-2">${item.salary_range}</span>
                    </div>
                </div>
            </div>
        `;
    });
    container.innerHTML = html;
}

// Handle file input label update
$(document).on("change", "#cvFile", function () {
    const fileName = $(this).val().split("\\").pop();
    $(this)
        .next(".custom-file-label")
        .html(fileName || "Pilih file CV...");
});

// Handle apply form submission
$(document).on("submit", "#applyForm", function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    const jobId = window.currentJobId;

    if (!jobId) {
        toastr.error("Job ID not found", "Error");
        return;
    }

    // Show loading
    const submitBtn = $(this).find('button[type="submit"]');
    const originalHtml = submitBtn.html();
    submitBtn
        .html('<i class="spinner spinner-white spinner-sm"></i> Mengirim...')
        .prop("disabled", true);

    $.ajax({
        url: `/jobs/${jobId}/apply`,
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            $("#applyModal").modal("hide");
            toastr.success(
                response.message || "Lamaran berhasil dikirim!",
                "Sukses",
            );

            // Reload page after 1s to show "Sudah Melamar" button
            setTimeout(() => {
                location.reload();
            }, 1000);
        },
        error: function (xhr) {
            const message =
                xhr.responseJSON?.message ||
                "Terjadi kesalahan saat mengirim lamaran";
            toastr.error(message, "Error");

            // Reset button
            submitBtn.html(originalHtml).prop("disabled", false);
        },
    });
});
