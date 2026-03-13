import React, { useState } from 'react';
import axios from 'axios';
import { useNavigate } from 'react-router-dom';

export default function ConfirmPasswordPage() {
    const navigate = useNavigate();
    const [password, setPassword] = useState('');
    const [error, setError] = useState('');

    async function submit(event) {
        event.preventDefault();
        setError('');
        try {
            await axios.post('/confirm-password', { password }, { headers: { Accept: 'application/json' } });
            navigate('/dashboard');
        } catch (requestError) {
            setError(requestError.response?.data?.message || 'Konfirmasi password gagal.');
        }
    }

    return (
        <div className="auth-page">
            <form className="auth-card" onSubmit={submit}>
                <p className="eyebrow">Confirm Password</p>
                <h1>Konfirmasi password</h1>
                <input type="password" placeholder="Password" value={password} onChange={(e) => setPassword(e.target.value)} required />
                {error && <div className="notice notice--error">{error}</div>}
                <button type="submit" className="button button--primary button--block">Konfirmasi</button>
            </form>
        </div>
    );
}
