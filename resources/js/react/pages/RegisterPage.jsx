import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import axios from 'axios';
import Icon from '../components/Icon';
import { api } from '../lib/api';

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
        <div className="public-page-shell min-h-screen bg-[#f5f8fc] px-4 py-12 sm:px-6 lg:px-8">
            <div className="mx-auto grid max-w-6xl gap-10 lg:grid-cols-[minmax(0,1.05fr)_minmax(420px,0.95fr)] lg:items-center">
                <section className="rounded-[36px] border border-slate-200 bg-[linear-gradient(180deg,#eef5fb_0%,#f8fbff_100%)] p-8 shadow-[0_22px_70px_rgba(15,23,42,0.06)] lg:p-12">
                    <span className="inline-flex rounded-full border border-emerald-200 bg-white px-4 py-2 text-sm font-medium text-emerald-700 shadow-sm">
                        Registrasi
                    </span>
                    <h1 className="mt-6 text-4xl font-semibold tracking-tight text-slate-950 lg:text-5xl">
                        Buat akun dan masuk ke alur kerja yang relevan dengan tema yang seragam.
                    </h1>
                    <p className="mt-5 text-lg leading-8 text-slate-600">
                        Kandidat bisa melamar lebih cepat, perusahaan memantau aplikasi lebih mudah, dan admin tetap memegang kontrol yang sama kuatnya.
                    </p>

                    <div className="mt-10 space-y-5">
                        <div className="flex items-start gap-4 rounded-[24px] border border-slate-200 bg-white p-5 shadow-sm">
                            <div className="rounded-2xl bg-amber-50 p-3 text-amber-700">
                                <Icon name="spark" className="h-5 w-5" />
                            </div>
                            <div>
                                <h2 className="text-lg font-semibold text-slate-950">Satu titik masuk</h2>
                                <p className="mt-1 text-sm leading-6 text-slate-600">Satu akun untuk seluruh area aplikasi sesuai role yang Anda miliki.</p>
                            </div>
                        </div>
                        <div className="flex items-start gap-4 rounded-[24px] border border-slate-200 bg-white p-5 shadow-sm">
                            <div className="rounded-2xl bg-emerald-50 p-3 text-emerald-700">
                                <Icon name="shield" className="h-5 w-5" />
                            </div>
                            <div>
                                <h2 className="text-lg font-semibold text-slate-950">Backend tetap ketat</h2>
                                <p className="mt-1 text-sm leading-6 text-slate-600">Session, CSRF, dan aturan akses tetap dijaga Laravel seperti semula.</p>
                            </div>
                        </div>
                    </div>
                </section>

                <section className="rounded-[36px] border border-slate-200 bg-white p-8 shadow-[0_22px_70px_rgba(15,23,42,0.08)] lg:p-12">
                    <span className="inline-flex rounded-full bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700">
                        Buat akun
                    </span>
                    <h2 className="mt-5 text-3xl font-semibold text-slate-950">Register</h2>

                    <form onSubmit={submit} className="mt-8 space-y-5">
                        <div>
                            <label htmlFor="name" className="mb-2 block text-sm font-medium text-slate-700">
                                Nama lengkap
                            </label>
                            <input
                                id="name"
                                type="text"
                                required
                                className="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-blue-300 focus:bg-white focus:ring-4 focus:ring-blue-100"
                                placeholder="John Doe"
                                value={form.name}
                                onChange={(event) => setForm((current) => ({ ...current, name: event.target.value }))}
                            />
                        </div>

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

                        <div>
                            <label htmlFor="password_confirmation" className="mb-2 block text-sm font-medium text-slate-700">
                                Konfirmasi password
                            </label>
                            <input
                                id="password_confirmation"
                                type="password"
                                required
                                className="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-blue-300 focus:bg-white focus:ring-4 focus:ring-blue-100"
                                placeholder="••••••••"
                                value={form.password_confirmation}
                                onChange={(event) => setForm((current) => ({ ...current, password_confirmation: event.target.value }))}
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
                            {loading ? 'Memproses...' : 'Daftar'}
                        </button>
                    </form>

                    <p className="mt-8 text-center text-sm text-slate-600">
                        Sudah punya akun?{' '}
                        <Link to="/login" className="font-semibold text-blue-700 hover:text-blue-800">
                            Login di sini
                        </Link>
                    </p>
                </section>
            </div>
        </div>
    );
}
