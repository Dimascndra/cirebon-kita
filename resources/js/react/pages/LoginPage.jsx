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
        <div className="public-page-shell min-h-screen bg-[#f5f8fc] px-4 py-12 sm:px-6 lg:px-8">
            <div className="mx-auto grid max-w-6xl gap-10 lg:grid-cols-[minmax(0,1.05fr)_minmax(420px,0.95fr)] lg:items-center">
                <section className="rounded-[36px] border border-slate-200 bg-[linear-gradient(180deg,#eef5fb_0%,#f8fbff_100%)] p-8 shadow-[0_22px_70px_rgba(15,23,42,0.06)] lg:p-12">
                    <span className="inline-flex rounded-full border border-cyan-200 bg-white px-4 py-2 text-sm font-medium text-cyan-700 shadow-sm">
                        Masuk
                    </span>
                    <h1 className="mt-6 text-4xl font-semibold tracking-tight text-slate-950 lg:text-5xl">
                        Masuk ke workspace CirebonKita dengan tampilan yang lebih ringan.
                    </h1>
                    <p className="mt-5 text-lg leading-8 text-slate-600">
                        Akses dashboard kandidat, panel perusahaan, atau area admin dari satu halaman login yang konsisten dengan seluruh situs.
                    </p>

                    <div className="mt-10 space-y-5">
                        <div className="flex items-start gap-4 rounded-[24px] border border-slate-200 bg-white p-5 shadow-sm">
                            <div className="rounded-2xl bg-cyan-50 p-3 text-cyan-700">
                                <Icon name="compass" className="h-5 w-5" />
                            </div>
                            <div>
                                <h2 className="text-lg font-semibold text-slate-950">Navigasi cepat</h2>
                                <p className="mt-1 text-sm leading-6 text-slate-600">Flow harian tetap ringan tanpa pindah ke visual yang berbeda-beda.</p>
                            </div>
                        </div>
                        <div className="flex items-start gap-4 rounded-[24px] border border-slate-200 bg-white p-5 shadow-sm">
                            <div className="rounded-2xl bg-emerald-50 p-3 text-emerald-700">
                                <Icon name="shield" className="h-5 w-5" />
                            </div>
                            <div>
                                <h2 className="text-lg font-semibold text-slate-950">Kontrol akses tetap ketat</h2>
                                <p className="mt-1 text-sm leading-6 text-slate-600">Role, permission, dan sesi tetap dikendalikan Laravel di belakang layar.</p>
                            </div>
                        </div>
                    </div>
                </section>

                <section className="rounded-[36px] border border-slate-200 bg-white p-8 shadow-[0_22px_70px_rgba(15,23,42,0.08)] lg:p-12">
                    <span className="inline-flex rounded-full bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700">
                        Akun Anda
                    </span>
                    <h2 className="mt-5 text-3xl font-semibold text-slate-950">Login</h2>

                    <form onSubmit={submit} className="mt-8 space-y-5">
                        <div>
                            <label htmlFor="email" className="mb-2 block text-sm font-medium text-slate-700">
                                Email
                            </label>
                            <input
                                id="email"
                                type="email"
                                required
                                className="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-blue-300 focus:bg-white focus:ring-4 focus:ring-blue-100"
                                placeholder="nama@email.com"
                                value={form.email}
                                onChange={(event) => setForm((current) => ({ ...current, email: event.target.value }))}
                            />
                        </div>

                        <div>
                            <label htmlFor="password" className="mb-2 block text-sm font-medium text-slate-700">
                                Password
                            </label>
                            <input
                                id="password"
                                type="password"
                                required
                                className="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-blue-300 focus:bg-white focus:ring-4 focus:ring-blue-100"
                                placeholder="••••••••"
                                value={form.password}
                                onChange={(event) => setForm((current) => ({ ...current, password: event.target.value }))}
                            />
                        </div>

                        {error && (
                            <div className="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">
                                <div className="flex items-center">
                                    <Icon name="alert-circle" className="mr-2 h-5 w-5 text-rose-600" />
                                    <span>{error}</span>
                                </div>
                            </div>
                        )}

                        <button
                            type="submit"
                            disabled={loading}
                            className="inline-flex w-full items-center justify-center rounded-2xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 disabled:bg-blue-300"
                        >
                            <Icon name="arrow" className="mr-2 h-4 w-4" />
                            {loading ? 'Memproses...' : 'Login'}
                        </button>
                    </form>

                    <p className="mt-8 text-center text-sm text-slate-600">
                        Belum punya akun?{' '}
                        <Link to="/register" className="font-semibold text-blue-700 hover:text-blue-800">
                            Daftar di sini
                        </Link>
                    </p>
                </section>
            </div>
        </div>
    );
}
