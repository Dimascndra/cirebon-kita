@extends('layouts.public')

@section('content')
    <!-- SUBHEADER -->
    <div class="subheader py-5 py-lg-10 subheader-transparent bg-white border-bottom mb-10" id="kt_subheader">
        <div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <a href="{{ route('news.index') }}" class="btn btn-light-primary btn-sm font-weight-bold mr-3"><i
                        class="flaticon2-back"></i> Kembali</a>
                <h5 class="text-dark font-weight-bold my-2 mr-5">Detail Berita</h5>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <!-- LEFT COLUMN: ARTICLE -->
            <div class="col-lg-8">
                <div class="card card-custom gutter-b shadow-sm">
                    <div class="card-body" id="article-content">
                        <!-- Loaded via AJAX -->
                        <div class="text-center py-10">
                            <div class="spinner spinner-primary spinner-lg"></div>
                        </div>
                    </div>
                </div>

                <!-- RELATED POSTS -->
                <div class="mb-10">
                    <h4 class="font-weight-bold mb-5">Berita Terkait</h4>
                    <div class="row" id="related-posts">
                        <!-- Loaded via AJAX -->
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: SIDEBAR -->
            <div class="col-lg-4">
                <!-- TRENDING -->
                <div class="card card-custom gutter-b">
                    <div class="card-header border-0 pb-0">
                        <h3 class="card-title font-weight-bolder text-dark">Sedang Populer</h3>
                    </div>
                    <div class="card-body pt-2" id="trending-posts">
                        <!-- Loaded via AJAX -->
                        <div class="spinner spinner-dark"></div>
                    </div>
                </div>

                <!-- ADS PLACEHOLDER -->
                <div class="mb-5">
                    <img src="" id="sidebar-ad" class="w-100 rounded" style="min-height: 250px; background: #eee;">
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="post-slug" value="{{ $slug }}">
@endsection

@section('title', $post->title)
@section('meta_description', Str::limit(strip_tags($post->excerpt ? $post->excerpt : $post->content), 150))
@section('meta_image', $post->image ? asset('storage/' . $post->image) : asset('assets/media/logos/logo-letter-1.png'))
@section('meta_type', 'article')

@section('schema_json_ld')
    <script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "NewsArticle",
  "headline": "{{ addslashes($post->title) }}",
  "image": [
    "{{ $post->image ? asset('storage/' . $post->image) : asset('assets/media/logos/logo-letter-1.png') }}"
   ],
  "datePublished": "{{ $post->published_at ? $post->published_at->toIso8601String() : $post->created_at->toIso8601String() }}",
  "dateModified": "{{ $post->updated_at->toIso8601String() }}",
  "author": [{
      "@type": "Person",
      "name": "Admin",
      "url": "{{ url('/') }}"
    }]
}
</script>
@endsection

@section('scripts')
    <script src="{{ asset('js/ajax.js') }}"></script>
    <script src="{{ asset('js/news-detail.js') }}"></script>
@endsection
