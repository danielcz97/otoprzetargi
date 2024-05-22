<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('head')
</head>

<body style="padding-top: 72px;">

    @include('header')

    <section class="py-6">
        <div class="container">
            <h1>Aktualności</h1>

            <div class="row mb-5">
                @foreach ($posts as $post)
                    <!-- blog item-->
                    <div class="col-lg-4 col-sm-6 mb-4 hover-animate">
                        <div class="card shadow border-0 h-100">
                            <a href="{{ route('news.view', $post->slug) }}"><img class="img-fluid card-img-top"
                                    src="{{ $post->getFirstImage() }}" alt="{{ $post->title }}" /></a>
                            <div class="card-body">

                                <h5 class="my-2"><a class="text-dark"
                                        href="{{ route('news.view', $post->slug) }}">{{ $post->title }}</a></h5>
                                <p class="text-gray-500 text-sm my-3"><i
                                        class="far fa-clock me-2"></i>{{ \Carbon\Carbon::parse($post->created)->format('d.m.Y') }}
                                </p>
                                <p class="my-2 text-muted text-sm">{{ Str::limit($post->excerpt, 100) }}</p>
                                <a class="btn btn-link ps-0" href="{{ route('news.view', $post->slug) }}">Czytaj<i
                                        class="fa fa-long-arrow-alt-right ms-2"></i></a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- Pagination -->
            <nav aria-label="Blog pagination">
                <ul class="pagination justify-content-between mb-5">
                    {{ $posts->links() }}
                </ul>
            </nav>
        </div>
    </section>
    @include('footer')

    @include('scripts')
</body>

</html>
