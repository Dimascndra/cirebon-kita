<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <!-- Home -->
    <url>
        <loc>{{ route('home') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>

    <!-- News Index -->
    <url>
        <loc>{{ route('news.index') }}</loc>
        <changefreq>hourly</changefreq>
        <priority>0.8</priority>
    </url>

    <!-- Jobs Index -->
    <url>
        <loc>{{ route('jobs.index') }}</loc>
        <changefreq>hourly</changefreq>
        <priority>0.8</priority>
    </url>

    <!-- Posts -->
    @foreach ($posts as $post)
        <url>
            <loc>{{ route('news.show', $post->slug) }}</loc>
            <lastmod>{{ $post->updated_at->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.7</priority>
        </url>
    @endforeach

    <!-- Jobs -->
    @foreach ($jobs as $job)
        <url>
            <loc>{{ route('jobs.show', $job->slug) }}</loc>
            <lastmod>{{ $job->updated_at->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.6</priority>
        </url>
    @endforeach
</urlset>
