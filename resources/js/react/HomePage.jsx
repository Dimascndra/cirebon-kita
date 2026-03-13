import React, { useEffect, useMemo, useState } from 'react';
import axios from 'axios';

const sectionDefaults = {
    hero: null,
    news: [],
    jobs: [],
    categories: [],
    companies: [],
    banners: [],
};

function normalizeResponse(payload) {
    if (Array.isArray(payload)) {
        return payload;
    }

    if (Array.isArray(payload?.data)) {
        return payload.data;
    }

    if (payload?.data && typeof payload.data === 'object') {
        return payload.data;
    }

    return payload ?? null;
}

function formatCurrency(value) {
    if (!value) {
        return 'Gaji negotiable';
    }

    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(Number(value));
}

function LoadingCard({ label }) {
    return <div className="react-home__loading">{label}</div>;
}

export default function HomePage({ endpoints }) {
    const [sections, setSections] = useState(sectionDefaults);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState('');

    useEffect(() => {
        let active = true;

        async function loadSections() {
            try {
                const [hero, news, jobs, categories, companies, banners] = await Promise.all([
                    axios.get(endpoints.hero),
                    axios.get(endpoints.news),
                    axios.get(endpoints.jobs),
                    axios.get(endpoints.categories),
                    axios.get(endpoints.companies),
                    axios.get(endpoints.banners),
                ]);

                if (!active) {
                    return;
                }

                setSections({
                    hero: normalizeResponse(hero.data),
                    news: normalizeResponse(news.data) ?? [],
                    jobs: normalizeResponse(jobs.data) ?? [],
                    categories: normalizeResponse(categories.data) ?? [],
                    companies: normalizeResponse(companies.data) ?? [],
                    banners: normalizeResponse(banners.data) ?? [],
                });
            } catch (loadError) {
                if (!active) {
                    return;
                }

                setError('Frontend React aktif, tetapi data homepage belum bisa dimuat.');
            } finally {
                if (active) {
                    setLoading(false);
                }
            }
        }

        loadSections();

        return () => {
            active = false;
        };
    }, [endpoints]);

    const heroStyle = useMemo(() => {
        const background = sections.hero?.image_url || sections.hero?.image || sections.hero?.banner_url;

        if (!background) {
            return undefined;
        }

        return {
            backgroundImage: `linear-gradient(135deg, rgba(24, 28, 50, 0.88), rgba(54, 153, 255, 0.62)), url(${background})`,
        };
    }, [sections.hero]);

    const topBanner = Array.isArray(sections.banners) ? sections.banners[0] : null;

    return (
        <div className="react-home">
            <section className="react-home__hero" style={heroStyle}>
                <div className="react-home__hero-inner">
                    <p className="react-home__eyebrow">Laravel backend, React frontend</p>
                    <h1>{sections.hero?.title || 'Portal berita dan lowongan kerja untuk Cirebon Raya'}</h1>
                    <p>
                        {sections.hero?.subtitle ||
                            'Data diambil dari endpoint Laravel dan dirender ulang oleh React di sisi frontend.'}
                    </p>
                    <div className="react-home__hero-actions">
                        <a href={endpoints.jobsIndex} className="react-home__button react-home__button--primary">
                            Lihat lowongan
                        </a>
                        <a href={endpoints.newsIndex} className="react-home__button react-home__button--ghost">
                            Baca berita
                        </a>
                    </div>
                </div>
            </section>

            <section className="react-home__search">
                <div className="react-home__search-card">
                    <div>
                        <h2>Cari peluang terbaru</h2>
                        <p>UI dikelola React, data tetap berasal dari Laravel.</p>
                    </div>
                    <div className="react-home__search-grid">
                        <input type="text" placeholder="Posisi atau kata kunci" />
                        <input type="text" placeholder="Lokasi" />
                        <a href={endpoints.jobsIndex} className="react-home__button react-home__button--primary">
                            Telusuri
                        </a>
                    </div>
                </div>
            </section>

            {topBanner && (
                <section className="react-home__banner">
                    <a href={topBanner.url || '#'} className="react-home__banner-card">
                        <span>Iklan unggulan</span>
                        <strong>{topBanner.title || topBanner.name || 'Promosi terbaru'}</strong>
                    </a>
                </section>
            )}

            <section className="react-home__content">
                <div className="react-home__main">
                    <div className="react-home__section-header">
                        <div>
                            <p className="react-home__eyebrow">Berita</p>
                            <h2>Berita terbaru</h2>
                        </div>
                        <a href={endpoints.newsIndex}>Lihat semua</a>
                    </div>

                    <div className="react-home__news-grid">
                        {loading && <LoadingCard label="Memuat berita..." />}
                        {!loading &&
                            sections.news.slice(0, 3).map((item, index) => (
                                <article key={item.id || index} className="react-home__news-card">
                                    <span>{item.category?.name || item.category || 'Berita'}</span>
                                    <h3>{item.title || item.name}</h3>
                                    <p>{item.excerpt || item.summary || 'Konten berita tersedia melalui backend Laravel.'}</p>
                                </article>
                            ))}
                    </div>

                    <div className="react-home__section-header">
                        <div>
                            <p className="react-home__eyebrow">Lowongan</p>
                            <h2>Lowongan kerja terbaru</h2>
                        </div>
                        <a href={endpoints.jobsIndex}>Lihat semua</a>
                    </div>

                    <div className="react-home__job-list">
                        {loading && <LoadingCard label="Memuat lowongan..." />}
                        {!loading &&
                            sections.jobs.slice(0, 4).map((job, index) => (
                                <article key={job.id || index} className="react-home__job-card">
                                    <div>
                                        <p className="react-home__job-company">
                                            {job.company?.name || job.company_name || 'Perusahaan'}
                                        </p>
                                        <h3>{job.title || job.position}</h3>
                                        <p>{job.location || job.city || 'Cirebon Raya'}</p>
                                    </div>
                                    <strong>{formatCurrency(job.salary || job.salary_min)}</strong>
                                </article>
                            ))}
                    </div>
                </div>

                <aside className="react-home__sidebar">
                    <div className="react-home__panel">
                        <p className="react-home__eyebrow">Kategori</p>
                        <h2>Jelajahi bidang</h2>
                        <div className="react-home__tag-list">
                            {loading && <LoadingCard label="Memuat kategori..." />}
                            {!loading &&
                                sections.categories.slice(0, 8).map((category, index) => (
                                    <span key={category.id || index} className="react-home__tag">
                                        {category.name || category.title}
                                    </span>
                                ))}
                        </div>
                    </div>

                    <div className="react-home__panel react-home__panel--accent">
                        <p className="react-home__eyebrow">Perusahaan</p>
                        <h2>Perusahaan unggulan</h2>
                        <div className="react-home__company-list">
                            {loading && <LoadingCard label="Memuat perusahaan..." />}
                            {!loading &&
                                sections.companies.slice(0, 5).map((company, index) => (
                                    <div key={company.id || index} className="react-home__company-item">
                                        <strong>{company.name}</strong>
                                        <span>{company.industry || company.city || 'Mitra CirebonKita'}</span>
                                    </div>
                                ))}
                        </div>
                    </div>
                </aside>
            </section>

            {error && <div className="react-home__error">{error}</div>}
        </div>
    );
}
