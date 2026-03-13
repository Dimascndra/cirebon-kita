import React, { useEffect, useMemo, useState } from 'react';
import axios from 'axios';
import { api } from '../lib/api';
import { formatDate } from '../lib/utils';

const emptyForm = { name: '', email: '', password: '', role: '' };

export default function AdminUsersPage() {
    const [payload, setPayload] = useState(null);
    const [form, setForm] = useState(emptyForm);
    const [editingId, setEditingId] = useState(null);
    const [message, setMessage] = useState('');

    const roles = useMemo(() => payload?.roles ?? [], [payload]);
    const users = useMemo(() => payload?.users?.data ?? [], [payload]);

    function load() {
        api.get('/admin/users').then((response) => setPayload(response.data?.data ?? null));
    }

    useEffect(() => {
        load();
    }, []);

    async function submit(event) {
        event.preventDefault();
        setMessage('');

        const endpoint = editingId ? `/admin/users/${editingId}` : '/admin/users';
        const formData = new FormData();
        formData.append('name', form.name);
        formData.append('email', form.email);
        formData.append('role', form.role);
        if (form.password) {
            formData.append('password', form.password);
        }
        if (editingId) {
            formData.append('_method', 'PUT');
        } else {
            formData.append('password', form.password);
        }

        await axios.post(endpoint, formData, {
            headers: {
                Accept: 'application/json',
            },
        });

        setForm(emptyForm);
        setEditingId(null);
        setMessage(editingId ? 'User updated.' : 'User created.');
        load();
    }

    async function remove(id) {
        if (!window.confirm('Hapus user ini?')) {
            return;
        }

        await axios.delete(`/admin/users/${id}`, {
            headers: { Accept: 'application/json' },
        });
        load();
    }

    function startEdit(user) {
        setEditingId(user.id);
        setForm({
            name: user.name,
            email: user.email,
            password: '',
            role: user.roles?.[0]?.name || '',
        });
    }

    return (
        <div className="page">
            <section className="page-header">
                <div className="container">
                    <p className="eyebrow">Admin Users</p>
                    <h1>Manajemen pengguna</h1>
                    <p>Daftar user dan perubahan role sekarang berjalan dari React.</p>
                </div>
            </section>
            <section className="page-section page-section--tight">
                <div className="container page-grid admin-grid">
                    <div className="surface-card">
                        <div className="section-heading">
                            <div>
                                <p className="eyebrow">Users</p>
                                <h2>{payload?.users?.total ?? 0} pengguna</h2>
                            </div>
                        </div>
                        <div className="stack-list stack-list--tight">
                            {users.map((user) => (
                                <div key={user.id} className="surface-card surface-card--row surface-card--nested">
                                    <div>
                                        <p className="meta-text">{user.email}</p>
                                        <h3>{user.name}</h3>
                                        <p>{user.roles?.map((role) => role.name).join(', ') || 'No role'} · {formatDate(user.created_at)}</p>
                                    </div>
                                    <div className="inline-actions">
                                        <button type="button" className="button button--secondary" onClick={() => startEdit(user)}>Edit</button>
                                        <button type="button" className="button button--secondary" onClick={() => remove(user.id)}>Delete</button>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                    <aside className="sidebar-stack">
                        <form className="surface-card" onSubmit={submit}>
                            <p className="eyebrow">{editingId ? 'Edit user' : 'New user'}</p>
                            <input type="text" placeholder="Nama" value={form.name} onChange={(e) => setForm((c) => ({ ...c, name: e.target.value }))} required />
                            <input type="email" placeholder="Email" value={form.email} onChange={(e) => setForm((c) => ({ ...c, email: e.target.value }))} required />
                            <select value={form.role} onChange={(e) => setForm((c) => ({ ...c, role: e.target.value }))} required>
                                <option value="">Pilih role</option>
                                {roles.map((role) => <option key={role.id} value={role.name}>{role.name}</option>)}
                            </select>
                            <input type="password" placeholder={editingId ? 'Password baru opsional' : 'Password'} value={form.password} onChange={(e) => setForm((c) => ({ ...c, password: e.target.value }))} required={!editingId} />
                            {message && <div className="notice">{message}</div>}
                            <button type="submit" className="button button--primary button--block">{editingId ? 'Update' : 'Create'}</button>
                        </form>
                    </aside>
                </div>
            </section>
        </div>
    );
}
