import React, { useEffect, useState } from 'react';
import { Link, useParams } from 'react-router-dom';
import { api } from '../lib/api';
import { excerpt } from '../lib/utils';

export default function CompanyDetailPage() {
    const { slug } = useParams();
    const [company, setCompany] = useState(null);

    useEffect(() => {
        let active = true;

        api.get(`/companies/${slug}`).then((response) => {
            if (!active) {
                return;
            }

            setCompany(response.data?.data ?? null);
        });

        return () => {
            active = false;
        };
    }, [slug]);

    if (!company) {
        return <div className="public-page-shell container page-section">Memuat detail perusahaan...</div>;
    }

    return (
        <div className="page public-page-shell">
            <section className="page-header page-header--article public-page-hero">
                <div className="container article-hero">
                    <div>
                        <p className="eyebrow">Perusahaan</p>
                        <h1>{company.name}</h1>
                        <p>{company.industry || 'Industri belum diisi'} · {company.jobs_count || 0} lowongan aktif</p>
                    </div>
                    <div className="detail-stats">
                        <strong>{company.verified ? 'Terverifikasi' : 'Belum terverifikasi'}</strong>
                        <span>{company.email || company.phone || 'Kontak belum tersedia'}</span>
                    </div>
                </div>
            </section>
            <section className="page-section page-section--tight">
                <div className="container page-grid">
                    <article className="surface-card article-body">
                        <h2>Tentang perusahaan</h2>
                        <p>{excerpt(company.description, 'Deskripsi perusahaan belum tersedia.')}</p>
                        {company.address && <p><strong>Alamat:</strong> {company.address}</p>}
                        {company.website && (
                            <p>
                                <strong>Website:</strong>{' '}
                                <a href={company.website} target="_blank" rel="noreferrer" className="inline-link">
                                    {company.website}
                                </a>
                            </p>
                        )}
                    </article>
                    <aside className="sidebar-stack">
                        <div className="surface-card">
                            <p className="eyebrow">Lowongan aktif</p>
                            <div className="stack-list stack-list--tight">
                                {(company.jobs || []).map((job) => (
                                    <Link key={job.id} to={`/lowongan/${job.slug}`} className="list-link">
                                        {job.title}
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
