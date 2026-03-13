import React, { useState } from 'react';
import axios from 'axios';

export default function VerifyEmailPage() {
    const [message, setMessage] = useState('Silakan verifikasi email Anda.');
    const [error, setError] = useState('');

    async function resend() {
        setError('');
        try {
            await axios.post('/email/verification-notification', {}, { headers: { Accept: 'application/json' } });
            setMessage('Link verifikasi baru telah dikirim.');
        } catch (requestError) {
            setError(requestError.response?.data?.message || 'Gagal mengirim ulang email verifikasi.');
        }
    }

    return (
        <div className="auth-page">
            <div className="auth-card">
                <p className="eyebrow">Email Verification</p>
                <h1>Verifikasi email</h1>
                <p>{message}</p>
                {error && <div className="notice notice--error">{error}</div>}
                <button type="button" className="button button--primary button--block" onClick={resend}>Kirim ulang email verifikasi</button>
            </div>
        </div>
    );
}
