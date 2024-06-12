<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('head')
</head>
@include('header')

<body>

    @include('hero')

    @if (!$promotedNodes->isEmpty())
        <section class="py-6">
            <div class="container">
                <div class="row mb-lg-6">
                    <div class="col-md-8">
                        <p class="subtitle text-secondary">Najlepsze oferty</p>
                        <h2 class="mb-md-0">Promowane ogłoszenia</h2>
                    </div>
                    <div class="col-md-4 d-md-flex align-items-center justify-content-end"><a class="text-muted text-lg"
                            href="nieruchomosci">
                            Zobacz wszystkie<i class="fas fa-angle-double-right ms-2"></i></a></div>
                </div>
            </div>
            <div class="container">
                <div class="swiper-container swiper-container-mx-negative items-slider px-lg-5 pt-3">
                    <div class="swiper-wrapper pb-5">
                        @foreach ($promotedNodes as $node)
                            <div class="swiper-slide h-auto px-2">
                                <div class="w-100 h-100 hover-animate" data-marker-id="{{ $node->id }}">
                                    <div class="card h-100 border-0 shadow">
                                        <div class="card-img-top overflow-hidden gradient-overlay"
                                            style="background-image: url('{{ $node->mainMedia }}'); min-height: 200px;    background-attachment: fixed;background-repeat: no-repeat;background-size: contain;background-position: center;">
                                            @php
                                                $transactionDetails = $node->getTransactionDetails() ?? [];
                                            @endphp
                                            @if ($transactionDetails)
                                                <div class="badge badge-transparent badge-pill px-3 py-2">
                                                    {{ $transactionDetails['transaction_type'] }}</div>
                                                <div class="badge badge-transparent badge-pill px-3 py-2">
                                                    {{ $transactionDetails['property_type'] }}</div>
                                            @endif
                                            <a class="tile-link"
                                                href="{{ route('properties.index', ['slug' => $node->slug]) }}"></a>

                                        </div>
                                        <div class="card-body d-flex align-items-start">
                                            <div class="w-100">
                                                <h6 class="card-title"><a class="text-decoration-none text-dark"
                                                        href="{{ route('properties.index', ['slug' => $node->slug]) }}">{{ Str::limit($node->title, 50) }}</a>
                                                </h6>

                                                <p class="text-sm text-muted text-uppercase">{{ $node->type }}</p>
                                                <p class="text-sm text-muted text-uppercase">
                                                    {{ \Carbon\Carbon::parse($node->created)->format('Y-m-d') }}</p>

                                                @if ($node->powierzchnia)
                                                    <p
                                                        class="card-text d-flex justify-content-between text-gray-800 text-sm">
                                                        <span class="me-1"><i
                                                                class="fa fa-ruler-combined text-primary opacity-4 text-xs me-1"></i>
                                                            {{ $node->powierzchnia }}
                                                            m<sup>2</sup></span>
                                                @endif
                                                @if ($node->cena)
                                                    <span><i
                                                            class="fa fa-tag text-primary opacity-4 text-xs me-1"></i>{{ number_format($property->cena, 2, ',', '.') }}zł</span>
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-button-next">
                            </div>
                            <div class="swiper-button-prev"></div>
                        @endforeach
                    </div>

                    <!-- If we need pagination-->
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </section>
    @endif
    <section class="py-6">
        <div class="container">
            <div class="row mb-lg-6">
                <div class="col-md-8">
                    <p class="subtitle text-secondary">Najnowsze przetargi</p>
                    <h2 class="mb-md-0">Ostatnio dodane</h2>
                </div>
                <div class="col-md-4 d-md-flex align-items-center justify-content-end"><a class="text-muted text-lg"
                        href="/nieruchomosci">
                        Zobacz wszystkie<i class="fas fa-angle-double-right ms-2"></i></a></div>
            </div>
        </div>
        <div class="container">
            <div class="swiper-container swiper-container-mx-negative items-slider px-lg-5 pt-3">
                <div class="swiper-wrapper pb-5">
                    @foreach ($latestNodes as $node)
                        <div class="swiper-slide h-auto px-2">
                            <div class="w-100 h-100 hover-animate" data-marker-id="{{ $node->id }}">
                                <div class="card h-100 border-0 shadow">
                                    <div class="card-img-top overflow-hidden gradient-overlay"
                                        style="background-image: url('{{ $node->thumbnail_url }}'); min-height: 200px;    background-attachment: fixed;background-repeat: no-repeat;background-size: contain; background-position: center;">

                                        <a class="tile-link"
                                            href="{{ route('properties.index', ['slug' => $node->slug]) }}"></a>

                                    </div>
                                    <div class="card-body d-flex align-items-start">
                                        <div class="w-100">
                                            <h6 class="card-title"><a class="text-decoration-none text-dark"
                                                    href="{{ route('properties.index', ['slug' => $node->slug]) }}">{{ Str::limit($node->title, 50) }}</a>
                                            </h6>

                                            <p class="text-sm text-muted text-uppercase">{{ $node->type }}</p>
                                            <p class="text-sm text-muted text-uppercase">
                                                {{ \Carbon\Carbon::parse($node->created)->format('Y-m-d') }}</p>
                                            @if ($node->powierzchnia)
                                                <p
                                                    class="card-text d-flex justify-content-between text-gray-800 text-sm">
                                                    <span class="me-1"><i
                                                            class="fa fa-ruler-combined text-primary opacity-4 text-xs me-1"></i>
                                                        {{ $node->powierzchnia }}
                                                        m<sup>2</sup></span>
                                            @endif
                                            @if ($node->cena)
                                                <span><i class="fa fa-tag text-primary opacity-4 text-xs me-1"></i>
                                                    {{ number_format($property->cena, 2, ',', '.') }}zł
                                                </span>
                                                </p>
                                            @endif
                                            @php
                                                $transactionDetails = $node->getTransactionDetails() ?? [];
                                            @endphp
                                            @if ($transactionDetails)
                                                <div class="badge badge-transparent badge-pill px-3 py-2">
                                                    {{ $transactionDetails['transaction_type'] }}</div>
                                                <div class="badge badge-transparent badge-pill px-3 py-2">
                                                    {{ $transactionDetails['property_type'] }}</div>
                                            @endif
                                            @if (!is_null($node->getFullLocationFront()))
                                                <div class="pt-4">
                                                    <p>
                                                        {{ $node->getFullLocationFront() }}
                                                    <p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>

                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>

    <section class="py-6 bg-gray-100">
        <div class="container">
            <div class="row mb-5">
                <div class="col-md-8">
                    <p class="subtitle text-primary">Bądź na bieżąco</p>
                    <h2>Aktualności</h2>
                </div>
                <div class="col-md-4 d-md-flex align-items-center justify-content-end">
                    <a class="text-muted text-lg" href="/news">
                        Zobacz wszystkie<i class="fas fa-angle-double-right ms-2"></i>
                    </a>
                </div>
            </div>
            <div class="row">
                @foreach ($latestPosts as $post)
                    <div class="col-lg-4 col-sm-6 mb-4 hover-animate">
                        <div class="card shadow border-0 h-100">
                            <a href="{{ route('news.view', $post->slug) }}">
                                <img class="w-100" src="{{ $post->thumbnail_url }}" alt="{{ $post->title }}">

                            </a>
                            <div class="card-body">
                                <h5 class="my-2">
                                    <a class="text-dark"
                                        href="{{ route('news.view', $post->slug) }}">{{ $post->title }}</a>
                                </h5>
                                <p class="text-gray-500 text-sm my-3">
                                    <i
                                        class="far fa-clock me-2"></i>{{ \Carbon\Carbon::parse($post->created)->format('d.m.Y') }}
                                </p>
                                <a class="btn btn-link ps-0" href="{{ route('news.view', $post->slug) }}">Czytaj<i
                                        class="fa fa-long-arrow-alt-right ms-2"></i></a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    @include('footer')

    </div>
    @include('scripts')



</body>

</html>
