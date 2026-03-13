import React, { useEffect, useMemo, useState } from 'react';
import { Link } from 'react-router-dom';
import Icon from '../components/Icon';
import { api } from '../lib/api';
import { excerpt, formatCurrency, imageUrl } from '../lib/utils';

const initialState = {
    hero: null,
    news: [],
    jobs: [],
    categories: [],
    companies: [],
    banners: [],
};

function normalizeSection(payload) {
    const data = payload?.data ?? payload;
    return Array.isArray(data) ? data : data ?? null;
}

export default function HomePage() {
    const [sections, setSections] = useState(initialState);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState('');

    useEffect(() => {
        let active = true;

        Promise.all([
            api.get('/home/hero'),
            api.get('/home/news'),
            api.get('/home/jobs'),
            api.get('/home/categories'),
            api.get('/home/companies'),
            api.get('/home/banners'),
        ])
            .then(([hero, news, jobs, categories, companies, banners]) => {
                if (!active) {
                    return;
                }

                setSections({
                    hero: normalizeSection(hero.data),
                    news: normalizeSection(news.data) ?? [],
                    jobs: normalizeSection(jobs.data) ?? [],
                    categories: normalizeSection(categories.data) ?? [],
                    companies: normalizeSection(companies.data) ?? [],
                    banners: normalizeSection(banners.data) ?? [],
                });
            })
            .catch(() => {
                if (active) {
                    setError('Konten belum bisa ditampilkan sekarang. Silakan coba lagi sebentar lagi.');
                }
            })
            .finally(() => {
                if (active) {
                    setLoading(false);
                }
            });

        return () => {
            active = false;
        };
    }, []);

    const heroStyle = useMemo(() => {
        const image = imageUrl(sections.hero?.image) || sections.hero?.image_url || sections.hero?.banner_url;
        if (!image) {
            return undefined;
        }

        return {
            backgroundImage: `linear-gradient(135deg, rgba(9, 19, 44, 0.92), rgba(34, 84, 144, 0.72)), url(${image})`,
        };
    }, [sections.hero]);

    const topBanner = sections.banners?.[0] ?? null;
    const metrics = [
        { label: 'Berita terpilih', value: sections.news.length, icon: 'newspaper' },
        { label: 'Lowongan tersedia', value: sections.jobs.length, icon: 'briefcase' },
        { label: 'Perusahaan aktif', value: sections.companies.length, icon: 'building' },
    ];
    const pathways = [
        {
            title: 'Cari peluang kerja',
            description: 'Telusuri lowongan yang relevan dengan minat, lokasi, dan perusahaan tujuan Anda.',
            link: '/lowongan',
            label: 'Lihat lowongan',
            icon: 'briefcase',
        },
        {
            title: 'Ikuti kabar terbaru',
            description: 'Baca berita pilihan untuk memahami perkembangan yang sedang ramai dibicarakan.',
            link: '/berita',
            label: 'Baca berita',
            icon: 'newspaper',
        },
        {
            title: 'Kenali perusahaan',
            description: 'Lihat profil perusahaan dan temukan tempat kerja yang sesuai dengan arah karier Anda.',
            link: '/perusahaan',
            label: 'Jelajahi perusahaan',
            icon: 'building',
        },
    ];

    return (
        <div className="page page--home">
            <section className="hero" style={heroStyle}>
                <div className="container hero__layout">
                    <div className="hero__content">
                        <p className="eyebrow">Cirebon Raya</p>
                        <h1>{sections.hero?.title || 'Satu tempat untuk mencari peluang, membaca kabar, dan mengenal perusahaan.'}</h1>
                        <p>
                            {sections.hero?.excerpt ||
                                'CirebonKita membantu Anda mengikuti perkembangan daerah, menemukan lowongan, dan menilai perusahaan dengan lebih cepat.'}
                        </p>
                        <div className="hero__actions">
                            <Link className="button button--primary" to="/lowongan">
                                <Icon name="briefcase" />
                                Cari lowongan
                            </Link>
                            <Link className="button button--ghost-light" to="/berita">
                                <Icon name="newspaper" />
                                Jelajahi berita
                            </Link>
                        </div>
                        <div className="hero__metrics">
                            {metrics.map((item) => (
                                <div key={item.label} className="hero__metric">
                                    <span className="hero__metric-icon">
                                        <Icon name={item.icon} />
                                    </span>
                                    <strong>{item.value}</strong>
                                    <span>{item.label}</span>
                                </div>
                            ))}
                        </div>
                    </div>
                    <aside className="hero__panel surface-card surface-card--dark">
                        <p className="eyebrow">Mulai dari sini</p>
                        <h2>Pilih jalur yang paling dekat dengan kebutuhan Anda hari ini.</h2>
                        <div className="stack-list stack-list--tight">
                            <div className="metric-line metric-line--stacked metric-line--with-icon">
                                <span className="metric-line__icon">
                                    <Icon name="compass" />
                                </span>
                                <span>Untuk pencari kerja</span>
                                <strong>Lihat lowongan terbaru dan simpan target yang paling menarik.</strong>
                            </div>
                            <div className="metric-line metric-line--stacked metric-line--with-icon">
                                <span className="metric-line__icon">
                                    <Icon name="shield" />
                                </span>
                                <span>Untuk pembaca aktif</span>
                                <strong>Ikuti berita penting agar tidak tertinggal perubahan yang sedang terjadi.</strong>
                            </div>
                        </div>
                    </aside>
                </div>
            </section>

            <section className="page-section page-section--tight">
                <div className="container promo-band">
                    <div>
                        <p className="eyebrow">Pilihan cepat</p>
                        <h2>Masuk ke halaman yang paling Anda butuhkan tanpa harus berputar-putar.</h2>
                    </div>
                    <div className="promo-band__actions">
                        <Link className="button button--primary" to="/perusahaan">
                            <Icon name="building" />
                            Temukan perusahaan
                        </Link>
                        <Link className="button button--secondary" to="/register">
                            <Icon name="spark" />
                            Buat akun
                        </Link>
                    </div>
                </div>
            </section>

            <section className="page-section">
                <div className="container">
                    <div className="section-heading">
                        <div>
                            <p className="eyebrow">Jelajahi lebih cepat</p>
                            <h2>Tiga pintu utama untuk memulai.</h2>
                        </div>
                    </div>
                    <div className="card-grid card-grid--three">
                        {pathways.map((item) => (
                            <Link key={item.link} className="surface-card surface-card--link surface-card--feature" to={item.link}>
                                <span className="stat-icon">
                                    <Icon name={item.icon} />
                                </span>
                                <h3>{item.title}</h3>
                                <p>{item.description}</p>
                                <span className="inline-link">{item.label}</span>
                            </Link>
                        ))}
                    </div>
                </div>
            </section>

            {topBanner && (
                <section className="page-section page-section--tight">
                    <div className="container banner-card">
                        <span>Iklan unggulan</span>
                        <strong>{topBanner.title || topBanner.name || 'Promosi terbaru'}</strong>
                    </div>
                </section>
            )}

            <section className="page-section page-section--tight">
                <div className="container page-grid">
                    <div>
                        <div className="section-heading">
                            <div>
                                <p className="eyebrow">Berita regional</p>
                                <h2>Kabar yang layak dibaca untuk memahami gerak Cirebon hari ini.</h2>
                            </div>
                            <Link to="/berita">Lihat semua</Link>
                        </div>
                        <div className="card-grid card-grid--three">
                            {loading && <div className="surface-card">Memuat berita...</div>}
                            {!loading &&
                                sections.news.slice(0, 3).map((item) => (
                                    <Link key={item.id} to={`/news/${item.slug}`} className="surface-card surface-card--link">
                                        <p className="meta-text">{item.category?.name || 'Berita'}</p>
                                        <h3>{item.title}</h3>
                                        <p>{excerpt(item.excerpt)}</p>
                                    </Link>
                                ))}
                        </div>

                        <div className="section-heading section-heading--spaced">
                            <div>
                                <p className="eyebrow">Karier</p>
                                <h2>Peluang kerja yang bisa langsung Anda pertimbangkan.</h2>
                            </div>
                            <Link to="/lowongan">Lihat semua</Link>
                        </div>
                        <div className="stack-list">
                            {loading && <div className="surface-card">Memuat lowongan...</div>}
                            {!loading &&
                                sections.jobs.slice(0, 4).map((job) => (
                                    <Link key={job.id} to={`/lowongan/${job.slug}`} className="surface-card surface-card--row">
                                        <div>
                                            <p className="meta-text">{job.company?.name || 'Perusahaan'}</p>
                                            <h3>{job.title}</h3>
                                            <p>{job.location || 'Cirebon Raya'}</p>
                                        </div>
                                        <strong>{formatCurrency(job.salary_range)}</strong>
                                    </Link>
                                ))}
                        </div>
                    </div>

                    <aside className="sidebar-stack">
                        <div className="surface-card">
                            <p className="eyebrow">Topik populer</p>
                            <h2>Isu yang paling banyak menarik perhatian pembaca.</h2>
                            <div className="tag-list">
                                {sections.categories.slice(0, 10).map((category) => (
                                    <span key={category.id} className="tag">
                                        {category.name}
                                    </span>
                                ))}
                            </div>
                        </div>
                        <div className="surface-card surface-card--accent">
                            <p className="eyebrow">Perusahaan pilihan</p>
                            <h2>Beberapa perusahaan yang sedang aktif membuka peluang.</h2>
                            <div className="stack-list stack-list--tight">
                                {sections.companies.slice(0, 5).map((company) => (
                                    <Link key={company.id} to={`/perusahaan/${company.slug}`} className="company-row">
                                        <strong>{company.name}</strong>
                                        <span>{company.industry || 'Partner Cirebon Kita'}</span>
                                    </Link>
                                ))}
                            </div>
                        </div>
                    </aside>
                </div>
                {error && <div className="container notice notice--error">{error}</div>}
            </section>
        </div>
    );
}
