import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import Icon from '../components/Icon';
import { api } from '../lib/api';
import { formatDate } from '../lib/utils';

export default function DashboardPage() {
    const [payload, setPayload] = useState(null);

    useEffect(() => {
        let active = true;

        api.get('/dashboard').then((response) => {
            if (active) {
                setPayload(response.data?.data ?? null);
            }
        });

        return () => {
            active = false;
        };
    }, []);

    if (!payload) {
        return <div className="container page-section">Memuat dashboard...</div>;
    }

    const stats = payload.stats ?? {};
    const applications = payload.recent_applications ?? [];

    return (
        <div className="page">
            <section className="page-header">
                <div className="container">
                    <p className="eyebrow">Dashboard</p>
                    <h1>Ringkasan akun yang dirancang untuk keputusan cepat.</h1>
                    <p>Statistik, aktivitas terbaru, dan jalur aksi utama disusun dalam satu layar yang lebih mudah dipindai.</p>
                </div>
            </section>
            <section className="page-section page-section--tight">
                <div className="container card-grid card-grid--four">
                    <article className="surface-card surface-card--stat">
                        <span className="stat-icon"><Icon name="briefcase" /></span>
                        <p className="meta-text">Lamaran</p>
                        <h2>{stats.applications_count ?? 0}</h2>
                        <p>Total lamaran yang telah dikirim.</p>
                    </article>
                    <article className="surface-card surface-card--stat">
                        <span className="stat-icon"><Icon name="chart" /></span>
                        <p className="meta-text">Diproses</p>
                        <h2>{stats.reviewing_count ?? 0}</h2>
                        <p>Lamaran yang sedang direview atau shortlist.</p>
                    </article>
                    <article className="surface-card surface-card--stat">
                        <span className="stat-icon"><Icon name="spark" /></span>
                        <p className="meta-text">Diterima</p>
                        <h2>{stats.accepted_count ?? 0}</h2>
                        <p>Status akhir yang sudah diterima.</p>
                    </article>
                    <article className="surface-card surface-card--stat">
                        <span className="stat-icon"><Icon name="newspaper" /></span>
                        <p className="meta-text">Konten</p>
                        <h2>{stats.bookmarked_news_count ?? 0}</h2>
                        <p>Total berita aktif yang tersedia di platform.</p>
                    </article>
                </div>
            </section>
            <section className="page-section page-section--tight">
                <div className="container page-grid">
                    <div className="surface-card">
                        <div className="section-heading">
                            <div>
                                <p className="eyebrow">Lamaran terbaru</p>
                                <h2>Aktivitas terakhir</h2>
                            </div>
                            <Link to="/my-applications">Lihat semua</Link>
                        </div>
                        <div className="stack-list stack-list--tight">
                            {applications.map((item) => (
                                <Link key={item.id} to={`/my-applications/${item.id}`} className="surface-card surface-card--row surface-card--nested">
                                    <div>
                                        <p className="meta-text">{item.job?.company?.name || 'Perusahaan'}</p>
                                        <h3>{item.job?.title || 'Lowongan'}</h3>
                                        <p>{formatDate(item.applied_at || item.created_at)}</p>
                                    </div>
                                    <strong className={`status-pill status-pill--${item.status}`}>{item.status}</strong>
                                </Link>
                            ))}
                        </div>
                    </div>
                    <aside className="sidebar-stack">
                        <div className="surface-card surface-card--accent">
                            <p className="eyebrow">Aksi cepat</p>
                            <h2>Navigasi akun</h2>
                            <div className="stack-list stack-list--tight">
                                <Link to="/profile" className="list-link list-link--light"><Icon name="user" />Edit profil</Link>
                                <Link to="/my-applications" className="list-link list-link--light"><Icon name="chart" />Riwayat lamaran</Link>
                                <Link to="/lowongan" className="list-link list-link--light"><Icon name="briefcase" />Cari lowongan baru</Link>
                            </div>
                        </div>
                    </aside>
                </div>
            </section>
        </div>
    );
}
