import React, { useEffect, useState } from 'react';
import { Link, useSearchParams } from 'react-router-dom';
import { api } from '../lib/api';
import { formatCurrency, unwrapList } from '../lib/utils';

export default function JobsPage() {
    const [searchParams, setSearchParams] = useSearchParams();
    const [filters, setFilters] = useState({ locations: [], types: [] });
    const [items, setItems] = useState([]);
    const [meta, setMeta] = useState(null);
    const [loading, setLoading] = useState(true);

    const search = searchParams.get('search') ?? '';
    const location = searchParams.get('location') ?? '';
    const type = searchParams.get('type') ?? '';
    const page = searchParams.get('page') ?? '1';

    useEffect(() => {
        api.get('/jobs/filters').then((response) => {
            setFilters(response.data?.data ?? { locations: [], types: [] });
        });
    }, []);

    useEffect(() => {
        let active = true;
        setLoading(true);

        api.get('/jobs', { params: { search, location, type, page } }).then((response) => {
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
    }, [search, location, type, page]);

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
                    <p className="eyebrow">Lowongan</p>
                    <h1>Daftar lowongan kerja</h1>
                    <p>Pencarian lowongan sekarang sepenuhnya dikendalikan React.</p>
                </div>
            </section>
            <section className="page-section page-section--tight">
                <div className="container filter-bar">
                    <input value={search} onChange={(e) => updateParams({ search: e.target.value })} placeholder="Cari posisi atau perusahaan" />
                    <select value={location} onChange={(e) => updateParams({ location: e.target.value })}>
                        <option value="">Semua lokasi</option>
                        {filters.locations.map((item) => (
                            <option key={item} value={item}>
                                {item}
                            </option>
                        ))}
                    </select>
                    <select value={type} onChange={(e) => updateParams({ type: e.target.value })}>
                        <option value="">Semua tipe</option>
                        {filters.types.map((item) => (
                            <option key={item} value={item}>
                                {item}
                            </option>
                        ))}
                    </select>
                </div>
            </section>
            <section className="page-section page-section--tight">
                <div className="container stack-list">
                    {loading && <div className="surface-card">Memuat lowongan...</div>}
                    {!loading &&
                        items.map((job) => (
                            <Link key={job.id} to={`/lowongan/${job.slug}`} className="surface-card surface-card--row">
                                <div>
                                    <p className="meta-text">{job.company?.name || 'Perusahaan'} · {job.type || 'Full-time'}</p>
                                    <h3>{job.title}</h3>
                                    <p>{job.location || 'Cirebon Raya'}</p>
                                </div>
                                <strong>{formatCurrency(job.salary_range)}</strong>
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
