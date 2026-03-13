import React from 'react';
import { Link } from 'react-router-dom';
import Icon from '../components/Icon';

const pillars = [
    {
        label: 'Informasi',
        title: 'Kabar daerah disusun agar lebih mudah dipahami.',
        description: 'Kami ingin pengunjung bisa menemukan informasi penting dengan cepat, tanpa harus membuka terlalu banyak halaman.',
        icon: 'shield',
        accent: 'bg-emerald-50 text-emerald-700',
    },
    {
        label: 'Peluang',
        title: 'Lowongan dan perusahaan ditampilkan lebih terarah.',
        description: 'Pencari kerja bisa lebih mudah membandingkan peluang, memahami profil perusahaan, lalu melangkah ke pilihan yang paling sesuai.',
        icon: 'compass',
        accent: 'bg-cyan-50 text-cyan-700',
    },
    {
        label: 'Kepercayaan',
        title: 'Semua bagian dirancang agar terasa lebih rapi dan konsisten.',
        description: 'Kami menjaga pengalaman membaca, mencari, dan menjelajah tetap nyaman supaya pengunjung tidak cepat lelah saat menggunakan platform.',
        icon: 'layers',
        accent: 'bg-blue-50 text-blue-700',
    },
];

export default function AboutPage() {
    return (
        <div className="public-page-shell min-h-screen bg-[#f5f8fc] text-slate-900">
            <section className="public-page-hero border-b border-slate-200 bg-[linear-gradient(180deg,#eef5fb_0%,#f8fbff_100%)]">
                <div className="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
                    <div className="grid gap-10 lg:grid-cols-[minmax(0,1.3fr)_360px] lg:items-start">
                        <div className="max-w-3xl">
                            <span className="inline-flex rounded-full border border-blue-200 bg-white px-4 py-2 text-sm font-medium text-blue-700 shadow-sm">
                                Tentang kami
                            </span>
                            <h1 className="mt-6 text-4xl font-semibold tracking-tight text-slate-950 sm:text-5xl">
                                CirebonKita hadir untuk membantu orang mengikuti kabar daerah, menemukan peluang, dan mengenal perusahaan dengan lebih mudah.
                            </h1>
                            <p className="mt-4 text-lg leading-8 text-slate-600">
                                Kami merancang CirebonKita sebagai tempat yang ringkas, jelas, dan nyaman dipakai setiap hari, baik untuk membaca berita maupun mencari arah berikutnya.
                            </p>

                            <div className="mt-8 flex flex-col gap-3 sm:flex-row">
                                <Link
                                    to="/berita"
                                    className="inline-flex items-center justify-center rounded-full bg-blue-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-blue-700"
                                >
                                    Jelajahi berita
                                    <Icon name="arrow-right" className="ml-2 h-4 w-4" />
                                </Link>
                                <Link
                                    to="/lowongan"
                                    className="inline-flex items-center justify-center rounded-full border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50"
                                >
                                    Lihat lowongan
                                </Link>
                            </div>
                        </div>

                        <aside className="rounded-[30px] border border-slate-200 bg-white p-6 shadow-[0_18px_60px_rgba(15,23,42,0.06)]">
                            <span className="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.22em] text-slate-600">
                                Ringkasan
                            </span>
                            <div className="mt-6 space-y-5">
                                <div className="flex items-start gap-4">
                                    <div className="rounded-2xl bg-cyan-50 p-3 text-cyan-700">
                                        <Icon name="chart" className="h-5 w-5" />
                                    </div>
                                    <div>
                                        <h2 className="text-base font-semibold text-slate-950">Lebih mudah dibaca</h2>
                                        <p className="mt-1 text-sm leading-6 text-slate-600">Informasi penting ditempatkan lebih jelas supaya pengunjung cepat menangkap inti halaman.</p>
                                    </div>
                                </div>
                                <div className="flex items-start gap-4">
                                    <div className="rounded-2xl bg-emerald-50 p-3 text-emerald-700">
                                        <Icon name="briefcase" className="h-5 w-5" />
                                    </div>
                                    <div>
                                        <h2 className="text-base font-semibold text-slate-950">Lebih mudah ditindaklanjuti</h2>
                                        <p className="mt-1 text-sm leading-6 text-slate-600">Berita, lowongan, dan profil perusahaan disusun agar pengunjung bisa langsung melangkah ke kebutuhan berikutnya.</p>
                                    </div>
                                </div>
                            </div>
                        </aside>
                    </div>
                </div>
            </section>

            <section className="pb-20 pt-8">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div className="grid gap-6 md:grid-cols-3">
                        {pillars.map((item) => (
                            <article
                                key={item.label}
                                className="rounded-[28px] border border-slate-200 bg-white p-6 shadow-[0_18px_60px_rgba(15,23,42,0.05)]"
                            >
                                <div className={`inline-flex rounded-2xl p-3 ${item.accent}`}>
                                    <Icon name={item.icon} className="h-5 w-5" />
                                </div>
                                <span className="mt-5 block text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">
                                    {item.label}
                                </span>
                                <h2 className="mt-3 text-2xl font-semibold leading-8 text-slate-950">
                                    {item.title}
                                </h2>
                                <p className="mt-3 text-sm leading-7 text-slate-600">
                                    {item.description}
                                </p>
                            </article>
                        ))}
                    </div>
                </div>
            </section>
        </div>
    );
}
