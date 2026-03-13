import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import axios from 'axios';
import { api } from '../lib/api';

export default function AdminNewsPage() {
    const [items, setItems] = useState([]);

    function load() {
        api.get('/admin/news').then((response) => setItems(response.data?.data ?? []));
    }

    useEffect(() => {
        load();
    }, []);

    async function remove(id) {
        if (!window.confirm('Hapus artikel ini?')) return;
        await axios.delete(`/admin/news/${id}`, { headers: { Accept: 'application/json' } });
        load();
    }

    return (
        <div className="page">
            <section className="page-header">
                <div className="container section-heading">
                    <div>
                        <p className="eyebrow">Admin News</p>
                        <h1>Kelola artikel</h1>
                        <p>List artikel admin sekarang berjalan penuh di React.</p>
                    </div>
                    <Link to="/admin/news/create" className="button button--primary">Artikel baru</Link>
                </div>
            </section>
            <section className="page-section page-section--tight">
                <div className="container stack-list">
                    {items.map((item) => (
                        <div key={item.id} className="surface-card surface-card--row">
                            <div>
                                <p className="meta-text">{item.category || '-'} · {item.status}</p>
                                <h3>{item.title}</h3>
                                <p>{item.published_at}</p>
                            </div>
                            <div className="inline-actions">
                                <Link to={`/admin/news/${item.id}/edit`} className="button button--secondary">Edit</Link>
                                <button type="button" className="button button--secondary" onClick={() => remove(item.id)}>Delete</button>
                            </div>
                        </div>
                    ))}
                </div>
            </section>
        </div>
    );
}
