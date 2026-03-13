import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import axios from 'axios';
import { api } from '../lib/api';

export default function AdminJobsPage() {
    const [items, setItems] = useState([]);

    function load() {
        api.get('/admin/jobs').then((response) => setItems(response.data?.data ?? []));
    }

    useEffect(() => {
        load();
    }, []);

    async function remove(id) {
        if (!window.confirm('Hapus lowongan ini?')) return;
        await axios.delete(`/admin/jobs/${id}`, { headers: { Accept: 'application/json' } });
        load();
    }

    return (
        <div className="page">
            <section className="page-header">
                <div className="container section-heading">
                    <div>
                        <p className="eyebrow">Admin Jobs</p>
                        <h1>Kelola lowongan</h1>
                        <p>List lowongan admin sekarang juga dirender React.</p>
                    </div>
                    <Link to="/admin/jobs/create" className="button button--primary">Lowongan baru</Link>
                </div>
            </section>
            <section className="page-section page-section--tight">
                <div className="container stack-list">
                    {items.map((item) => (
                        <div key={item.id} className="surface-card surface-card--row">
                            <div>
                                <p className="meta-text">{item.company || '-'} · {item.type} · {item.status}</p>
                                <h3>{item.title}</h3>
                                <p>{item.location}</p>
                            </div>
                            <div className="inline-actions">
                                <Link to={`/admin/jobs/${item.id}/edit`} className="button button--secondary">Edit</Link>
                                <button type="button" className="button button--secondary" onClick={() => remove(item.id)}>Delete</button>
                            </div>
                        </div>
                    ))}
                </div>
            </section>
        </div>
    );
}
