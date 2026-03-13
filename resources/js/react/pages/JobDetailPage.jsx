import React, { useEffect, useState } from 'react';
import { Link, useParams } from 'react-router-dom';
import { api } from '../lib/api';
import { formatCurrency } from '../lib/utils';

export default function JobDetailPage() {
    const { slug } = useParams();
    const [job, setJob] = useState(null);
    const [related, setRelated] = useState([]);

    useEffect(() => {
        let active = true;

        api.get(`/jobs/${slug}`).then((response) => {
            if (!active) {
                return;
            }

            const data = response.data?.data ?? {};
            setJob(data.job ?? null);
            setRelated(data.related ?? []);
        });

        return () => {
            active = false;
        };
    }, [slug]);

    if (!job) {
        return <div className="container page-section">Memuat detail lowongan...</div>;
    }

    return (
        <div className="page">
            <section className="page-header page-header--article">
                <div className="container article-hero">
                    <div>
                        <p className="eyebrow">{job.company?.name || 'Perusahaan'}</p>
                        <h1>{job.title}</h1>
                        <p>{job.location || 'Cirebon Raya'} · {job.type || 'Full-time'}</p>
                    </div>
                    <div className="detail-stats">
                        <strong>{formatCurrency(job.salary_range)}</strong>
                        <span>Status: {job.status}</span>
                    </div>
                </div>
            </section>
            <section className="page-section page-section--tight">
                <div className="container page-grid">
                    <article className="surface-card article-body" dangerouslySetInnerHTML={{ __html: job.description }} />
                    <aside className="sidebar-stack">
                        <div className="surface-card">
                            <p className="eyebrow">Perusahaan</p>
                            <h2>{job.company?.name || 'Perusahaan'}</h2>
                            <p>{job.company?.industry || 'Informasi industri belum tersedia.'}</p>
                            {job.company?.website && (
                                <a href={job.company.website} target="_blank" rel="noreferrer" className="inline-link">
                                    Kunjungi website
                                </a>
                            )}
                        </div>
                        <div className="surface-card">
                            <p className="eyebrow">Lowongan terkait</p>
                            <div className="stack-list stack-list--tight">
                                {related.map((item) => (
                                    <Link key={item.id} to={`/lowongan/${item.slug}`} className="list-link">
                                        {item.title}
                                    </Link>
                                ))}
                            </div>
                        </div>
                    </aside>
                </div>
            </section>
        </div>
    );
}
