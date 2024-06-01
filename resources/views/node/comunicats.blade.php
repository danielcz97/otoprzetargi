<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('head')
    <meta property="og:title" content="{{ $property->title }}">
    <meta property="og:description" content="{{ Str::limit(strip_tags($property->body), 150) }}">
    <meta property="og:image" content="{{ $property->getFirstImage() }}">
    <meta property="og:url" content="{{ route('properties.index', ['slug' => $property->slug]) }}">
    <meta property="og:type" content="website">
</head>
<style>
    table {
        min-width: 100%;
    }

    table td {
        border: 1px solid #333;
    }
</style>

<body>

    @include('header')

    <section class="pt-4 pb-2 d-flex align-items-end bg-gray-700">
        <div class="container overlay-content">
            <div class="row">
                <div class="col-9 flex align-items-center">
                    <div
                        class="d-flex justify-content-between align-items-start flex-column flex-lg-row align-items-lg-end">
                        <div class="text-white mb-4 mb-lg-0">
                            <div class="badge badge-pill badge-transparent px-3 py-2 mb-4">Komunikaty</div>

                            <h1 class="text-shadow verified">{{ $property->title }}</h1>
                            <p> Wydanie nr <strong>{{ $formattedDateNumeric }}</strong> z dnia {{ $formattedDateText }}
                                roku,
                                ISSN 2392-215X </p>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <img src="{{ $property->getFirstImage() }}">
                </div>
            </div>
        </div>
    </section>
    <section class="py-6">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="text-block">
                        <h3 class="mb-3">Szczegóły</h3>
                        <p class="text-sm text-muted">{!! $property->body !!}</p>
                    </div>
                    <div class="text-block">
                        <!-- Listing Location-->
                        <h3 class="mb-4">Lokalizacja</h3>
                        <div class="map-wrapper-300 mb-3">
                            <div style="height:300px" id="map"></div>

                            <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAUkqOT1W28YXPzewCoOI70b-LfunSPldk&libraries=places">
                            </script>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    var defaultLat = {{ $property->teryt->latitude ?? 52.2297 }};
                                    var defaultLng = {{ $property->teryt->longitude ?? 21.0122 }};
                                    var map = new google.maps.Map(document.getElementById('map'), {
                                        center: {
                                            lat: defaultLat,
                                            lng: defaultLng
                                        },
                                        zoom: 10
                                    });

                                    var marker = new google.maps.Marker({
                                        map: map,
                                        draggable: true,
                                        position: {
                                            lat: defaultLat,
                                            lng: defaultLng
                                        }
                                    });

                                    google.maps.event.addListener(map, 'click', function(event) {
                                        placeMarker(event.latLng);
                                    });

                                    google.maps.event.addListener(marker, 'dragend', function(event) {
                                        updateLatLngInputs(event.latLng.lat(), event.latLng.lng());
                                    });

                                    function placeMarker(location) {
                                        marker.setPosition(location);
                                        updateLatLngInputs(location.lat(), location.lng());
                                    }

                                    function updateLatLngInputs(lat, lng) {
                                        document.getElementById('data.teryt.latitude').value = lat.toFixed(5);
                                        document.getElementById('data.teryt.longitude').value = lng.toFixed(5);
                                    }

                                    // Initialize Google Places Autocomplete
                                    var autocomplete = new google.maps.places.Autocomplete(document.getElementById('autocomplete'));
                                    autocomplete.addListener('place_changed', function() {
                                        var place = autocomplete.getPlace();
                                        if (!place.geometry) {
                                            return;
                                        }

                                        var location = place.geometry.location;
                                        map.setCenter(location);
                                        map.setZoom(15);
                                        placeMarker(location);
                                    });
                                });
                            </script>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="ps-xl-4">
                        <h5 class="pb-2 text-primary">Ostatnie komunikaty</h5>

                        <div class="swiper-container swiper-container-mx-negative items-slider-custom">
                            <!-- Additional required wrapper-->
                            <div class="swiper-wrapper pb-5">
                                <!-- Slides-->

                                @foreach ($properties as $property)
                                    <div class="swiper-slide h-auto px-2">
                                        <!-- venue item-->
                                        <div class="w-100 h-100" data-marker-id="59c0c8e33b1527bfe2abaf92">
                                            <div class="card h-100 border-0 shadow">
                                                <div class="card-img-top overflow-hidden bg-cover"
                                                    style="background-image: url('{{ $property->getFirstImage() }}'); min-height: 200px;background-attachment: fixed;
    background-repeat: no-repeat;
    background-size: contain;
    background-position: center;">
                                                    <a class="tile-link"
                                                        href="{{ route('properties.index', ['slug' => $property->slug]) }}"></a>
                                                    <div class="card-img-overlay-bottom z-index-20">
                                                        <!-- Card Title -->
                                                    </div>
                                                    <div
                                                        class="card-img-overlay-top d-flex justify-content-between align-items-center">
                                                        @php
                                                            $terms = $property->terms;
                                                            $termsArray = json_decode($terms, true); // Dekoduj JSON do tablicy asocjacyjnej.
                                                            $lastTerm = end($termsArray);
                                                        @endphp
                                                        <div class="badge badge-transparent badge-pill px-3 py-2">
                                                            {{ $lastTerm }}</div>

                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <h2 class="text-sm text-muted mb-3">
                                                        {{ Str::limit($property->title, 100) }}
                                                    </h2>
                                                    <p class="text-sm text-muted text-uppercase mb-1">Powierzchnia:
                                                        {{ $property->powierzchnia }}</p>
                                                    <p class="text-sm text-muted text-uppercase mb-1">Cena:
                                                        {{ $property->cena }}
                                                    </p>
                                                    <p class="text-sm text-muted text-uppercase mb-1">Data:
                                                        {{ \Carbon\Carbon::parse($property->created)->format('d.m.Y') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <h5 class="pb-2 text-primary">Informacje z rynku</h5>

                        <div class="swiper-container swiper-container-mx-negative items-slider-custom">
                            <!-- Additional required wrapper-->
                            <div class="swiper-wrapper pb-5">
                                <!-- Slides-->

                                @foreach ($comunicats as $property)
                                    <div class="swiper-slide h-auto px-2">
                                        <!-- venue item-->
                                        <div class="w-100 h-100" data-marker-id="59c0c8e33b1527bfe2abaf92">
                                            <div class="card h-100 border-0 shadow">
                                                <div class="card-img-top overflow-hidden bg-cover"
                                                    style="background-image: url('{{ $property->getFirstImage() }}'); min-height: 200px;background-attachment: fixed;
    background-repeat: no-repeat;
    background-size: contain;
    background-position: center;">
                                                    <a class="tile-link"
                                                        href="{{ route('properties.index', ['slug' => $property->slug]) }}"></a>
                                                    <div class="card-img-overlay-bottom z-index-20">
                                                        <!-- Card Title -->
                                                    </div>

                                                </div>
                                                <div class="card-body">
                                                    <h2 class="text-sm text-muted mb-3">
                                                        {{ Str::limit($property->title, 100) }}
                                                    </h2>

                                                    <p class="text-sm text-muted text-uppercase mb-1">Data:
                                                        {{ \Carbon\Carbon::parse($property->created)->format('d.m.Y') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="py-6 bg-gray-100">
        <div class="container">
            <h5 class="mb-0">Komunikaty</h5>
            <p class="subtitle text-sm text-primary mb-4"> Które również polubisz </p>
            <!-- Slider main container-->
            <div class="swiper-container swiper-container-mx-negative items-slider">
                <!-- Additional required wrapper-->
                <div class="swiper-wrapper pb-5">
                    <!-- Slides-->
                    @foreach ($properties as $property)
                        <div class="swiper-slide h-auto px-2">
                            <!-- venue item-->
                            <div class="w-100 h-100" data-marker-id="59c0c8e33b1527bfe2abaf92">
                                <div class="card h-100 border-0 shadow">
                                    <div class="card-img-top overflow-hidden bg-cover"
                                        style="background-image: url('{{ $property->getFirstImage() }}'); min-height: 200px;background-attachment: fixed;
    background-repeat: no-repeat;
    background-size: contain;
    background-position: center;">
                                        <a class="tile-link"
                                            href="{{ route('comunicats.index', ['slug' => $property->slug]) }}"></a>
                                        <div class="card-img-overlay-bottom z-index-20">
                                            <!-- Card Title -->
                                        </div>
                                        <div
                                            class="card-img-overlay-top d-flex justify-content-between align-items-center">


                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <h2 class="text-sm text-muted mb-3">{{ Str::limit($property->title, 100) }}
                                        </h2>

                                        <p class="text-sm text-muted text-uppercase mb-1">Data:
                                            {{ \Carbon\Carbon::parse($property->created)->format('d.m.Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    @include('footer')

    @include('scripts')
</body>

</html>