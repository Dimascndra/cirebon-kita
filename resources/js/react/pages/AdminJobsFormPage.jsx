import React, { useEffect, useState } from 'react';
import axios from 'axios';
import { useNavigate, useParams } from 'react-router-dom';
import { api } from '../lib/api';

const initialForm = {
    title: '',
    company_id: '',
    location: '',
    type: 'Full-time',
    salary_range: '',
    description: '',
    status: 'active',
};

export default function AdminJobsFormPage() {
    const { id } = useParams();
    const navigate = useNavigate();
    const editing = Boolean(id);
    const [companies, setCompanies] = useState([]);
    const [form, setForm] = useState(initialForm);

    useEffect(() => {
        api.get('/admin/jobs/meta').then((response) => {
            setCompanies(response.data?.data?.companies ?? []);
        });

        if (editing) {
            api.get(`/admin/jobs/${id}`).then((response) => {
                const item = response.data?.data ?? {};
                setForm({
                    title: item.title || '',
                    company_id: item.company_id || '',
                    location: item.location || '',
                    type: item.type || 'Full-time',
                    salary_range: item.salary_range || '',
                    description: item.description || '',
                    status: item.status || 'active',
                });
            });
        }
    }, [editing, id]);

    async function submit(event) {
        event.preventDefault();
        const endpoint = editing ? `/admin/jobs/${id}` : '/admin/jobs';
        const formData = new FormData();
        Object.entries(form).forEach(([key, value]) => formData.append(key, value));
        if (editing) formData.append('_method', 'PUT');

        await axios.post(endpoint, formData, { headers: { Accept: 'application/json' } });
        navigate('/admin/jobs');
    }

    return (
        <div className="page">
            <section className="page-header">
                <div className="container">
                    <p className="eyebrow">Admin Jobs</p>
                    <h1>{editing ? 'Edit lowongan' : 'Lowongan baru'}</h1>
                </div>
            </section>
            <section className="page-section page-section--tight">
                <div className="container">
                    <form className="surface-card form-stack" onSubmit={submit}>
                        <input type="text" placeholder="Judul lowongan" value={form.title} onChange={(e) => setForm((c) => ({ ...c, title: e.target.value }))} required />
                        <select value={form.company_id} onChange={(e) => setForm((c) => ({ ...c, company_id: e.target.value }))} required>
                            <option value="">Pilih perusahaan</option>
                            {companies.map((company) => <option key={company.id} value={company.id}>{company.name}</option>)}
                        </select>
                        <input type="text" placeholder="Lokasi" value={form.location} onChange={(e) => setForm((c) => ({ ...c, location: e.target.value }))} required />
                        <select value={form.type} onChange={(e) => setForm((c) => ({ ...c, type: e.target.value }))}>
                            {['Full-time', 'Part-time', 'Contract', 'Internship', 'Freelance'].map((type) => <option key={type} value={type}>{type}</option>)}
                        </select>
                        <input type="text" placeholder="Salary range" value={form.salary_range} onChange={(e) => setForm((c) => ({ ...c, salary_range: e.target.value }))} />
                        <textarea className="textarea-control textarea-control--editor" placeholder="Deskripsi" value={form.description} onChange={(e) => setForm((c) => ({ ...c, description: e.target.value }))} rows="12" />
                        <select value={form.status} onChange={(e) => setForm((c) => ({ ...c, status: e.target.value }))}>
                            <option value="active">Active</option>
                            <option value="closed">Closed</option>
                        </select>
                        <button type="submit" className="button button--primary button--block">{editing ? 'Update' : 'Create'}</button>
                    </form>
                </div>
            </section>
        </div>
    );
}
