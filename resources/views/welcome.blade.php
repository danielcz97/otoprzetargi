<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('head')
</head>
@include('header')

<body>

    @include('hero')


    <section class="py-6">
        <div class="container">
            <div class="row mb-lg-6">
                <div class="col-md-8">
                    <p class="subtitle text-secondary">Najlepsze oferty</p>
                    <h2 class="mb-md-0">Promowane ogłoszenia</h2>
                </div>
                <div class="col-md-4 d-md-flex align-items-center justify-content-end"><a class="text-muted text-sm"
                        href="nieruchomosci">
                        Zobacz wszystkie<i class="fas fa-angle-double-right ms-2"></i></a></div>
            </div>
        </div>
        <div class="container">
            <!-- Slider main container-->
            <div class="swiper-container swiper-container-mx-negative items-slider px-lg-5 pt-3">
                <!-- Additional required wrapper-->
                <div class="swiper-wrapper pb-5">
                    <!-- Iteruj przez promowane ogłoszenia -->
                    @foreach ($promotedNodes as $node)
                        <div class="swiper-slide h-auto px-2">
                            <div class="w-100 h-100 hover-animate" data-marker-id="{{ $node->id }}">
                                <div class="card h-100 border-0 shadow">
                                    <div class="card-img-top overflow-hidden gradient-overlay"
                                        style="background-image: url('{{ $node->getFirstImage() }}'); min-height: 200px;">
                                        <a class="tile-link"
                                            href="{{ route('properties.index', ['slug' => $node->slug]) }}"></a>

                                    </div>
                                    <div class="card-body d-flex align-items-center">
                                        <div class="w-100">
                                            <h6 class="card-title"><a class="text-decoration-none text-dark"
                                                    href="{{ route('properties.index', ['slug' => $node->slug]) }}">{{ $node->title }}</a>
                                            </h6>

                                            <p class="text-sm text-muted text-uppercase">{{ $node->type }}</p>
                                            <p class="card-text d-flex justify-content-between text-gray-800 text-sm">
                                                <span class="me-1"><i
                                                        class="fa fa-ruler-combined text-primary opacity-4 text-xs me-1"></i>{{ $node->powierzchnia }}
                                                    m<sup>2</sup></span>

                                                <span><i
                                                        class="fa fa-tag text-primary opacity-4 text-xs me-1"></i>{{ number_format($node->cena) }}zł</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- If we need pagination-->
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>

    <section class="py-6">
        <div class="container">
            <div class="row mb-lg-6">
                <div class="col-md-8">
                    <p class="subtitle text-secondary">Najnowsze przetargi</p>
                    <h2 class="mb-md-0">Ostatnio dodane</h2>
                </div>
                <div class="col-md-4 d-md-flex align-items-center justify-content-end"><a class="text-muted text-sm"
                        href="/nieruchomosci">
                        Zobacz wszystkie<i class="fas fa-angle-double-right ms-2"></i></a></div>
            </div>
        </div>
        <div class="container">
            <!-- Slider main container-->
            <div class="swiper-container swiper-container-mx-negative items-slider px-lg-5 pt-3">
                <!-- Additional required wrapper -->
                <div class="swiper-wrapper pb-5">
                    <!-- Iteruj przez promowane ogłoszenia -->
                    @foreach ($promotedNodes as $node)
                        <div class="swiper-slide h-auto px-2">
                            <div class="w-100 h-100 hover-animate" data-marker-id="{{ $node->id }}">
                                <div class="card h-100 border-0 shadow">
                                    <div class="card-img-top overflow-hidden gradient-overlay"
                                        style="background-image: url('{{ $node->getFirstImage() }}'); min-height: 200px;">
                                        <a class="tile-link"
                                            href="{{ route('properties.index', ['slug' => $node->slug]) }}"></a>

                                    </div>
                                    <div class="card-body d-flex align-items-center">
                                        <div class="w-100">
                                            <h6 class="card-title"><a class="text-decoration-none text-dark"
                                                    href="{{ route('properties.index', ['slug' => $node->slug]) }}">{{ $node->title }}</a>
                                            </h6>

                                            <p class="text-sm text-muted text-uppercase">{{ $node->type }}</p>
                                            <p class="card-text d-flex justify-content-between text-gray-800 text-sm">
                                                <span class="me-1"><i
                                                        class="fa fa-ruler-combined text-primary opacity-4 text-xs me-1"></i>{{ $node->powierzchnia }}
                                                    m<sup>2</sup></span>

                                                <span><i
                                                        class="fa fa-tag text-primary opacity-4 text-xs me-1"></i>{{ number_format($node->cena) }}zł</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
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
                    <a class="text-muted text-sm" href="/news">
                        Zobacz wszystkie<i class="fas fa-angle-double-right ms-2"></i>
                    </a>
                </div>
            </div>
            <div class="row">
                @foreach ($latestPosts as $post)
                    <div class="col-lg-4 col-sm-6 mb-4 hover-animate">
                        <div class="card shadow border-0 h-100">
                            <a href="{{ route('news.view', $post->slug) }}">
                                <img class="img-fluid card-img-top" src="{{ $post->getFirstImage() }}"
                                    alt="{{ $post->title }}" />
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

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAUkqOT1W28YXPzewCoOI70b-LfunSPldk&libraries=places">
    </script>
    <script>
        function initAutocomplete() {
            const inputIds = ['address-input-buy', 'address-input-rent', 'address-input-sell'];
            const autocompleteObjects = {};

            inputIds.forEach(id => {
                const input = document.getElementById(id);
                const autocomplete = new google.maps.places.Autocomplete(input);
                autocompleteObjects[id] = autocomplete;

                autocomplete.addListener('place_changed', function() {
                    const place = autocomplete.getPlace();
                    if (place.geometry) {
                        const latitude = place.geometry.location.lat();
                        const longitude = place.geometry.location.lng();
                        document.getElementById(`latitude-${id.split('-')[2]}`).value = latitude;
                        document.getElementById(`longitude-${id.split('-')[2]}`).value = longitude;

                        const geocoder = new google.maps.Geocoder();
                        geocoder.geocode({
                            'location': {
                                lat: latitude,
                                lng: longitude
                            }
                        }, function(results, status) {
                            if (status === 'OK' && results[0]) {
                                const addressComponents = results[0].address_components;
                                for (let i = 0; i < addressComponents.length; i++) {
                                    if (addressComponents[i].types.includes('locality')) {
                                        const city = addressComponents[i].long_name;
                                        document.getElementById(`city-${id.split('-')[2]}`).value =
                                            city;
                                        break;
                                    }
                                }
                            }
                        });
                    }
                });
            });
        }
        google.maps.event.addDomListener(window, 'load', initAutocomplete);
    </script>

</body>

</html>
