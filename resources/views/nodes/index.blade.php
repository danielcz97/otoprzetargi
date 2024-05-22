<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('head')
</head>
<style>
    table {
        min-width: 100%;
    }

    table td {
        border: 1px solid #333;
    }
</style>

<body style="padding-top: 72px;">

    @include('header')
    <section class="pt-7 pb-5 d-flex align-items-end dark-overlay "
        style="background-image: url('{{ $property->getFirstImage() }}');">
        <div class="container overlay-content">
            <div class="d-flex justify-content-between align-items-start flex-column flex-lg-row align-items-lg-end">
                <div class="text-white mb-4 mb-lg-0">
                    <div class="badge badge-pill badge-transparent px-3 py-2 mb-4">Nieruchomości</div>
                    @if ($property->cena)
                        <div class="badge badge-pill badge-transparent px-3 py-2 mb-4">Cena: {{ $property->cena }}</div>
                    @endif
                    @if ($property->powierzchnia)
                        <div class="badge badge-pill badge-transparent px-3 py-2 mb-4">Powierzchnia:
                            {{ $property->powierzchnia }}</div>
                    @endif
                    <h1 class="text-shadow verified">{{ $property->title }}</h1>
                    <p> Wydanie nr <strong>10/05/2024</strong> z dnia 10 maja 2024 roku, ISSN 2392-215X </p>
                </div>
            </div>
        </div>
    </section>
    <section class="py-6">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="text-block">
                        <!-- Gallery -->
                        <style>
                            .gallery-image {
                                width: 100%;
                                height: 200px;
                                /* Wysokość zdjęcia */
                                object-fit: cover;
                                /* Dopasowanie obrazu do kontenera z zachowaniem proporcji */
                                object-position: center;
                                /* Ustawienie obrazu w centrum */
                            }
                        </style>
                        <div class="row gallery ms-n1 me-n1">

                            @php
                                $images = $property->getAllImages();
                            @endphp

                            @if ($images)
                                @foreach ($images as $image)
                                    <div class="col-lg-4 col-6 px-1 mb-2">
                                        <a href="{{ $image }}">
                                            <img class="img-fluid gallery-image" src="{{ $image }}"
                                                alt="Property Image">
                                        </a>
                                    </div>
                                @endforeach
                            @endif

                        </div>

                    </div>
                    <!-- About Listing-->
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
                        <!-- Contact-->
                        <div class="card border-0 shadow mb-5">
                            <div class="card-header bg-gray-100 py-4 border-0">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <p class="subtitle text-sm text-primary">Drop Us a Line</p>
                                        <h4 class="mb-0">Contact</h4>
                                    </div>
                                    <svg
                                        class="svg-icon svg-icon svg-icon-light w-3rem h-3rem ms-3 text-muted flex-shrink-0">
                                        <use xlink:href="#fountain-pen-1"> </use>
                                    </svg>
                                </div>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled mb-4">
                                    <li class="mb-2"> <a class="text-gray-00 text-sm text-decoration-none"
                                            href="#"><i class="fa fa-phone me-3"></i><span
                                                class="text-muted">(020) 123 456 789</span></a></li>
                                    <li class="mb-2"> <a class=" text-sm text-decoration-none" href="#"><i
                                                class="fa fa-envelope me-3"></i><span
                                                class="text-muted">info@example.com</span></a></li>
                                    <li class="mb-2"> <a class=" text-sm text-decoration-none" href="#"><i
                                                class="fa fa-globe me-3"></i><span
                                                class="text-muted">www.example.com</span></a></li>
                                    <li class="mb-2"> <a class="text-blue text-sm text-decoration-none"
                                            href="#"><i class="fab fa-facebook me-3"></i><span
                                                class="text-muted">Facebook</span></a></li>
                                    <li class="mb-2"> <a class=" text-sm text-decoration-none" href="#"><i
                                                class="fab fa-twitter me-3"></i><span
                                                class="text-muted">Twitter</span></a></li>
                                    <li class="mb-2"> <a class=" text-sm text-decoration-none" href="#"><i
                                                class="fab fa-instagram me-3"></i><span
                                                class="text-muted">Instagram</span></a></li>
                                    <li class="mb-2"> <a class=" text-sm text-decoration-none" href="#"><i
                                                class="fab fa-google-plus me-3"></i><span
                                                class="text-muted">Google+</span></a></li>
                                </ul>
                                <div class="d-grid text-center"><a class="btn btn-outline-primary" href="#"> <i
                                            class="far fa-paper-plane me-2"></i>Send a Message</a></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="py-6 bg-gray-100">
        <div class="container">
            <h5 class="mb-0">Nieruchomości</h5>
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
                                        style="background-image: url('{{ $property->getFirstImage() }}'); min-height: 200px;">
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
                                        <h2 class="text-sm text-muted mb-3">{{ Str::limit($property->title, 100) }}
                                        </h2>
                                        <p class="text-sm text-muted text-uppercase mb-1">Powierzchnia:
                                            {{ $property->powierzchnia }}</p>
                                        <p class="text-sm text-muted text-uppercase mb-1">Cena: {{ $property->cena }}
                                        </p>
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
