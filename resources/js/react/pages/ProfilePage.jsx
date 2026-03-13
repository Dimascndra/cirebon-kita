import React, { useEffect, useState } from 'react';
import { api, setStoredUser } from '../lib/api';

export default function ProfilePage() {
    const [form, setForm] = useState({
        name: '',
        email: '',
        current_password: '',
        new_password: '',
        new_password_confirmation: '',
    });
    const [message, setMessage] = useState('');
    const [error, setError] = useState('');
    const [loading, setLoading] = useState(false);

    useEffect(() => {
        let active = true;

        api.get('/profile').then((response) => {
            if (!active) {
                return;
            }

            const user = response.data?.data ?? {};
            setForm((current) => ({ ...current, name: user.name || '', email: user.email || '' }));
        });

        return () => {
            active = false;
        };
    }, []);

    async function submit(event) {
        event.preventDefault();
        setLoading(true);
        setMessage('');
        setError('');

        try {
            const response = await api.post('/profile', form);
            const user = response.data?.data ?? null;
            if (user) {
                setStoredUser(user);
            }
            setMessage(response.data?.message || 'Profil berhasil diperbarui.');
            setForm((current) => ({
                ...current,
                current_password: '',
                new_password: '',
                new_password_confirmation: '',
            }));
        } catch (requestError) {
            setError(requestError.response?.data?.message || 'Gagal memperbarui profil.');
        } finally {
            setLoading(false);
        }
    }

    return (
        <div className="page">
            <section className="page-header">
                <div className="container">
                    <p className="eyebrow">Profil</p>
                    <h1>Kelola akun Anda</h1>
                    <p>Update nama, email, dan password tanpa keluar dari frontend React.</p>
                </div>
            </section>
            <section className="page-section page-section--tight">
                <div className="container">
                    <form className="auth-card auth-card--wide" onSubmit={submit}>
                        <input type="text" placeholder="Nama" value={form.name} onChange={(e) => setForm((c) => ({ ...c, name: e.target.value }))} />
                        <input type="email" placeholder="Email" value={form.email} onChange={(e) => setForm((c) => ({ ...c, email: e.target.value }))} />
                        <input type="password" placeholder="Password saat ini" value={form.current_password} onChange={(e) => setForm((c) => ({ ...c, current_password: e.target.value }))} />
                        <input type="password" placeholder="Password baru" value={form.new_password} onChange={(e) => setForm((c) => ({ ...c, new_password: e.target.value }))} />
                        <input type="password" placeholder="Konfirmasi password baru" value={form.new_password_confirmation} onChange={(e) => setForm((c) => ({ ...c, new_password_confirmation: e.target.value }))} />
                        {message && <div className="notice">{message}</div>}
                        {error && <div className="notice notice--error">{error}</div>}
                        <button type="submit" className="button button--primary button--block" disabled={loading}>
                            {loading ? 'Menyimpan...' : 'Simpan perubahan'}
                        </button>
                    </form>
                </div>
            </section>
        </div>
    );
}
