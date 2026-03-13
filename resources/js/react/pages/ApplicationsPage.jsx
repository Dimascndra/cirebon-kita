import React, { useEffect, useState } from 'react';
import { Link, useSearchParams } from 'react-router-dom';
import { api } from '../lib/api';
import { formatDate, unwrapList } from '../lib/utils';

export default function ApplicationsPage() {
    const [searchParams, setSearchParams] = useSearchParams();
    const [items, setItems] = useState([]);
    const [meta, setMeta] = useState(null);

    const page = searchParams.get('page') ?? '1';

    useEffect(() => {
        let active = true;

        api.get('/applications', { params: { page } }).then((response) => {
            if (!active) {
                return;
            }

            const result = unwrapList(response.data);
            setItems(result.items);
            setMeta(result.meta);
        });

        return () => {
            active = false;
        };
    }, [page]);

    return (
        <div className="page">
            <section className="page-header">
                <div className="container">
                    <p className="eyebrow">Lamaran</p>
                    <h1>Riwayat lamaran kerja</h1>
                    <p>Semua lamaran Anda sekarang tersedia dalam tampilan React.</p>
                </div>
            </section>
            <section className="page-section page-section--tight">
                <div className="container stack-list">
                    {items.map((item) => (
                        <Link key={item.id} to={`/my-applications/${item.id}`} className="surface-card surface-card--row">
                            <div>
                                <p className="meta-text">{item.job?.company?.name || 'Perusahaan'} · {formatDate(item.applied_at || item.created_at)}</p>
                                <h3>{item.job?.title || 'Lowongan'}</h3>
                                <p>Status aplikasi: {item.status}</p>
                            </div>
                            <strong className={`status-pill status-pill--${item.status}`}>{item.status}</strong>
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
                                onClick={() => setSearchParams({ page: String(pageNumber) })}
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
