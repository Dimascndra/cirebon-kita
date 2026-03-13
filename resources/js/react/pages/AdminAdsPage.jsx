import React, { useEffect, useState } from 'react';
import axios from 'axios';
import { api } from '../lib/api';

const initialForm = {
    title: '',
    image: null,
    url: '',
    placement: 'header',
    start_date: '',
    end_date: '',
    is_active: true,
};

function toDateTimeLocal(value) {
    if (!value) return '';
    const date = new Date(value);
    if (Number.isNaN(date.getTime())) return '';
    const offset = date.getTimezoneOffset();
    const adjusted = new Date(date.getTime() - offset * 60000);
    return adjusted.toISOString().slice(0, 16);
}

export default function AdminAdsPage() {
    const [items, setItems] = useState([]);
    const [form, setForm] = useState(initialForm);
    const [editingId, setEditingId] = useState(null);

    function load() {
        api.get('/admin/ads').then((response) => setItems(response.data?.data ?? []));
    }

    useEffect(() => {
        load();
    }, []);

    async function edit(id) {
        const response = await api.get(`/admin/ads/${id}`);
        const item = response.data?.data ?? {};
        setEditingId(id);
        setForm({
            title: item.title || '',
            image: null,
            url: item.url || '',
            placement: item.placement || 'header',
            start_date: toDateTimeLocal(item.start_date),
            end_date: toDateTimeLocal(item.end_date),
            is_active: Boolean(item.is_active),
        });
    }

    async function submit(event) {
        event.preventDefault();
        const endpoint = editingId ? `/admin/ads/${editingId}` : '/admin/ads';
        const formData = new FormData();
        formData.append('title', form.title);
        formData.append('url', form.url);
        formData.append('placement', form.placement);
        if (form.start_date) formData.append('start_date', form.start_date);
        if (form.end_date) formData.append('end_date', form.end_date);
        if (form.image) formData.append('image', form.image);
        if (form.is_active) formData.append('is_active', '1');
        if (editingId) formData.append('_method', 'PUT');

        await axios.post(endpoint, formData, { headers: { Accept: 'application/json' } });
        setForm(initialForm);
        setEditingId(null);
        load();
    }

    async function remove(id) {
        if (!window.confirm('Hapus banner ini?')) return;
        await axios.delete(`/admin/ads/${id}`, { headers: { Accept: 'application/json' } });
        load();
    }

    return (
        <div className="page">
            <section className="page-header">
                <div className="container">
                    <p className="eyebrow">Admin Ads</p>
                    <h1>Manajemen banner iklan</h1>
                </div>
            </section>
            <section className="page-section page-section--tight">
                <div className="container page-grid admin-grid">
                    <div className="surface-card">
                        <div className="stack-list stack-list--tight">
                            {items.map((item) => (
                                <div key={item.id} className="surface-card surface-card--row surface-card--nested">
                                    <div>
                                        <p className="meta-text">{item.placement} · {item.status}</p>
                                        <h3>{item.title}</h3>
                                        <p>{item.ctr} · {item.clicks} clicks · {item.impressions} impressions</p>
                                    </div>
                                    <div className="inline-actions">
                                        <button type="button" className="button button--secondary" onClick={() => edit(item.id)}>Edit</button>
                                        <button type="button" className="button button--secondary" onClick={() => remove(item.id)}>Delete</button>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                    <aside className="sidebar-stack">
                        <form className="surface-card form-stack" onSubmit={submit}>
                            <p className="eyebrow">{editingId ? 'Edit banner' : 'Banner baru'}</p>
                            <input type="text" placeholder="Title" value={form.title} onChange={(e) => setForm((c) => ({ ...c, title: e.target.value }))} required />
                            <input type="file" accept="image/*" onChange={(e) => setForm((c) => ({ ...c, image: e.target.files?.[0] || null }))} required={!editingId} />
                            <input type="url" placeholder="Target URL" value={form.url} onChange={(e) => setForm((c) => ({ ...c, url: e.target.value }))} />
                            <select value={form.placement} onChange={(e) => setForm((c) => ({ ...c, placement: e.target.value }))}>
                                <option value="header">Header</option>
                                <option value="sidebar">Sidebar</option>
                                <option value="homepage">Homepage</option>
                                <option value="footer">Footer</option>
                            </select>
                            <input type="datetime-local" value={form.start_date} onChange={(e) => setForm((c) => ({ ...c, start_date: e.target.value }))} />
                            <input type="datetime-local" value={form.end_date} onChange={(e) => setForm((c) => ({ ...c, end_date: e.target.value }))} />
                            <label className="check-row"><input type="checkbox" checked={form.is_active} onChange={(e) => setForm((c) => ({ ...c, is_active: e.target.checked }))} /><span>Active</span></label>
                            <button type="submit" className="button button--primary button--block">{editingId ? 'Update' : 'Create'}</button>
                        </form>
                    </aside>
                </div>
            </section>
        </div>
    );
}
