<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('head')
</head>

<body style="padding-top: 72px;">

    @include('header')

    <section class="d-flex align-items-center dark-overlay bg-cover"
        style="background-image: url(https://otoprzetargi.pl/otoprzetargi_theme/img/bg-banner.jpeg);">
        <div class="container py-6 py-lg-7 text-white overlay-content">
            <div class="row">
                <div class="col-xl-8">
                    <h1 class="display-3 fw-bold text-shadow">Otoprzetargi</h1>
                    <p class="text-lg text-shadow mb-6">GC Trader z siedzibą w Warszawie.</p>
                </div>
            </div>
        </div>
    </section>
    <div class="container position-relative mt-n6 z-index-20" style="margin-top: -56px">
        <ul class="nav nav-tabs search-bar-nav-tabs" role="tablist">
            <li class="nav-item me-2"><a class="nav-link active" href="#buy" data-bs-toggle="tab"
                    role="tab">Nieruchomości</a></li>
            <li class="nav-item me-2"><a class="nav-link" href="#rent" data-bs-toggle="tab"
                    role="tab">Ruchomości</a></li>
            <li class="nav-item"><a class="nav-link" href="#sell" data-bs-toggle="tab" role="tab">Komunikaty</a>
            </li>
        </ul>
        <div class="search-bar search-bar-with-tabs p-3 p-lg-4">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="buy" role="tabpanel">
                    <form action="{{ route('search.nieruchomosci') }}" method="GET">
                        <div class="row">
                            <div class="col-lg-4 d-flex align-items-center form-group no-divider">
                                <input id="address-input-buy" class="form-control" name="address" type="text"
                                    placeholder="Wprowadź adres" autocomplete="off">
                            </div>
                            <div class="col-md-6 col-lg-3 d-flex align-items-center form-group no-divider">
                                <select name="transaction_type" class="form-control">
                                    <option value="">Typ transakcji</option>
                                    <option value="10">Sprzedaż</option>
                                    <option value="11">Kupno</option>
                                    <option value="12">Wynajem</option>
                                    <option value="13">Dzierżawa</option>
                                    <option value="5">Inne</option>
                                </select>
                            </div>
                            <div class="col-md-6 col-lg-3 d-flex align-items-center form-group no-divider">
                                <select name="subject" class="form-control">
                                    <option value="">Rodzaj transakcji</option>
                                    <option value="22">Biuro/Obiekt biurowy</option>
                                    <option value="23">Dom</option>
                                    <option value="25">Dworek/Pałac</option>
                                    <option value="26">Działka</option>
                                    <option value="27">Hotel/Pensjonat</option>
                                    <option value="28">Lokal użytkowy</option>
                                    <option value="29">Magazyn</option>
                                    <option value="30">Mieszkanie</option>
                                    <option value="31">Obiekt użytkowy</option>
                                </select>
                            </div>
                            <div class="col-md-6 col-lg-3 d-flex align-items-center form-group no-divider">
                                <input type="number" name="powierzchnia_od" class="form-control"
                                    placeholder="Powierzchnia od">
                            </div>
                            <div class="col-md-6 col-lg-3 d-flex align-items-center form-group no-divider">
                                <input type="number" name="powierzchnia_do" class="form-control"
                                    placeholder="Powierzchnia do">
                            </div>
                            <div class="col-md-6 col-lg-3 d-flex align-items-center form-group no-divider">
                                <input type="number" name="cena_od" class="form-control" placeholder="Cena od">
                            </div>
                            <div class="col-md-6 col-lg-3 d-flex align-items-center form-group no-divider">
                                <input type="number" name="cena_do" class="form-control" placeholder="Cena do">
                            </div>
                            <div class="col-md-6 col-lg-3 d-flex align-items-center form-group no-divider">
                                <select name="radius" class="form-control">
                                    <option value="0">0 km</option>
                                    <option value="25">+25 km</option>
                                    <option value="50">+50 km</option>
                                    <option value="75">+75 km</option>
                                </select>
                            </div>
                            <input type="hidden" id="city-buy" name="city">
                            <input type="hidden" id="latitude-buy" name="latitude">
                            <input type="hidden" id="longitude-buy" name="longitude">
                            <div class="col-lg-2 d-grid form-group mb-0">
                                <button class="btn btn-primary h-100" type="submit">Szukaj</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade" id="rent" role="tabpanel">
                    <form action="{{ route('search.ruchomosci') }}" method="GET">
                        <div class="row">
                            <div class="col-lg-4 d-flex align-items-center form-group no-divider">
                                <input id="address-input-rent" class="form-control" name="address" type="text"
                                    placeholder="Wprowadź adres" autocomplete="off">
                            </div>
                            <div class="col-md-6 col-lg-3 d-flex align-items-center form-group no-divider">
                                <select name="radius" class="form-control">
                                    <option value="0">0 km</option>
                                    <option value="25">+25 km</option>
                                    <option value="50">+50 km</option>
                                    <option value="75">+75 km</option>
                                </select>
                            </div>
                            <div class="col-md-6 col-lg-3 d-flex align-items-center form-group no-divider">
                                <select name="subject" class="form-control">
                                    <option value="">Rodzaj transakcji</option>
                                    <option value="32">samochody osobowe</option>
                                    <option value="33">samochody ciężarowe</option>
                                    <option value="34">pojazdy specjalistyczne</option>
                                    <option value="35">maszyny, urządzenia</option>
                                    <option value="47">łodzie</option>
                                    <option value="48">maszyny przemysłowe</option>
                                    <option value="49">maszyny rolnicze</option>
                                    <option value="50">przyczepy/naczepy</option>
                                    <option value="51">motocykle/skutery</option>
                                </select>
                            </div>
                            <div class="col-md-6 col-lg-3 d-flex align-items-center form-group no-divider">
                                <input type="number" name="cena_od" class="form-control" placeholder="Cena od">
                            </div>
                            <div class="col-md-6 col-lg-3 d-flex align-items-center form-group no-divider">
                                <input type="number" name="cena_do" class="form-control" placeholder="Cena do">
                            </div>
                            <input type="hidden" id="city-rent" name="city">
                            <input type="hidden" id="latitude-rent" name="latitude">
                            <input type="hidden" id="longitude-rent" name="longitude">
                            <div class="col-lg-2 d-grid form-group mb-0">
                                <button class="btn btn-primary h-100" type="submit">Szukaj</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade" id="sell" role="tabpanel">
                    <form action="{{ route('search.komunikaty') }}" method="GET">
                        <div class="row">
                            <div class="col-lg-4 d-flex align-items-center form-group no-divider">
                                <input id="address-input-sell" class="form-control" name="address" type="text"
                                    placeholder="Wprowadź adres" autocomplete="off">
                            </div>
                            <div class="col-md-6 col-lg-3 d-flex align-items-center form-group no-divider">
                                <select name="radius" class="form-control">
                                    <option value="0">0 km</option>
                                    <option value="25">+25 km</option>
                                    <option value="50">+50 km</option>
                                    <option value="75">+75 km</option>
                                </select>
                            </div>
                            <input type="hidden" id="city-sell" name="city">
                            <input type="hidden" id="latitude-sell" name="latitude">
                            <input type="hidden" id="longitude-sell" name="longitude">
                            <div class="col-lg-2 d-grid form-group mb-0">
                                <button class="btn btn-primary h-100" type="submit">Szukaj</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
        <div class="container-fluid">
            <!-- Slider main container-->
            <div class="swiper-container swiper-container-mx-negative items-slider-full px-lg-5 pt-3">
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
        <div class="container-fluid">
            <!-- Slider main container-->
            <div class="swiper-container swiper-container-mx-negative items-slider-full px-lg-5 pt-3">
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
                                                        class="fa fa-tag text-primary opacity-4 text-xs me-1"></i>${{ number_format($node->cena) }}</span>
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
