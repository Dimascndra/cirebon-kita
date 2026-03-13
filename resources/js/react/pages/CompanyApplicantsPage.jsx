import React, { useEffect, useState } from 'react';
import { Link, useSearchParams } from 'react-router-dom';
import { api } from '../lib/api';
import { formatDate, unwrapList } from '../lib/utils';

const statuses = ['', 'pending', 'reviewing', 'shortlisted', 'rejected', 'accepted'];

export default function CompanyApplicantsPage() {
    const [searchParams, setSearchParams] = useSearchParams();
    const [items, setItems] = useState([]);
    const [meta, setMeta] = useState(null);

    const page = searchParams.get('page') ?? '1';
    const status = searchParams.get('status') ?? '';

    useEffect(() => {
        let active = true;

        api.get('/company/applicants', { params: { page, status } }).then((response) => {
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
    }, [page, status]);

    return (
        <div className="page">
            <section className="page-header">
                <div className="container">
                    <p className="eyebrow">Applicants</p>
                    <h1>Manajemen pelamar</h1>
                    <p>Filter dan detail pelamar sekarang dikelola oleh React.</p>
                </div>
            </section>
            <section className="page-section page-section--tight">
                <div className="container filter-bar">
                    <select value={status} onChange={(e) => setSearchParams(e.target.value ? { status: e.target.value } : {})}>
                        {statuses.map((item) => (
                            <option key={item || 'all'} value={item}>
                                {item ? item.charAt(0).toUpperCase() + item.slice(1) : 'All status'}
                            </option>
                        ))}
                    </select>
                </div>
            </section>
            <section className="page-section page-section--tight">
                <div className="container stack-list">
                    {items.map((app) => (
                        <Link key={app.id} to={`/company/applicants/${app.id}`} className="surface-card surface-card--row">
                            <div>
                                <p className="meta-text">{app.user?.email || 'Applicant'} · {formatDate(app.applied_at || app.created_at)}</p>
                                <h3>{app.user?.name || 'Applicant'}</h3>
                                <p>{app.job?.title || 'Job'} · {app.job?.location || '-'}</p>
                            </div>
                            <strong className={`status-pill status-pill--${app.status}`}>{app.status}</strong>
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
                                onClick={() => {
                                    const next = {};
                                    if (status) next.status = status;
                                    next.page = String(pageNumber);
                                    setSearchParams(next);
                                }}
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
