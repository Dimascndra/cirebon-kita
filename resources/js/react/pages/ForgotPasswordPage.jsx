import React, { useState } from 'react';
import axios from 'axios';

export default function ForgotPasswordPage() {
    const [email, setEmail] = useState('');
    const [message, setMessage] = useState('');
    const [error, setError] = useState('');

    async function submit(event) {
        event.preventDefault();
        setMessage('');
        setError('');

        try {
            const response = await axios.post('/forgot-password', { email }, { headers: { Accept: 'application/json' } });
            setMessage(response.data?.message || 'Link reset password telah dikirim.');
        } catch (requestError) {
            setError(requestError.response?.data?.message || 'Gagal mengirim link reset password.');
        }
    }

    return (
        <div className="auth-page">
            <form className="auth-card" onSubmit={submit}>
                <p className="eyebrow">Password Reset</p>
                <h1>Lupa password</h1>
                <input type="email" placeholder="Email" value={email} onChange={(e) => setEmail(e.target.value)} required />
                {message && <div className="notice">{message}</div>}
                {error && <div className="notice notice--error">{error}</div>}
                <button type="submit" className="button button--primary button--block">Kirim link reset</button>
            </form>
        </div>
    );
}
