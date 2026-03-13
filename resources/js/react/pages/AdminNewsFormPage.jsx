import React, { useEffect, useState } from 'react';
import axios from 'axios';
import { useNavigate, useParams } from 'react-router-dom';
import { api } from '../lib/api';

const initialForm = {
    title: '',
    category_id: '',
    excerpt: '',
    content: '',
    status: 'published',
    image: null,
};

export default function AdminNewsFormPage() {
    const { id } = useParams();
    const navigate = useNavigate();
    const editing = Boolean(id);
    const [categories, setCategories] = useState([]);
    const [form, setForm] = useState(initialForm);

    useEffect(() => {
        api.get('/admin/news/meta').then((response) => {
            setCategories(response.data?.data?.categories ?? []);
        });

        if (editing) {
            api.get(`/admin/news/${id}`).then((response) => {
                const item = response.data?.data ?? {};
                setForm((current) => ({
                    ...current,
                    title: item.title || '',
                    category_id: item.category_id || '',
                    excerpt: item.excerpt || '',
                    content: item.content || '',
                    status: item.status || 'published',
                }));
            });
        }
    }, [editing, id]);

    async function submit(event) {
        event.preventDefault();
        const endpoint = editing ? `/admin/news/${id}` : '/admin/news';
        const formData = new FormData();
        Object.entries(form).forEach(([key, value]) => {
            if (value !== null && value !== '') {
                formData.append(key, value);
            }
        });
        if (editing) formData.append('_method', 'PUT');

        await axios.post(endpoint, formData, { headers: { Accept: 'application/json' } });
        navigate('/admin/news');
    }

    return (
        <div className="page">
            <section className="page-header">
                <div className="container">
                    <p className="eyebrow">Admin News</p>
                    <h1>{editing ? 'Edit artikel' : 'Artikel baru'}</h1>
                </div>
            </section>
            <section className="page-section page-section--tight">
                <div className="container">
                    <form className="surface-card form-stack" onSubmit={submit}>
                        <input type="text" placeholder="Judul" value={form.title} onChange={(e) => setForm((c) => ({ ...c, title: e.target.value }))} required />
                        <select value={form.category_id} onChange={(e) => setForm((c) => ({ ...c, category_id: e.target.value }))} required>
                            <option value="">Pilih kategori</option>
                            {categories.map((category) => <option key={category.id} value={category.id}>{category.name}</option>)}
                        </select>
                        <textarea className="textarea-control" placeholder="Excerpt" value={form.excerpt} onChange={(e) => setForm((c) => ({ ...c, excerpt: e.target.value }))} rows="4" />
                        <textarea className="textarea-control textarea-control--editor" placeholder="Konten" value={form.content} onChange={(e) => setForm((c) => ({ ...c, content: e.target.value }))} rows="12" />
                        <input type="file" accept="image/*" onChange={(e) => setForm((c) => ({ ...c, image: e.target.files?.[0] || null }))} />
                        <select value={form.status} onChange={(e) => setForm((c) => ({ ...c, status: e.target.value }))}>
                            <option value="published">Published</option>
                            <option value="draft">Draft</option>
                        </select>
                        <button type="submit" className="button button--primary button--block">{editing ? 'Update' : 'Create'}</button>
                    </form>
                </div>
            </section>
        </div>
    );
}
