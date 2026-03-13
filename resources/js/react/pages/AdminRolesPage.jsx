import React, { useEffect, useMemo, useState } from 'react';
import axios from 'axios';
import { api } from '../lib/api';

const emptyForm = { name: '', permissions: [] };

export default function AdminRolesPage() {
    const [payload, setPayload] = useState(null);
    const [form, setForm] = useState(emptyForm);
    const [editingId, setEditingId] = useState(null);
    const [message, setMessage] = useState('');

    const roles = useMemo(() => payload?.roles ?? [], [payload]);
    const permissions = useMemo(() => payload?.permissions ?? [], [payload]);

    function load() {
        api.get('/admin/roles').then((response) => setPayload(response.data?.data ?? null));
    }

    useEffect(() => {
        load();
    }, []);

    async function submit(event) {
        event.preventDefault();
        setMessage('');

        const endpoint = editingId ? `/admin/roles/${editingId}` : '/admin/roles';
        const formData = new FormData();
        formData.append('name', form.name);
        form.permissions.forEach((permission) => formData.append('permissions[]', permission));
        if (editingId) {
            formData.append('_method', 'PUT');
        }

        await axios.post(endpoint, formData, {
            headers: { Accept: 'application/json' },
        });

        setEditingId(null);
        setForm(emptyForm);
        setMessage(editingId ? 'Role updated.' : 'Role created.');
        load();
    }

    async function remove(id) {
        if (!window.confirm('Hapus role ini?')) {
            return;
        }

        await axios.delete(`/admin/roles/${id}`, {
            headers: { Accept: 'application/json' },
        });
        load();
    }

    function togglePermission(name) {
        setForm((current) => ({
            ...current,
            permissions: current.permissions.includes(name)
                ? current.permissions.filter((item) => item !== name)
                : [...current.permissions, name],
        }));
    }

    function startEdit(role) {
        setEditingId(role.id);
        setForm({
            name: role.name,
            permissions: (role.permissions || []).map((permission) => permission.name),
        });
    }

    return (
        <div className="page">
            <section className="page-header">
                <div className="container">
                    <p className="eyebrow">Admin Roles</p>
                    <h1>Manajemen role dan permission</h1>
                    <p>Role dan permission sekarang bisa dikelola langsung dari React.</p>
                </div>
            </section>
            <section className="page-section page-section--tight">
                <div className="container page-grid admin-grid">
                    <div className="surface-card">
                        <div className="stack-list stack-list--tight">
                            {roles.map((role) => (
                                <div key={role.id} className="surface-card surface-card--nested">
                                    <div className="section-heading">
                                        <div>
                                            <p className="meta-text">{role.users_count} users</p>
                                            <h3>{role.name}</h3>
                                        </div>
                                        <div className="inline-actions">
                                            <button type="button" className="button button--secondary" onClick={() => startEdit(role)}>Edit</button>
                                            {role.name !== 'SuperAdmin' && <button type="button" className="button button--secondary" onClick={() => remove(role.id)}>Delete</button>}
                                        </div>
                                    </div>
                                    <div className="tag-list">
                                        {(role.permissions || []).map((permission) => <span key={permission.id} className="tag">{permission.name}</span>)}
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                    <aside className="sidebar-stack">
                        <form className="surface-card" onSubmit={submit}>
                            <p className="eyebrow">{editingId ? 'Edit role' : 'New role'}</p>
                            <input type="text" placeholder="Nama role" value={form.name} onChange={(e) => setForm((c) => ({ ...c, name: e.target.value }))} required />
                            <div className="permission-list">
                                {permissions.map((permission) => (
                                    <label key={permission.id} className="check-row">
                                        <input type="checkbox" checked={form.permissions.includes(permission.name)} onChange={() => togglePermission(permission.name)} />
                                        <span>{permission.name}</span>
                                    </label>
                                ))}
                            </div>
                            {message && <div className="notice">{message}</div>}
                            <button type="submit" className="button button--primary button--block">{editingId ? 'Update' : 'Create'}</button>
                        </form>
                    </aside>
                </div>
            </section>
        </div>
    );
}
