<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('head')
</head>

<body style="padding-top: 72px;">

    @include('header')
    <section class="hero-home dark-overlay mb-5"><img class="bg-image" src="{{ $post->getFirstImage() }}" alt="">
        <div class="container py-7">
            <div class="overlay-content text-center text-white">
                <h1 class="display-3 text-serif fw-bold text-shadow mb-0">{{ $post->title }}</h1>
            </div>
        </div>
    </section>
    <section>
        <div class="container">
            <div class="row">
                <div class="col-xl-8 col-lg-10 mx-auto">

                    <p class="lead mb-5">
                        {!! $post->body !!}
                    </p>
                </div>
            </div>

        </div>
    </section>

    @include('footer')

    @include('scripts')
</body>

</html>
