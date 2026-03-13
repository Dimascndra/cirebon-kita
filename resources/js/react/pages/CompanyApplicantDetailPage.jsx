import React, { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import { api } from '../lib/api';
import { formatDate } from '../lib/utils';

const statuses = ['pending', 'reviewing', 'shortlisted', 'rejected', 'accepted'];

export default function CompanyApplicantDetailPage() {
    const { id } = useParams();
    const [application, setApplication] = useState(null);
    const [status, setStatus] = useState('pending');
    const [notes, setNotes] = useState('');
    const [message, setMessage] = useState('');

    useEffect(() => {
        let active = true;

        api.get(`/company/applicants/${id}`).then((response) => {
            if (!active) {
                return;
            }

            const data = response.data?.data ?? null;
            setApplication(data);
            setStatus(data?.status || 'pending');
            setNotes(data?.notes || '');
        });

        return () => {
            active = false;
        };
    }, [id]);

    async function submit(event) {
        event.preventDefault();
        const response = await api.patch(`/company/applicants/${id}/status`, { status, notes });
        const data = response.data?.data ?? null;
        setApplication(data);
        setStatus(data?.status || status);
        setNotes(data?.notes || notes);
        setMessage(response.data?.message || 'Status updated.');
    }

    if (!application) {
        return <div className="container page-section">Memuat detail pelamar...</div>;
    }

    return (
        <div className="page">
            <section className="page-header page-header--article">
                <div className="container article-hero">
                    <div>
                        <p className="eyebrow">Applicant detail</p>
                        <h1>{application.user?.name || 'Applicant'}</h1>
                        <p>{application.job?.title || 'Job'} · {formatDate(application.applied_at || application.created_at)}</p>
                    </div>
                    <div className="detail-stats">
                        <strong className={`status-pill status-pill--${application.status}`}>{application.status}</strong>
                        <span>{application.user?.email || '-'}</span>
                    </div>
                </div>
            </section>
            <section className="page-section page-section--tight">
                <div className="container page-grid">
                    <article className="surface-card article-body">
                        <h2>Cover letter</h2>
                        <p>{application.cover_letter || 'No cover letter provided.'}</p>
                        <h2>Applied for</h2>
                        <p>{application.job?.title || '-'} · {application.job?.location || '-'} · {application.job?.type || '-'}</p>
                        <h2>CV</h2>
                        {application.cv_url ? (
                            <a href={`/company/applicants/${application.id}/cv`} target="_blank" rel="noreferrer" className="inline-link">
                                Download CV
                            </a>
                        ) : (
                            <p>CV tidak tersedia.</p>
                        )}
                    </article>
                    <aside className="sidebar-stack">
                        <form className="surface-card" onSubmit={submit}>
                            <p className="eyebrow">Update status</p>
                            <select value={status} onChange={(e) => setStatus(e.target.value)}>
                                {statuses.map((item) => (
                                    <option key={item} value={item}>{item}</option>
                                ))}
                            </select>
                            <textarea value={notes} onChange={(e) => setNotes(e.target.value)} rows="6" className="textarea-control" placeholder="Internal notes" />
                            {message && <div className="notice">{message}</div>}
                            <button type="submit" className="button button--primary button--block">Simpan status</button>
                        </form>
                    </aside>
                </div>
            </section>
        </div>
    );
}
