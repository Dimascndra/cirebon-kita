import React, { useEffect, useState } from 'react';
import Icon from '../components/Icon';
import { api } from '../lib/api';

export default function AdminDashboardPage() {
    const [payload, setPayload] = useState(null);

    useEffect(() => {
        let active = true;
        api.get('/admin/dashboard').then((response) => {
            if (active) {
                setPayload(response.data?.data ?? null);
            }
        });
        return () => {
            active = false;
        };
    }, []);

    if (!payload) {
        return <div className="container page-section">Memuat admin dashboard...</div>;
    }

    return (
        <div className="page">
            <section className="page-header">
                <div className="container">
                    <p className="eyebrow">Admin Dashboard</p>
                    <h1>Kontrol utama sistem dengan struktur yang lebih mudah dibaca.</h1>
                    <p>Ringkasan operasional, pertumbuhan user, dan konten yang bergerak cepat ditampilkan dalam satu workspace.</p>
                </div>
            </section>
            <section className="page-section page-section--tight">
                <div className="container card-grid card-grid--three">
                    <article className="surface-card surface-card--stat"><span className="stat-icon"><Icon name="user" /></span><p className="meta-text">Total users</p><h2>{payload.totalUsers}</h2></article>
                    <article className="surface-card surface-card--stat"><span className="stat-icon"><Icon name="briefcase" /></span><p className="meta-text">Active jobs</p><h2>{payload.activeJobs}</h2></article>
                    <article className="surface-card surface-card--stat"><span className="stat-icon"><Icon name="newspaper" /></span><p className="meta-text">Article views</p><h2>{payload.totalViews}</h2></article>
                </div>
            </section>
            <section className="page-section page-section--tight">
                <div className="container page-grid">
                    <div className="surface-card">
                        <p className="eyebrow">User growth</p>
                        <div className="stack-list stack-list--tight">
                            {(payload.dates || []).map((label, index) => (
                                <div key={label} className="metric-line metric-line--compact">
                                    <span>{label}</span>
                                    <strong>{payload.counts?.[index] ?? 0}</strong>
                                </div>
                            ))}
                        </div>
                    </div>
                    <aside className="sidebar-stack">
                        <div className="surface-card surface-card--accent">
                            <p className="eyebrow">Trending news</p>
                            <div className="stack-list stack-list--tight">
                                {(payload.trendingPosts || []).map((post) => (
                                    <div key={post.id} className="metric-line metric-line--stacked metric-line--with-icon">
                                        <span className="metric-line__icon">
                                            <Icon name="spark" />
                                        </span>
                                        <span>{post.title}</span>
                                        <strong>{post.views} views</strong>
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
