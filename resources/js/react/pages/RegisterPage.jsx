import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import { api } from '../lib/api';
import axios from 'axios';
import Icon from '../components/Icon';

export default function RegisterPage({ onSuccess }) {
    const [form, setForm] = useState({ name: '', email: '', password: '', password_confirmation: '' });
    const [error, setError] = useState('');
    const [loading, setLoading] = useState(false);

    async function submit(event) {
        event.preventDefault();
        setLoading(true);
        setError('');

        try {
            await api.post('/register', form);
            const loginResponse = await axios.post('/login-web', {
                email: form.email,
                password: form.password,
            });
            onSuccess(loginResponse.data?.data ?? null);
        } catch (requestError) {
            setError(requestError.response?.data?.message || 'Registrasi gagal.');
        } finally {
            setLoading(false);
        }
    }

    return (
        <div className="auth-page">
            <div className="auth-layout">
                <section className="auth-panel auth-panel--intro">
                    <p className="eyebrow">Registrasi</p>
                    <h1>Buat akun dan masuk ke alur kerja yang relevan.</h1>
                    <p>
                        Kandidat bisa melamar lebih cepat, perusahaan bisa memantau aplikasi, dan admin tetap memegang kontrol.
                    </p>
                    <div className="auth-feature-list">
                        <div className="metric-line metric-line--stacked metric-line--with-icon">
                            <span className="metric-line__icon">
                                <Icon name="spark" />
                            </span>
                            <span>Single access point</span>
                            <strong>Satu akun untuk seluruh area aplikasi sesuai role</strong>
                        </div>
                        <div className="metric-line metric-line--stacked metric-line--with-icon">
                            <span className="metric-line__icon">
                                <Icon name="shield" />
                            </span>
                            <span>Backend tetap ketat</span>
                            <strong>Session, CSRF, dan aturan akses berjalan di Laravel</strong>
                        </div>
                    </div>
                </section>
                <form className="auth-card" onSubmit={submit}>
                    <p className="eyebrow">Buat akun</p>
                    <h1>Register</h1>
                    <input
                        type="text"
                        placeholder="Nama"
                        value={form.name}
                        onChange={(event) => setForm((current) => ({ ...current, name: event.target.value }))}
                    />
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
                    <input
                        type="password"
                        placeholder="Konfirmasi password"
                        value={form.password_confirmation}
                        onChange={(event) =>
                            setForm((current) => ({ ...current, password_confirmation: event.target.value }))
                        }
                    />
                    {error && <div className="notice notice--error">{error}</div>}
                    <button type="submit" className="button button--primary button--block" disabled={loading}>
                        <Icon name="arrow" />
                        {loading ? 'Memproses...' : 'Daftar'}
                    </button>
                    <p className="auth-card__hint">
                        Sudah punya akun? <Link to="/login">Login di sini</Link>
                    </p>
                </form>
            </div>
        </div>
    );
}
