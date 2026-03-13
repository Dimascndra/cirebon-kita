import React, { useEffect, useState } from 'react';
import { Link, useSearchParams } from 'react-router-dom';
import { api } from '../lib/api';
import { excerpt, unwrapList } from '../lib/utils';

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
        <div className="page">
            <section className="page-header">
                <div className="container">
                    <p className="eyebrow">Perusahaan</p>
                    <h1>Perusahaan terverifikasi</h1>
                    <p>Listing perusahaan sekarang dirender React menggunakan data paginated dari Laravel.</p>
                </div>
            </section>
            <section className="page-section page-section--tight">
                <div className="container filter-bar">
                    <input value={search} onChange={(e) => updateParams({ search: e.target.value })} placeholder="Cari perusahaan" />
                    <select value={sort} onChange={(e) => updateParams({ sort: e.target.value })}>
                        <option value="newest">Terbaru</option>
                        <option value="most_jobs">Paling banyak lowongan</option>
                        <option value="name_asc">Nama A-Z</option>
                        <option value="name_desc">Nama Z-A</option>
                    </select>
                </div>
            </section>
            <section className="page-section page-section--tight">
                <div className="container card-grid card-grid--three">
                    {loading && <div className="surface-card">Memuat perusahaan...</div>}
                    {!loading &&
                        items.map((company) => (
                            <Link key={company.id} to={`/perusahaan/${company.slug}`} className="surface-card surface-card--link">
                                <p className="meta-text">{company.industry || 'Perusahaan'} · {company.jobs_count || 0} lowongan</p>
                                <h3>{company.name}</h3>
                                <p>{excerpt(company.description, 'Profil perusahaan tersedia melalui backend Laravel.')}</p>
                            </Link>
                        ))}
                </div>
                {meta && meta.last_page > 1 && (
                    <div className="container pager">
                        {Array.from({ length: meta.last_page }, (_, index) => index + 1).map((pageNumber) => (
                            <button
                                key={pageNumber}
                                type="button"
                                className={pageNumber === Number(page) ? 'pager__button pager__button--active' : 'pager__button'}
                                onClick={() => setSearchParams((current) => {
                                    const params = new URLSearchParams(current);
                                    params.set('page', String(pageNumber));
                                    return params;
                                })}
                            >
                                {pageNumber}
                            </button>
                        ))}
                    </div>
                )}
            </section>
        </div>
    );
}
