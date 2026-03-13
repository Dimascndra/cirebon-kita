import React, { useEffect, useState } from 'react';
import { Link, useSearchParams } from 'react-router-dom';
import Icon from '../components/Icon';
import { api } from '../lib/api';
import { excerpt, unwrapList } from '../lib/utils';

function companyInitial(name) {
    return name?.charAt(0)?.toUpperCase() || 'C';
}

export default function CompaniesPage() {
    const [searchParams, setSearchParams] = useSearchParams();
    const [items, setItems] = useState([]);
    const [meta, setMeta] = useState(null);
    const [loading, setLoading] = useState(true);

    const search = searchParams.get('search') ?? '';
    const sort = searchParams.get('sort') ?? 'newest';
    const page = searchParams.get('page') ?? '1';

    useEffect(() => {
        let active = true;
        setLoading(true);

        api.get('/companies', { params: { search, sort, page } }).then((response) => {
            if (!active) {
                return;
            }

            const result = unwrapList(response.data);
            setItems(result.items);
            setMeta(result.meta);
            setLoading(false);
        });

        return () => {
            active = false;
        };
    }, [search, sort, page]);

    function updateParams(next) {
        const params = new URLSearchParams(searchParams);
        Object.entries(next).forEach(([key, value]) => {
            if (!value) {
                params.delete(key);
            } else {
                params.set(key, value);
            }
        });
        params.delete('page');
        setSearchParams(params);
    }

    return (
        <div className="public-page-shell min-h-screen bg-[#f5f8fc] text-slate-900">
            <section className="public-page-hero border-b border-slate-200 bg-[linear-gradient(180deg,#eef5fb_0%,#f8fbff_100%)]">
                <div className="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
                    <div className="max-w-3xl">
                        <span className="inline-flex rounded-full border border-blue-200 bg-white px-4 py-2 text-sm font-medium text-blue-700 shadow-sm">
                            Perusahaan
                        </span>
                        <h1 className="mt-6 text-4xl font-semibold tracking-tight text-slate-950 sm:text-5xl">
                            Jelajahi perusahaan aktif dengan visual yang lebih konsisten.
                        </h1>
                        <p className="mt-4 text-lg leading-8 text-slate-600">
                            Telusuri profil perusahaan, urutkan sesuai kebutuhan, dan buka detail tanpa perpindahan gaya yang terasa acak.
                        </p>
                    </div>
                </div>
            </section>

            <section className="py-8">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div className="rounded-[28px] border border-slate-200 bg-white p-5 shadow-[0_18px_60px_rgba(15,23,42,0.06)] sm:p-6">
                        <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div className="relative">
                                <input
                                    type="text"
                                    value={search}
                                    onChange={(e) => updateParams({ search: e.target.value })}
                                    placeholder="Cari perusahaan"
                                    className="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 pl-11 text-slate-900 outline-none transition focus:border-blue-300 focus:bg-white focus:ring-4 focus:ring-blue-100"
                                />
                                <svg className="absolute left-4 top-3.5 h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <select
                                value={sort}
                                onChange={(e) => updateParams({ sort: e.target.value })}
                                className="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-blue-300 focus:bg-white focus:ring-4 focus:ring-blue-100"
                            >
                                <option value="newest">Terbaru</option>
                                <option value="most_jobs">Paling banyak lowongan</option>
                                <option value="name_asc">Nama A-Z</option>
                                <option value="name_desc">Nama Z-A</option>
                            </select>
                        </div>
                    </div>
                </div>
            </section>

            <section className="pb-20 pt-2">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div className="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                        {loading && (
                            <div className="md:col-span-2 xl:col-span-3 rounded-[28px] border border-slate-200 bg-white p-8 text-center shadow-[0_18px_60px_rgba(15,23,42,0.05)]">
                                <div className="inline-flex items-center text-slate-600">
                                    <svg className="mr-3 h-5 w-5 animate-spin text-blue-600" viewBox="0 0 24 24">
                                        <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" fill="none" />
                                        <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                                    </svg>
                                    Memuat perusahaan...
                                </div>
                            </div>
                        )}

                        {!loading && items.map((company) => (
                            <Link
                                key={company.id}
                                to={`/perusahaan/${company.slug}`}
                                className="group flex h-full flex-col rounded-[28px] border border-slate-200 bg-white p-6 shadow-[0_18px_60px_rgba(15,23,42,0.05)] transition hover:-translate-y-1 hover:border-blue-300 hover:shadow-[0_24px_70px_rgba(15,23,42,0.08)]"
                            >
                                <div className="flex items-center justify-between gap-4">
                                    <div className="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-600 to-cyan-500 text-lg font-semibold text-white shadow-sm">
                                        {companyInitial(company.name)}
                                    </div>
                                    <span className="rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-700">
                                        {company.industry || 'Perusahaan'}
                                    </span>
                                </div>
                                <h3 className="mt-5 text-xl font-semibold text-slate-950 transition group-hover:text-blue-700">
                                    {company.name}
                                </h3>
                                <p className="mt-3 text-sm leading-7 text-slate-600">
                                    {excerpt(company.description, 'Profil perusahaan tersedia melalui backend Laravel.')}
                                </p>
                                <div className="mt-auto flex items-center justify-between border-t border-slate-100 pt-6">
                                    <div className="inline-flex items-center text-sm text-slate-500">
                                        <Icon name="briefcase" className="mr-2 h-4 w-4" />
                                        {company.jobs_count || 0} lowongan
                                    </div>
                                    <div className="inline-flex items-center text-sm font-semibold text-slate-700 group-hover:text-blue-700">
                                        Detail
                                        <Icon name="arrow-right" className="ml-2 h-4 w-4 transition group-hover:translate-x-1" />
                                    </div>
                                </div>
                            </Link>
                        ))}

                        {!loading && items.length === 0 && (
                            <div className="md:col-span-2 xl:col-span-3 rounded-[28px] border border-dashed border-slate-200 bg-white p-8 text-sm text-slate-500">
                                Belum ada perusahaan yang cocok dengan pencarian ini.
                            </div>
                        )}
                    </div>

                    {meta && meta.last_page > 1 && (
                        <div className="mt-12 flex justify-center">
                            <div className="flex flex-wrap items-center gap-2">
                                {Array.from({ length: meta.last_page }, (_, index) => index + 1).map((pageNumber) => (
                                    <button
                                        key={pageNumber}
                                        type="button"
                                        onClick={() => setSearchParams((current) => {
                                            const params = new URLSearchParams(current);
                                            params.set('page', String(pageNumber));
                                            return params;
                                        })}
                                        className={`min-w-[44px] rounded-xl px-4 py-2 text-sm font-medium transition ${
                                            pageNumber === Number(page)
                                                ? 'bg-blue-600 text-white'
                                                : 'border border-slate-200 bg-white text-slate-700 hover:bg-slate-50'
                                        }`}
                                    >
                                        {pageNumber}
                                    </button>
                                ))}
                            </div>
                        </div>
                    )}
                </div>
            </section>
        </div>
    );
}
