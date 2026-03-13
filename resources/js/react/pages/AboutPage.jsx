import React from 'react';

export default function AboutPage() {
    return (
        <div className="page">
            <section className="page-header">
                <div className="container">
                    <p className="eyebrow">Tentang platform</p>
                    <h1>CirebonKita dibangun untuk kecepatan akses dan kontrol operasional.</h1>
                    <p>
                        Laravel menangani business logic, autentikasi, dan API. React menangani seluruh antarmuka publik
                        dan workspace sebagai single-page application yang lebih konsisten.
                    </p>
                </div>
            </section>
            <section className="page-section page-section--tight">
                <div className="container card-grid card-grid--three">
                    <article className="surface-card">
                        <p className="eyebrow">Backend</p>
                        <h2>Laravel tetap menjadi pusat aturan bisnis.</h2>
                        <p>Controller, service, repository, auth, dan seluruh data tetap dikelola di sisi server.</p>
                    </article>
                    <article className="surface-card">
                        <p className="eyebrow">Frontend</p>
                        <h2>React menangani ritme interaksi.</h2>
                        <p>Routing, state halaman, filter, dan detail view sekarang terasa lebih cepat dan seragam.</p>
                    </article>
                    <article className="surface-card">
                        <p className="eyebrow">Integrasi</p>
                        <h2>Satu jalur data untuk semua area aplikasi.</h2>
                        <p>Halaman publik, user, company, dan admin sama-sama mengambil data dari endpoint Laravel.</p>
                    </article>
                </div>
            </section>
        </div>
    );
}
