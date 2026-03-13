import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { api } from '../lib/api';

export default function CompanyDashboardPage() {
    const [payload, setPayload] = useState(null);

    useEffect(() => {
        let active = true;

        api.get('/company/dashboard').then((response) => {
            if (active) {
                setPayload(response.data?.data ?? null);
            }
        });

        return () => {
            active = false;
        };
    }, []);

    if (!payload) {
        return <div className="container page-section">Memuat company dashboard...</div>;
    }

    return (
        <div className="page">
            <section className="page-header">
                <div className="container">
                    <p className="eyebrow">Company Dashboard</p>
                    <h1>Ringkasan rekrutmen perusahaan</h1>
                    <p>Panel perusahaan sekarang dirender React di atas endpoint Laravel yang sama.</p>
                </div>
            </section>
            <section className="page-section page-section--tight">
                <div className="container card-grid card-grid--four">
                    <article className="surface-card"><p className="meta-text">Total jobs</p><h2>{payload.totalJobs}</h2></article>
                    <article className="surface-card"><p className="meta-text">Active jobs</p><h2>{payload.activeJobs}</h2></article>
                    <article className="surface-card"><p className="meta-text">Applications</p><h2>{payload.totalApplications}</h2></article>
                    <article className="surface-card"><p className="meta-text">Pending review</p><h2>{payload.pendingApplications}</h2></article>
                </div>
            </section>
            <section className="page-section page-section--tight">
                <div className="container page-grid">
                    <div className="surface-card">
                        <div className="section-heading">
                            <div>
                                <p className="eyebrow">Recent applications</p>
                                <h2>Pelamar terbaru</h2>
                            </div>
                            <Link to="/company/applicants">Lihat semua</Link>
                        </div>
                        <div className="stack-list stack-list--tight">
                            {(payload.recentApplications || []).map((app) => (
                                <Link key={app.id} to={`/company/applicants/${app.id}`} className="surface-card surface-card--row surface-card--nested">
                                    <div>
                                        <p className="meta-text">{app.user?.email || 'Applicant'}</p>
                                        <h3>{app.user?.name || 'Applicant'}</h3>
                                        <p>Applied for {app.job?.title || 'Job'}</p>
                                    </div>
                                    <strong className={`status-pill status-pill--${app.status}`}>{app.status}</strong>
                                </Link>
                            ))}
                        </div>
                    </div>
                    <aside className="sidebar-stack">
                        <div className="surface-card surface-card--accent">
                            <p className="eyebrow">Status</p>
                            <h2>Breakdown aplikasi</h2>
                            <div className="stack-list stack-list--tight">
                                <div className="metric-line"><span>Pending</span><strong>{payload.pendingApplications}</strong></div>
                                <div className="metric-line"><span>Reviewing</span><strong>{payload.reviewingApplications}</strong></div>
                                <div className="metric-line"><span>Shortlisted</span><strong>{payload.shortlistedApplications}</strong></div>
                                <div className="metric-line"><span>Accepted</span><strong>{payload.acceptedApplications}</strong></div>
                                <div className="metric-line"><span>Rejected</span><strong>{payload.rejectedApplications}</strong></div>
                            </div>
                        </div>
                        <div className="surface-card">
                            <p className="eyebrow">Most applied jobs</p>
                            <div className="stack-list stack-list--tight">
                                {(payload.popularJobs || []).map((job) => (
                                    <div key={job.id} className="metric-line">
                                        <span>{job.title}</span>
                                        <strong>{job.applications_count}</strong>
                                    </div>
                                ))}
                            </div>
                        </div>
                    </aside>
                </div>
            </section>
        </div>
    );
}
