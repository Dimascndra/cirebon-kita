import React, { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import { api } from '../lib/api';
import { formatDate } from '../lib/utils';

export default function ApplicationDetailPage() {
    const { id } = useParams();
    const [item, setItem] = useState(null);

    useEffect(() => {
        let active = true;

        api.get(`/applications/${id}`).then((response) => {
            if (active) {
                setItem(response.data?.data ?? null);
            }
        });

        return () => {
            active = false;
        };
    }, [id]);

    if (!item) {
        return <div className="container page-section">Memuat detail lamaran...</div>;
    }

    return (
        <div className="page">
            <section className="page-header page-header--article">
                <div className="container article-hero">
                    <div>
                        <p className="eyebrow">Detail lamaran</p>
                        <h1>{item.job?.title || 'Lowongan'}</h1>
                        <p>{item.job?.company?.name || 'Perusahaan'} · {formatDate(item.applied_at || item.created_at)}</p>
                    </div>
                    <div className="detail-stats">
                        <strong className={`status-pill status-pill--${item.status}`}>{item.status}</strong>
                        <span>{item.job?.location || 'Cirebon Raya'}</span>
                    </div>
                </div>
            </section>
            <section className="page-section page-section--tight">
                <div className="container page-grid">
                    <article className="surface-card article-body">
                        <h2>Cover letter</h2>
                        <p>{item.cover_letter || 'Tidak ada cover letter yang dikirim.'}</p>
                        <h2>Status</h2>
                        <p>Status aplikasi saat ini: {item.status}</p>
                        {item.notes && (
                            <>
                                <h2>Catatan</h2>
                                <p>{item.notes}</p>
                            </>
                        )}
                    </article>
                    <aside className="sidebar-stack">
                        <div className="surface-card">
                            <p className="eyebrow">Dokumen</p>
                            {item.cv_url ? (
                                <a href={item.cv_url} className="inline-link" target="_blank" rel="noreferrer">
                                    Lihat CV
                                </a>
                            ) : (
                                <p>CV tidak tersedia.</p>
                            )}
                        </div>
                    </aside>
                </div>
            </section>
        </div>
    );
}
