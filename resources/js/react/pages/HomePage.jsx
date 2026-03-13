import React, { useEffect, useMemo, useState } from 'react';
import { Link } from 'react-router-dom';
import Icon from '../components/Icon';
import { api } from '../lib/api';
import { excerpt, formatCurrency, formatDate, imageUrl } from '../lib/utils';

const initialState = {
    hero: null,
    news: [],
    jobs: [],
    categories: [],
    companies: [],
    banners: {
        top: [],
        sidebar: [],
        homepage: [],
        footer: [],
    },
};

function normalizeSection(payload) {
    const data = payload?.data ?? payload;
    return Array.isArray(data) ? data : data ?? null;
}

function normalizeBanners(payload) {
    const data = payload?.data ?? payload ?? {};

    return {
        top: Array.isArray(data?.top) ? data.top : [],
        sidebar: Array.isArray(data?.sidebar) ? data.sidebar : [],
        homepage: Array.isArray(data?.homepage) ? data.homepage : [],
        footer: Array.isArray(data?.footer) ? data.footer : [],
    };
}

function companyInitials(name) {
    if (!name) {
        return 'CK';
    }

    return String(name)
        .split(' ')
        .filter(Boolean)
        .slice(0, 2)
        .map((part) => part[0])
        .join('')
        .toUpperCase();
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
                    banners: normalizeBanners(banners.data),
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
            backgroundImage: `url(${image})`,
            backgroundSize: 'cover',
            backgroundPosition: 'center',
        };
    }, [sections.hero]);

    const topBanner = sections.banners.homepage[0] ?? sections.banners.top[0] ?? null;
    const bannerImage = imageUrl(topBanner?.image) || topBanner?.image_url || null;
    const featuredNews = sections.news[0] ?? null;
    const secondaryNews = sections.news.slice(1, 4);
    const featuredJobs = sections.jobs.slice(0, 4);
    const featuredCompanies = sections.companies.slice(0, 5);
    const topCategories = sections.categories.slice(0, 8);
    const hasFeaturedNews = Boolean(featuredNews?.slug && featuredNews?.title);
    const hasFeaturedJobs = featuredJobs.length > 0;
    const hasCategories = topCategories.length > 0;
    const hasCompanies = featuredCompanies.length > 0;
    const metrics = [
        {
            label: 'Update berita',
            value: sections.news.length || 0,
            helper: 'pilihan editorial',
            icon: 'newspaper',
        },
        {
            label: 'Peluang kerja',
            value: sections.jobs.length || 0,
            helper: 'siap ditinjau',
            icon: 'briefcase',
        },
        {
            label: 'Perusahaan',
            value: sections.companies.length || 0,
            helper: 'partner terkurasi',
            icon: 'building',
        },
    ];
    const pathways = [
        {
            title: 'Peta lowongan',
            description: 'Cari posisi yang paling relevan.',
            link: '/lowongan',
            label: 'Telusuri lowongan',
            icon: 'briefcase',
            accent: 'from-cyan-500/15 to-blue-500/5 border-cyan-200',
        },
        {
            title: 'Ruang berita',
            description: 'Lihat kabar penting hari ini.',
            link: '/berita',
            label: 'Buka berita',
            icon: 'newspaper',
            accent: 'from-amber-500/15 to-orange-500/5 border-amber-200',
        },
        {
            title: 'Direktori perusahaan',
            description: 'Kenali perusahaan sebelum melamar.',
            link: '/perusahaan',
            label: 'Lihat perusahaan',
            icon: 'building',
            accent: 'from-emerald-500/15 to-teal-500/5 border-emerald-200',
        },
    ];

    return (
        <div className="public-page-shell min-h-screen bg-[#f5f8fc] text-slate-900">
            <section className="public-page-hero relative overflow-hidden border-b border-slate-200 bg-[#eef5fb] text-slate-900 dark:border-slate-800 dark:bg-slate-950">
                <div className="absolute inset-0" style={heroStyle} />
                <div className="absolute inset-0 dark:hidden bg-[radial-gradient(circle_at_top_left,_rgba(56,189,248,0.14),_transparent_34%),radial-gradient(circle_at_85%_20%,_rgba(59,130,246,0.10),_transparent_26%),linear-gradient(180deg,_rgba(255,255,255,0.42),_rgba(238,245,251,0.78))]" />
                <div className="absolute inset-0 hidden dark:block bg-[radial-gradient(circle_at_top_left,_rgba(34,211,238,0.14),_transparent_34%),radial-gradient(circle_at_85%_20%,_rgba(59,130,246,0.16),_transparent_26%),linear-gradient(180deg,_rgba(2,6,23,0.82),_rgba(15,23,42,0.94))]" />
                <div className="absolute -left-20 top-24 h-64 w-64 rounded-full bg-cyan-400/10 blur-3xl dark:bg-cyan-400/20" />
                <div className="absolute right-0 top-0 h-72 w-72 rounded-full bg-blue-400/10 blur-3xl dark:bg-blue-500/20" />

                <div className="relative mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8 lg:pt-24 lg:pb-28">
                    <div className="grid gap-10 lg:grid-cols-[minmax(0,1.55fr)_minmax(320px,0.95fr)] lg:items-start">
                        <div className="space-y-8">
                            <div className="space-y-5">
                                <span className="inline-flex items-center rounded-full border border-cyan-200 bg-white/80 px-4 py-1.5 text-sm font-medium text-cyan-800 shadow-sm backdrop-blur dark:border-slate-700 dark:bg-slate-950/80 dark:text-cyan-300">
                                    Hub informasi regional Cirebon
                                </span>
                                <div className="space-y-4">
                                    <h1 className="max-w-4xl text-4xl font-semibold leading-tight tracking-tight text-slate-950 dark:text-white sm:text-5xl lg:text-6xl">
                                        {sections.hero?.title || 'Kabar, lowongan, dan perusahaan dalam satu tempat.'}
                                    </h1>
                                    <p className="max-w-2xl text-lg leading-8 text-slate-600 dark:text-slate-300 sm:text-xl">
                                        {sections.hero?.excerpt ||
                                            'Berita, lowongan, dan perusahaan dalam satu tempat.'}
                                    </p>
                                </div>
                            </div>

                            <div className="flex flex-col gap-3 sm:flex-row">
                                <Link
                                    className="inline-flex items-center justify-center rounded-full bg-blue-600 px-6 py-3.5 text-sm font-semibold text-white transition hover:bg-blue-700"
                                    to="/lowongan"
                                >
                                    <Icon name="briefcase" className="mr-2 h-4 w-4" />
                                    Mulai dari lowongan
                                </Link>
                                <Link
                                    className="inline-flex items-center justify-center rounded-full border border-slate-300 bg-white px-6 py-3.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-400 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-800"
                                    to="/berita"
                                >
                                    <Icon name="newspaper" className="mr-2 h-4 w-4" />
                                    Lihat berita terbaru
                                </Link>
                            </div>

                            <div className="grid gap-4 sm:grid-cols-3">
                                {metrics.map((item) => (
                                    <div
                                        key={item.label}
                                        className="rounded-2xl border border-slate-200 bg-white/90 p-5 shadow-[0_20px_80px_rgba(15,23,42,0.08)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/90"
                                    >
                                        <div className="mb-5 inline-flex rounded-2xl bg-cyan-50 p-3 text-cyan-700 dark:bg-cyan-500/15 dark:text-cyan-300">
                                            <Icon name={item.icon} className="h-5 w-5" />
                                        </div>
                                        <div className="text-3xl font-semibold text-slate-950 dark:text-white">{item.value}</div>
                                        <div className="mt-1 text-sm font-medium text-slate-700 dark:text-slate-200">{item.label}</div>
                                        <div className="mt-1 text-xs uppercase tracking-[0.22em] text-slate-500 dark:text-slate-400">{item.helper}</div>
                                    </div>
                                ))}
                            </div>
                        </div>

                        <div className="space-y-4">
                            <div className="rounded-[28px] border border-slate-200 bg-white/90 p-6 shadow-[0_30px_90px_rgba(15,23,42,0.10)] backdrop-blur-xl dark:border-slate-800 dark:bg-slate-900/95">
                                <div className="mb-5 flex items-center justify-between">
                                    <div>
                                        <p className="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 dark:text-slate-400">Sorotan hari ini</p>
                                        <h2 className="mt-2 text-xl font-semibold text-slate-950 dark:text-white">Masuk dari headline terpenting</h2>
                                    </div>
                                    <div className="rounded-full border border-slate-200 bg-slate-50 p-2 text-cyan-700 dark:border-slate-700 dark:bg-slate-800 dark:text-cyan-300">
                                        <Icon name="compass" className="h-5 w-5" />
                                    </div>
                                </div>

                                {hasFeaturedNews ? (
                                    <Link
                                        to={`/news/${featuredNews.slug}`}
                                        className="group block rounded-3xl border border-slate-200 bg-slate-50 p-5 transition hover:border-cyan-300 hover:bg-cyan-50/40 dark:border-slate-800 dark:bg-slate-950/60 dark:hover:border-cyan-500 dark:hover:bg-slate-950"
                                    >
                                        <div className="mb-3 flex items-center justify-between gap-4">
                                            <span className="rounded-full bg-white px-3 py-1 text-xs font-medium text-cyan-700 shadow-sm ring-1 ring-slate-200 dark:bg-slate-900 dark:text-cyan-300 dark:ring-slate-700">
                                                {featuredNews.category?.name || 'Berita utama'}
                                            </span>
                                            <span className="text-xs text-slate-500 dark:text-slate-400">
                                                {formatDate(featuredNews.published_at || featuredNews.created_at)}
                                            </span>
                                        </div>
                                        <h3 className="text-lg font-semibold leading-7 text-slate-950 transition group-hover:text-cyan-700 dark:text-white dark:group-hover:text-cyan-300">
                                            {featuredNews.title}
                                        </h3>
                                        <p className="mt-3 text-sm leading-6 text-slate-600 dark:text-slate-300">
                                            {excerpt(featuredNews.excerpt, 'Ringkasan penting untuk membantu Anda membaca konteks lebih cepat.')}
                                        </p>
                                        <div className="mt-5 inline-flex items-center text-sm font-semibold text-cyan-700 dark:text-cyan-300">
                                            Baca sorotan
                                            <Icon name="arrow-right" className="ml-2 h-4 w-4 transition group-hover:translate-x-1" />
                                        </div>
                                    </Link>
                                ) : (
                                    <div className="rounded-3xl border border-dashed border-slate-200 bg-slate-50 p-6 text-sm text-slate-500 dark:border-slate-700 dark:bg-slate-950/60 dark:text-slate-400">
                                        Belum ada berita utama.
                                    </div>
                                )}
                            </div>

                            <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-1">
                                <div className="rounded-[24px] border border-slate-200/70 bg-white p-5 text-slate-900 shadow-[0_20px_60px_rgba(15,23,42,0.08)] dark:border-slate-800 dark:bg-slate-900">
                                    <p className="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500 dark:text-slate-400">Alur tercepat</p>
                                    <div className="mt-4 space-y-4">
                                        <div className="flex items-start gap-3">
                                            <div className="rounded-2xl bg-cyan-50 p-2.5 text-cyan-700 dark:bg-cyan-500/15 dark:text-cyan-300">
                                                <Icon name="briefcase" className="h-5 w-5" />
                                            </div>
                                            <div>
                                                <div className="font-semibold text-slate-900 dark:text-white">Mencari kerja</div>
                                                <div className="mt-1 text-sm leading-6 text-slate-600 dark:text-slate-300">Lihat lowongan terbaru.</div>
                                            </div>
                                        </div>
                                        <div className="flex items-start gap-3">
                                            <div className="rounded-2xl bg-amber-50 p-2.5 text-amber-700 dark:bg-amber-500/15 dark:text-amber-300">
                                                <Icon name="newspaper" className="h-5 w-5" />
                                            </div>
                                            <div>
                                                <div className="font-semibold text-slate-900 dark:text-white">Memantau isu</div>
                                                <div className="mt-1 text-sm leading-6 text-slate-600 dark:text-slate-300">Baca kabar yang sedang ramai.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div className="rounded-[24px] border border-blue-100 bg-gradient-to-br from-blue-50 via-white to-cyan-50 p-5 text-slate-900 shadow-[0_20px_60px_rgba(15,23,42,0.05)] dark:border-slate-800 dark:bg-gradient-to-br dark:from-slate-900 dark:via-slate-900 dark:to-cyan-950/40">
                                    <p className="text-xs font-semibold uppercase tracking-[0.24em] text-blue-700 dark:text-cyan-300">Akun pribadi</p>
                                    <h3 className="mt-3 text-lg font-semibold text-slate-950 dark:text-white">Simpan langkah Anda.</h3>
                                    <p className="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-300">
                                        Buat akun untuk melanjutkan lebih cepat.
                                    </p>
                                    <Link
                                        className="mt-5 inline-flex items-center rounded-full bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700 dark:bg-white dark:text-slate-950 dark:hover:bg-slate-100"
                                        to="/register"
                                    >
                                        Buat akun
                                        <Icon name="arrow-right" className="ml-2 h-4 w-4" />
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>

                    {error && (
                        <div className="mt-8 rounded-2xl border border-rose-200/70 bg-rose-50/95 px-5 py-4 text-sm text-rose-800">
                            {error}
                        </div>
                    )}
                </div>
            </section>

            <section className="relative z-10 bg-transparent pb-12 pt-10 lg:pb-14 lg:pt-8">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div className="grid gap-4 md:grid-cols-3">
                        {pathways.map((item) => (
                            <Link
                                key={item.link}
                                className={`group flex min-h-[220px] flex-col rounded-[28px] border bg-gradient-to-br ${item.accent} p-6 shadow-[0_22px_60px_rgba(15,23,42,0.08)] transition hover:-translate-y-1 hover:shadow-[0_24px_70px_rgba(15,23,42,0.12)] dark:border-slate-800 dark:from-slate-950 dark:to-slate-900`}
                                to={item.link}
                            >
                                <div className="flex items-center justify-between">
                                    <div className="rounded-2xl bg-white/80 p-3 text-slate-900 shadow-sm dark:bg-slate-900 dark:text-cyan-300">
                                        <Icon name={item.icon} className="h-5 w-5" />
                                    </div>
                                    <span className="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-400">Navigasi</span>
                                </div>
                                <h2 className="mt-6 text-2xl font-semibold text-slate-950 dark:text-white">{item.title}</h2>
                                <p className="mt-3 text-sm leading-6 text-slate-600 dark:text-slate-300">{item.description}</p>
                                <div className="mt-auto pt-6 inline-flex items-center text-sm font-semibold text-slate-900 dark:text-slate-100">
                                    {item.label}
                                    <Icon name="arrow-right" className="ml-2 h-4 w-4 transition group-hover:translate-x-1" />
                                </div>
                            </Link>
                        ))}
                    </div>
                </div>
            </section>

            {topBanner && (
                <section className="pb-6 pt-4">
                    <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        <a
                            className="group block overflow-hidden rounded-[32px] border border-slate-200 bg-white shadow-[0_28px_90px_rgba(15,23,42,0.08)]"
                            href={topBanner.link || '#'}
                        >
                            <div className="grid gap-0 lg:grid-cols-[minmax(0,1.1fr)_360px]">
                                <div className="relative overflow-hidden bg-gradient-to-br from-blue-50 via-white to-cyan-50 px-6 py-8 sm:px-8 sm:py-10">
                                    <div className="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(56,189,248,0.10),_transparent_28%)]" />
                                    <div className="relative max-w-2xl">
                                        <span className="inline-flex rounded-full border border-cyan-200 bg-white px-3 py-1 text-xs font-semibold uppercase tracking-[0.24em] text-cyan-700">
                                            Iklan unggulan
                                        </span>
                                        <h2 className="mt-4 text-2xl font-semibold text-slate-950 sm:text-3xl">
                                            {topBanner.title || 'Promosi pilihan untuk pengunjung CirebonKita'}
                                        </h2>
                                        <p className="mt-3 max-w-xl text-sm leading-7 text-slate-600">
                                            Slot promosi ini ditempatkan dekat area keputusan agar kampanye lebih mudah terlihat tanpa mengganggu ritme baca.
                                        </p>
                                        <div className="mt-6 inline-flex items-center text-sm font-semibold text-cyan-700">
                                            Buka promosi
                                            <Icon name="arrow-right" className="ml-2 h-4 w-4 transition group-hover:translate-x-1" />
                                        </div>
                                    </div>
                                </div>
                                <div className="relative min-h-[220px] bg-slate-100">
                                    {bannerImage ? (
                                        <img
                                            src={bannerImage}
                                            alt={topBanner.title || 'Banner promosi'}
                                            className="h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]"
                                        />
                                    ) : (
                                        <div className="flex h-full items-center justify-center bg-gradient-to-br from-cyan-100 via-slate-100 to-amber-100">
                                            <div className="rounded-full bg-white p-4 text-slate-900 shadow-lg">
                                                <Icon name="spark" className="h-8 w-8" />
                                            </div>
                                        </div>
                                    )}
                                </div>
                            </div>
                        </a>
                    </div>
                </section>
            )}

            <section className="pb-20 pt-6">
                <div className="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-[minmax(0,1.6fr)_360px] lg:px-8">
                    <div className="space-y-10">
                        <section className="rounded-[32px] border border-slate-200 bg-white p-6 shadow-[0_24px_80px_rgba(15,23,42,0.06)] sm:p-8">
                            <div className="mb-8 flex flex-col gap-4 border-b border-slate-200 pb-6 sm:flex-row sm:items-end sm:justify-between">
                                <div>
                                    <span className="inline-flex rounded-full bg-cyan-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.22em] text-cyan-700">
                                        Berita pilihan
                                    </span>
                                    <h2 className="mt-4 text-3xl font-semibold tracking-tight text-slate-950">
                                        Baca kabar utama.
                                    </h2>
                                    <p className="mt-3 max-w-2xl text-sm leading-7 text-slate-600">
                                        Ringkas, cepat, dan langsung ke inti.
                                    </p>
                                </div>
                                <Link
                                    to="/berita"
                                    className="inline-flex items-center text-sm font-semibold text-slate-900 transition hover:text-cyan-700"
                                >
                                    Lihat semua berita
                                    <Icon name="arrow-right" className="ml-2 h-4 w-4" />
                                </Link>
                            </div>

                            {loading ? (
                                <div className="grid gap-5 lg:grid-cols-[minmax(0,1.2fr)_minmax(0,0.8fr)]">
                                    <div className="animate-pulse rounded-[28px] bg-slate-100 p-8">
                                        <div className="h-4 w-28 rounded bg-slate-200" />
                                        <div className="mt-6 h-8 w-4/5 rounded bg-slate-200" />
                                        <div className="mt-3 h-4 w-full rounded bg-slate-200" />
                                        <div className="mt-2 h-4 w-11/12 rounded bg-slate-200" />
                                    </div>
                                    <div className="space-y-4">
                                        {[1, 2, 3].map((item) => (
                                            <div key={item} className="animate-pulse rounded-[24px] bg-slate-100 p-5">
                                                <div className="h-4 w-20 rounded bg-slate-200" />
                                                <div className="mt-4 h-5 w-5/6 rounded bg-slate-200" />
                                                <div className="mt-2 h-4 w-full rounded bg-slate-200" />
                                            </div>
                                        ))}
                                    </div>
                                </div>
                            ) : hasFeaturedNews ? (
                                <div className="grid gap-5 lg:grid-cols-[minmax(0,1.18fr)_minmax(0,0.82fr)]">
                                    <Link
                                        to={`/news/${featuredNews.slug}`}
                                        className="group rounded-[28px] border border-slate-200 bg-slate-50 p-7 text-slate-900 transition hover:border-cyan-300 hover:bg-cyan-50/30 dark:border-slate-800 dark:bg-slate-950 dark:text-white dark:hover:border-cyan-500 dark:hover:bg-slate-900"
                                    >
                                        <div className="flex items-center justify-between gap-4 text-sm">
                                            <span className="rounded-full bg-white px-3 py-1 text-cyan-700 shadow-sm ring-1 ring-slate-200 dark:bg-slate-900 dark:text-cyan-300 dark:ring-slate-700">
                                                {featuredNews.category?.name || 'Berita'}
                                            </span>
                                            <span className="text-slate-500 dark:text-slate-400">
                                                {formatDate(featuredNews.published_at || featuredNews.created_at)}
                                            </span>
                                        </div>
                                        <h3 className="mt-6 text-3xl font-semibold leading-tight tracking-tight transition group-hover:text-cyan-700 dark:text-white dark:group-hover:text-cyan-300">
                                            {featuredNews.title}
                                        </h3>
                                        <p className="mt-5 max-w-2xl text-sm leading-7 text-slate-600 dark:text-slate-300">
                                            {excerpt(featuredNews.excerpt, 'Berita utama akan membantu pembaca memahami perkembangan penting secara singkat dan terarah.')}
                                        </p>
                                        <div className="mt-8 inline-flex items-center text-sm font-semibold text-cyan-700 dark:text-cyan-300">
                                            Baca artikel
                                            <Icon name="arrow-right" className="ml-2 h-4 w-4 transition group-hover:translate-x-1" />
                                        </div>
                                    </Link>

                                    <div className="space-y-4">
                                        {secondaryNews.map((item) => (
                                            <Link
                                                key={item.id}
                                                to={`/news/${item.slug}`}
                                                className="group block rounded-[24px] border border-slate-200 bg-slate-50 p-5 transition hover:border-cyan-200 hover:bg-cyan-50/40 dark:border-slate-800 dark:bg-slate-950 dark:hover:border-cyan-500 dark:hover:bg-slate-900"
                                            >
                                                <div className="flex items-center justify-between gap-3">
                                                    <span className="rounded-full bg-white px-3 py-1 text-xs font-medium text-slate-600 shadow-sm dark:bg-slate-900 dark:text-slate-300">
                                                        {item.category?.name || 'Update'}
                                                    </span>
                                                    <span className="text-xs text-slate-500 dark:text-slate-400">
                                                        {formatDate(item.published_at || item.created_at)}
                                                    </span>
                                                </div>
                                                <h3 className="mt-4 text-lg font-semibold leading-7 text-slate-950 transition group-hover:text-cyan-700 dark:text-white dark:group-hover:text-cyan-300">
                                                    {item.title}
                                                </h3>
                                                <p className="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-300">
                                                    {excerpt(item.excerpt)}
                                                </p>
                                            </Link>
                                        ))}
                                        {secondaryNews.length === 0 && (
                                            <div className="rounded-[24px] border border-dashed border-slate-200 bg-slate-50 p-5 text-sm text-slate-500 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-400">
                                                Artikel lain akan muncul di sini.
                                            </div>
                                        )}
                                    </div>
                                </div>
                            ) : (
                                <div className="rounded-[24px] border border-dashed border-slate-200 bg-slate-50 p-6 text-sm text-slate-500 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-400">
                                    Belum ada berita untuk ditampilkan.
                                </div>
                            )}
                        </section>

                        <section className="rounded-[32px] border border-slate-200 bg-white p-6 shadow-[0_24px_80px_rgba(15,23,42,0.06)] sm:p-8">
                            <div className="mb-8 flex flex-col gap-4 border-b border-slate-200 pb-6 sm:flex-row sm:items-end sm:justify-between">
                                <div>
                                    <span className="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.22em] text-emerald-700">
                                        Lowongan terbaru
                                    </span>
                                    <h2 className="mt-4 text-3xl font-semibold tracking-tight text-slate-950">
                                        Lowongan terbaru.
                                    </h2>
                                    <p className="mt-3 max-w-2xl text-sm leading-7 text-slate-600">
                                        Pilih yang paling sesuai untuk Anda.
                                    </p>
                                </div>
                                <Link
                                    to="/lowongan"
                                    className="inline-flex items-center text-sm font-semibold text-slate-900 transition hover:text-emerald-700"
                                >
                                    Semua lowongan
                                    <Icon name="arrow-right" className="ml-2 h-4 w-4" />
                                </Link>
                            </div>

                            {loading ? (
                                <div className="grid gap-4 md:grid-cols-2">
                                    {[1, 2, 3, 4].map((item) => (
                                        <div key={item} className="animate-pulse rounded-[24px] border border-slate-200 bg-slate-50 p-6">
                                            <div className="h-4 w-24 rounded bg-slate-200" />
                                            <div className="mt-5 h-6 w-4/5 rounded bg-slate-200" />
                                            <div className="mt-3 h-4 w-2/3 rounded bg-slate-200" />
                                            <div className="mt-6 h-4 w-1/2 rounded bg-slate-200" />
                                        </div>
                                    ))}
                                </div>
                            ) : hasFeaturedJobs ? (
                                <div className="grid gap-4 md:grid-cols-2">
                                    {featuredJobs.map((job) => (
                                        <Link
                                            key={job.id}
                                            to={`/lowongan/${job.slug}`}
                                            className="group rounded-[24px] border border-slate-200 bg-slate-50 p-6 transition hover:-translate-y-1 hover:border-emerald-200 hover:bg-white hover:shadow-[0_22px_60px_rgba(15,23,42,0.08)]"
                                        >
                                            <div className="flex items-start justify-between gap-4">
                                                <div>
                                                    <span className="rounded-full bg-white px-3 py-1 text-xs font-medium text-slate-600 shadow-sm">
                                                        {job.company?.name || 'Perusahaan'}
                                                    </span>
                                                    <h3 className="mt-4 text-xl font-semibold leading-7 text-slate-950 transition group-hover:text-emerald-700">
                                                        {job.title}
                                                    </h3>
                                                </div>
                                                <div className="rounded-2xl bg-emerald-100 p-3 text-emerald-700">
                                                    <Icon name="briefcase" className="h-5 w-5" />
                                                </div>
                                            </div>

                                            <div className="mt-5 flex flex-wrap gap-3 text-sm text-slate-600">
                                                <div className="inline-flex items-center rounded-full bg-white px-3 py-1.5 shadow-sm">
                                                    <Icon name="map-pin" className="mr-2 h-4 w-4 text-slate-500" />
                                                    {job.location || 'Cirebon Raya'}
                                                </div>
                                                <div className="inline-flex items-center rounded-full bg-white px-3 py-1.5 shadow-sm">
                                                    <Icon name="clock" className="mr-2 h-4 w-4 text-slate-500" />
                                                    {job.type || 'Full-time'}
                                                </div>
                                            </div>

                                            <div className="mt-6 flex items-end justify-between gap-4 border-t border-slate-200 pt-5">
                                                <div>
                                                    <div className="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Kompensasi</div>
                                                    <div className="mt-1 text-lg font-semibold text-emerald-700">
                                                        {formatCurrency(job.salary_range)}
                                                    </div>
                                                </div>
                                                <div className="inline-flex items-center text-sm font-semibold text-slate-900">
                                                    Detail
                                                    <Icon name="arrow-right" className="ml-2 h-4 w-4 transition group-hover:translate-x-1" />
                                                </div>
                                            </div>
                                        </Link>
                                    ))}
                                </div>
                            ) : (
                                <div className="rounded-[24px] border border-dashed border-slate-200 bg-slate-50 p-6 text-sm text-slate-500 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-400">
                                    Belum ada lowongan.
                                </div>
                            )}
                        </section>
                    </div>

                    <aside className="space-y-6">
                        <section className="rounded-[28px] border border-slate-200 bg-white p-6 shadow-[0_20px_70px_rgba(15,23,42,0.06)]">
                            <div className="flex items-center justify-between">
                                <div>
                                    <span className="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.22em] text-slate-600">
                                        Topik populer
                                    </span>
                                    <h2 className="mt-4 text-xl font-semibold text-slate-950">Topik populer.</h2>
                                </div>
                                <div className="rounded-2xl bg-slate-100 p-3 text-slate-700">
                                    <Icon name="layers" className="h-5 w-5" />
                                </div>
                            </div>

                            <div className="mt-6 flex flex-wrap gap-3">
                                {hasCategories ? topCategories.map((category) => (
                                    <Link
                                        key={category.id}
                                        to={`/berita?category=${category.slug}`}
                                        className="group inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-sm text-slate-700 transition hover:border-cyan-200 hover:bg-cyan-50 hover:text-cyan-800"
                                    >
                                        <span>{category.name}</span>
                                        <span className="rounded-full bg-white px-2 py-0.5 text-xs text-slate-500 shadow-sm">
                                            {(category.posts_count || 0) + (category.jobs_count || 0)}
                                        </span>
                                    </Link>
                                )) : (
                                    <div className="w-full rounded-2xl border border-dashed border-slate-200 bg-slate-50 px-4 py-4 text-sm text-slate-500 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-400">
                                        Topik akan muncul di sini.
                                    </div>
                                )}
                            </div>
                        </section>

                        <section className="rounded-[28px] border border-slate-200 bg-white p-6 shadow-[0_20px_70px_rgba(15,23,42,0.06)]">
                            <div>
                                <span className="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.22em] text-blue-700">
                                    Perusahaan pilihan
                                </span>
                                <h2 className="mt-4 text-xl font-semibold text-slate-950">Perusahaan pilihan.</h2>
                            </div>

                            <div className="mt-6 space-y-3">
                                {hasCompanies ? featuredCompanies.map((company) => (
                                    <Link
                                        key={company.id}
                                        to={`/perusahaan/${company.slug}`}
                                        className="group flex items-center gap-4 rounded-[22px] border border-slate-200 bg-slate-50 px-4 py-4 transition hover:border-blue-200 hover:bg-blue-50/50"
                                    >
                                        <div className="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-600 to-cyan-500 text-sm font-semibold text-white shadow-sm">
                                            {companyInitials(company.name)}
                                        </div>
                                        <div className="min-w-0 flex-1">
                                            <div className="truncate text-sm font-semibold text-slate-950">{company.name}</div>
                                            <div className="mt-1 truncate text-sm text-slate-600">
                                                {company.industry || 'Partner CirebonKita'}
                                            </div>
                                        </div>
                                        <Icon name="arrow-right" className="h-4 w-4 text-slate-400 transition group-hover:translate-x-1 group-hover:text-blue-700" />
                                    </Link>
                                )) : (
                                    <div className="rounded-[22px] border border-dashed border-slate-200 bg-slate-50 px-4 py-5 text-sm text-slate-500 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-400">
                                        Perusahaan akan muncul di sini.
                                    </div>
                                )}
                            </div>

                            <Link
                                to="/perusahaan"
                                className="mt-6 inline-flex items-center text-sm font-semibold text-slate-900 transition hover:text-blue-700"
                            >
                                Jelajahi direktori
                                <Icon name="arrow-right" className="ml-2 h-4 w-4" />
                            </Link>
                        </section>

                        <section className="overflow-hidden rounded-[28px] border border-slate-200 bg-gradient-to-br from-blue-50 via-white to-cyan-50 p-6 text-slate-900 shadow-[0_24px_80px_rgba(15,23,42,0.08)] dark:border-slate-800 dark:bg-gradient-to-br dark:from-slate-900 dark:via-slate-900 dark:to-cyan-950/30">
                            <span className="inline-flex rounded-full border border-cyan-200 bg-white px-3 py-1 text-xs font-semibold uppercase tracking-[0.22em] text-cyan-700 dark:border-slate-700 dark:bg-slate-900 dark:text-cyan-300">
                                Langkah berikutnya
                            </span>
                            <h2 className="mt-4 text-2xl font-semibold text-slate-950 dark:text-white">Lanjutkan lebih cepat.</h2>
                            <p className="mt-3 text-sm leading-7 text-slate-600 dark:text-slate-300">
                                Masuk atau daftar untuk mulai.
                            </p>
                            <div className="mt-6 space-y-3 text-sm text-slate-700 dark:text-slate-300">
                                <div className="flex items-center gap-3">
                                    <div className="rounded-full bg-white p-2 text-blue-700 shadow-sm ring-1 ring-slate-200 dark:bg-slate-900 dark:text-cyan-300 dark:ring-slate-700">
                                        <Icon name="user" className="h-4 w-4" />
                                    </div>
                                    Simpan pencarian Anda
                                </div>
                                <div className="flex items-center gap-3">
                                    <div className="rounded-full bg-white p-2 text-blue-700 shadow-sm ring-1 ring-slate-200 dark:bg-slate-900 dark:text-cyan-300 dark:ring-slate-700">
                                        <Icon name="shield" className="h-4 w-4" />
                                    </div>
                                    Akses sesuai kebutuhan
                                </div>
                            </div>
                            <div className="mt-6 flex flex-col gap-3">
                                <Link
                                    to="/register"
                                    className="inline-flex items-center justify-center rounded-full bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 dark:bg-white dark:text-slate-950 dark:hover:bg-slate-100"
                                >
                                    Daftar sekarang
                                </Link>
                                <Link
                                    to="/login"
                                    className="inline-flex items-center justify-center rounded-full border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-800"
                                >
                                    Masuk ke akun
                                </Link>
                            </div>
                        </section>
                    </aside>
                </div>
            </section>
        </div>
    );
}
