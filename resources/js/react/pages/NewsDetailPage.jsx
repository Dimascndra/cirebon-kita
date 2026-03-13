import React, { useEffect, useState } from 'react';
import { Link, useParams } from 'react-router-dom';
import { api } from '../lib/api';
import { formatDate, imageUrl } from '../lib/utils';

export default function NewsDetailPage() {
    const { slug } = useParams();
    const [post, setPost] = useState(null);
    const [related, setRelated] = useState([]);
    const [trending, setTrending] = useState([]);

    useEffect(() => {
        let active = true;

        api.get(`/news/${slug}`).then((response) => {
            if (!active) {
                return;
            }

            const data = response.data?.data ?? {};
            setPost(data.post ?? null);
            setRelated(data.related ?? []);
            setTrending(data.trending ?? []);
        });

        return () => {
            active = false;
        };
    }, [slug]);

    if (!post) {
        return <div className="container page-section">Memuat artikel...</div>;
    }

    return (
        <div className="page">
            <section className="page-header page-header--article">
                <div className="container article-hero">
                    <div>
                        <p className="eyebrow">{post.category?.name || 'Berita'}</p>
                        <h1>{post.title}</h1>
                        <p>{formatDate(post.published_at)}</p>
                    </div>
                    {imageUrl(post.image) && <img src={imageUrl(post.image)} alt={post.title} className="article-hero__image" />}
                </div>
            </section>
            <section className="page-section page-section--tight">
                <div className="container page-grid">
                    <article className="surface-card article-body" dangerouslySetInnerHTML={{ __html: post.content }} />
                    <aside className="sidebar-stack">
                        <div className="surface-card">
                            <p className="eyebrow">Trending</p>
                            <div className="stack-list stack-list--tight">
                                {trending.map((item) => (
                                    <Link key={item.id} to={`/news/${item.slug}`} className="list-link">
                                        {item.title}
                                    </Link>
                                ))}
                            </div>
                        </div>
                        <div className="surface-card">
                            <p className="eyebrow">Terkait</p>
                            <div className="stack-list stack-list--tight">
                                {related.map((item) => (
                                    <Link key={item.id} to={`/news/${item.slug}`} className="list-link">
                                        {item.title}
                                    </Link>
                                ))}
                            </div>
                        </div>
                    </aside>
                </div>
            </section>
        </div>
    );
}
