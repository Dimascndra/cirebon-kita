import React, { useEffect, useState } from 'react';
import { Link, useSearchParams } from 'react-router-dom';
import { api } from '../lib/api';
import { excerpt, formatDate, unwrapList } from '../lib/utils';

export default function NewsPage() {
    const [searchParams, setSearchParams] = useSearchParams();
    const [categories, setCategories] = useState([]);
    const [items, setItems] = useState([]);
    const [meta, setMeta] = useState(null);
    const [loading, setLoading] = useState(true);

    const search = searchParams.get('search') ?? '';
    const category = searchParams.get('category') ?? '';
    const sort = searchParams.get('sort') ?? 'newest';
    const page = searchParams.get('page') ?? '1';

    useEffect(() => {
        api.get('/news/categories').then((response) => {
            setCategories(response.data?.data ?? []);
        });
    }, []);

    useEffect(() => {
        let active = true;
        setLoading(true);

        api.get('/news', { params: { search, category, sort, page } }).then((response) => {
            if (!active) {
                return;
            }

            const result = unwrapList(response.data);
            setItems(result.items);
            setMeta(result.meta);
            setLoading(false);
        });

        return () => {
            active = false;
        };
    }, [search, category, sort, page]);

    function updateParams(next) {
        const params = new URLSearchParams(searchParams);
        Object.entries(next).forEach(([key, value]) => {
            if (!value) {
                params.delete(key);
            } else {
                params.set(key, value);
            }
        });
        params.delete('page');
        setSearchParams(params);
    }

    return (
        <div className="page">
            <section className="page-header">
                <div className="container">
                    <p className="eyebrow">Berita</p>
                    <h1>Semua berita dalam frontend React</h1>
                    <p>Filter, pencarian, dan pagination berjalan langsung dari React ke API Laravel.</p>
                </div>
            </section>
            <section className="page-section page-section--tight">
                <div className="container filter-bar">
                    <input value={search} onChange={(e) => updateParams({ search: e.target.value })} placeholder="Cari berita" />
                    <select value={category} onChange={(e) => updateParams({ category: e.target.value })}>
                        <option value="">Semua kategori</option>
                        {categories.map((item) => (
                            <option key={item.id} value={item.slug}>
                                {item.name}
                            </option>
                        ))}
                    </select>
                    <select value={sort} onChange={(e) => updateParams({ sort: e.target.value })}>
                        <option value="newest">Terbaru</option>
                        <option value="popular">Terpopuler</option>
                        <option value="oldest">Terlama</option>
                    </select>
                </div>
            </section>
            <section className="page-section page-section--tight">
                <div className="container card-grid card-grid--three">
                    {loading && <div className="surface-card">Memuat berita...</div>}
                    {!loading &&
                        items.map((item) => (
                            <Link key={item.id} to={`/news/${item.slug}`} className="surface-card surface-card--link">
                                <p className="meta-text">{item.category?.name || 'Berita'} · {formatDate(item.published_at)}</p>
                                <h3>{item.title}</h3>
                                <p>{excerpt(item.excerpt)}</p>
                            </Link>
                        ))}
                </div>
                {meta && meta.last_page > 1 && (
                    <div className="container pager">
                        {Array.from({ length: meta.last_page }, (_, index) => index + 1).map((pageNumber) => (
                            <button
                                key={pageNumber}
                                type="button"
                                className={pageNumber === Number(page) ? 'pager__button pager__button--active' : 'pager__button'}
                                onClick={() => setSearchParams((current) => {
                                    const params = new URLSearchParams(current);
                                    params.set('page', String(pageNumber));
                                    return params;
                                })}
                            >
                                {pageNumber}
                            </button>
                        ))}
                    </div>
                )}
            </section>
        </div>
    );
}
