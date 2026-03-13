import React, { useState } from 'react';
import axios from 'axios';
import { useNavigate, useParams, useSearchParams } from 'react-router-dom';

export default function ResetPasswordPage() {
    const { token } = useParams();
    const [searchParams] = useSearchParams();
    const navigate = useNavigate();
    const [form, setForm] = useState({
        email: searchParams.get('email') || '',
        password: '',
        password_confirmation: '',
    });
    const [message, setMessage] = useState('');
    const [error, setError] = useState('');

    async function submit(event) {
        event.preventDefault();
        setMessage('');
        setError('');

        try {
            const response = await axios.post('/reset-password', { ...form, token }, { headers: { Accept: 'application/json' } });
            setMessage(response.data?.message || 'Password berhasil direset.');
            setTimeout(() => navigate('/login'), 1000);
        } catch (requestError) {
            setError(requestError.response?.data?.message || 'Reset password gagal.');
        }
    }

    return (
        <div className="auth-page">
            <form className="auth-card" onSubmit={submit}>
                <p className="eyebrow">Reset Password</p>
                <h1>Atur password baru</h1>
                <input type="email" placeholder="Email" value={form.email} onChange={(e) => setForm((c) => ({ ...c, email: e.target.value }))} required />
                <input type="password" placeholder="Password baru" value={form.password} onChange={(e) => setForm((c) => ({ ...c, password: e.target.value }))} required />
                <input type="password" placeholder="Konfirmasi password" value={form.password_confirmation} onChange={(e) => setForm((c) => ({ ...c, password_confirmation: e.target.value }))} required />
                {message && <div className="notice">{message}</div>}
                {error && <div className="notice notice--error">{error}</div>}
                <button type="submit" className="button button--primary button--block">Reset password</button>
            </form>
        </div>
    );
}
