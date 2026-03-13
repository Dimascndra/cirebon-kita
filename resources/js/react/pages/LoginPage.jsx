import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import axios from 'axios';
import Icon from '../components/Icon';

export default function LoginPage({ onSuccess }) {
    const [form, setForm] = useState({ email: '', password: '' });
    const [error, setError] = useState('');
    const [loading, setLoading] = useState(false);

    async function submit(event) {
        event.preventDefault();
        setLoading(true);
        setError('');

        try {
            const response = await axios.post('/login-web', form);
            onSuccess(response.data?.data ?? null);
        } catch (requestError) {
            setError(requestError.response?.data?.message || 'Login gagal.');
        } finally {
            setLoading(false);
        }
    }

    return (
        <div className="auth-page">
            <div className="auth-layout">
                <section className="auth-panel auth-panel--intro">
                    <p className="eyebrow">Masuk</p>
                    <h1>Masuk ke workspace CirebonKita.</h1>
                    <p>
                        Akses dashboard kandidat, panel perusahaan, atau area admin dari satu alur login yang sama.
                    </p>
                    <div className="auth-feature-list">
                        <div className="metric-line metric-line--stacked metric-line--with-icon">
                            <span className="metric-line__icon">
                                <Icon name="compass" />
                            </span>
                            <span>Navigasi cepat</span>
                            <strong>React SPA untuk flow harian yang lebih ringan</strong>
                        </div>
                        <div className="metric-line metric-line--stacked metric-line--with-icon">
                            <span className="metric-line__icon">
                                <Icon name="shield" />
                            </span>
                            <span>Kontrol akses</span>
                            <strong>Role dan permission tetap dikelola Laravel</strong>
                        </div>
                    </div>
                </section>
                <form className="auth-card" onSubmit={submit}>
                    <p className="eyebrow">Akun Anda</p>
                    <h1>Login</h1>
                    <input
                        type="email"
                        placeholder="Email"
                        value={form.email}
                        onChange={(event) => setForm((current) => ({ ...current, email: event.target.value }))}
                    />
                    <input
                        type="password"
                        placeholder="Password"
                        value={form.password}
                        onChange={(event) => setForm((current) => ({ ...current, password: event.target.value }))}
                    />
                    {error && <div className="notice notice--error">{error}</div>}
                    <button type="submit" className="button button--primary button--block" disabled={loading}>
                        <Icon name="arrow" />
                        {loading ? 'Memproses...' : 'Login'}
                    </button>
                    <p className="auth-card__hint">
                        Belum punya akun? <Link to="/register">Daftar di sini</Link>
                    </p>
                </form>
            </div>
        </div>
    );
}
