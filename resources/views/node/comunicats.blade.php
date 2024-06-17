<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('head')
    <meta property="og:title" content="{{ $property->title }}">
    <meta property="og:description" content="{{ Str::limit(strip_tags($property->body), 150) }}">
    <meta property="og:image" content="">
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
    @if (auth()->check())
        <a style="position: fixed; top: 30px;left: 30px;z-index: 99999; background: red;color: white;"
            href="/admin/notices/{{ $property->id }}/edit">
            Edytuj ogłoszenie
        </a>
    @endif
    @include('header')

    <section class="pt-4 pb-2 d-flex align-items-end bg-gray-700">
        <div class="container overlay-content">
            <div class="row">
                <div class="col-md-9 col-12 flex align-items-center">
                    <div
                        class="d-flex justify-content-between align-items-start flex-column flex-lg-row align-items-lg-end">
                        <div class="text-white mb-4 mb-lg-0">
                            <div class="badge badge-pill badge-transparent px-3 py-2 mb-4">Komunikaty</div>

                            <h1 class="text-shadow verified">{{ $property->title }}</h1>
                            <p> Wydanie nr <strong>{{ $formattedDateNumeric }}</strong> z dnia {{ $formattedDateText }}
                                roku,
                                ISSN 2392-215X </p>
                            @if ($property->teryt->miasto)
                                <div><strong>Miejscowość ogłoszenia:</strong>
                                    {{ $property->teryt->miasto }}, {{ $property->teryt->ulica }}
                                    @if ($property->teryt->powiat)
                                        <br>Powiat: {{ $property->teryt->powiat }},
                                    @endif
                                    @if ($property->teryt->gmina)
                                        <br>Gmina: {{ str_replace('_', ' ', ucfirst($property->teryt->gmina)) }},
                                    @endif
                                    @if ($property->teryt->wojewodztwo)
                                        <br> Województwo: {{ $property->teryt->wojewodztwo }}
                                    @endif
                                </div>
                            @else
                                <div><strong>Miejscowość ogłoszenia:</strong>
                                    {{ $property->getFullLocationFront() }}</div>
                            @endif
                            @if ($property->cena)
                                <div><strong>Cena:</strong>
                                    <div>Cena: {{ number_format($property->cena, 2, ',', '.') }} zł</div>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>

                <div class="col-md-3 col-12">
                    <div class="pb-2">
                        <a href="{{ route('comunicats.printPage', ['slug' => $property->slug]) }}" target="_blank">
                            <i style="font-size:25px; color:red" class="fas fa-print">Drukuj</i>
                        </a>
                    </div>
                    <img style="max-width:150px" src="{{ $mainMediaUrl }}">
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
                                object-fit: cover;
                                object-position: center;
                            }
                        </style>
                        <div class="row gallery ms-n1 me-n1">
                            @if ($galleryMedia->isNotEmpty())
                                <div class="col-lg-12 mb-3 text-center">
                                    <a href="{{ $galleryMedia->first()->getUrl() }}" data-fancybox="gallery">
                                        <img id="main-gallery-image" class="img-fluid main-gallery-image"
                                            src="{{ $galleryMedia->first()->getUrl() }}" alt="Property Image" />
                                    </a>
                                </div>
                                <div class="row thumbnail-wrapper">
                                    @foreach ($galleryMedia->reverse() as $media)
                                        <div class="col-lg-2 col-6 px-1 mb-2">
                                            <a href="{{ $media->getUrl() }}" data-fancybox="gallery">
                                                <img class="img-fluid gallery-thumbnail" src="{{ $media->getUrl() }}"
                                                    alt="Property Image" data-full="{{ $media->getUrl() }}" />
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <script>
                            document.addEventListener("DOMContentLoaded", function() {
                                // Log to console to confirm script execution
                                console.log("Fancybox binding script is executing");

                                // Enable Fancybox for any element with the `data-fancybox` attribute
                                Fancybox.bind("[data-fancybox]", {
                                    // Custom options
                                });

                                // Set custom options for elements with the `data-fancybox='gallery'` attribute
                                Fancybox.bind("[data-fancybox='gallery']", {
                                    loop: true,
                                    thumbs: {
                                        autoStart: true,
                                    },
                                    buttons: ["zoom", "slideShow", "thumbs", "close", "arrowLeft", "arrowRight"],
                                });

                                // Log to console to confirm Fancybox initialization
                                console.log("Fancybox initialized for gallery");
                            });
                        </script>

                    </div>
                    <div class="text-block">
                        <h3 class="mb-3">Szczegóły</h3>
                        <p class="text-sm text-muted">{!! $property->body !!}</p>
                    </div>
                    <div class="text-block">
                        <!-- Listing Location-->
                        <h3 class="mb-4">Lokalizacja</h3>
                        <div class="map-wrapper-300 mb-3">
                            <div style="height:300px" id="map"></div>

                            <script src="{{ $maps }}"></script>

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
                                                    style="background-image: url('{{ $property->mainMediaUrl }}'); min-height: 200px;background-attachment: fixed;
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
                                                        {{ Str::limit($property->title, 50) }}
                                                    </h2>
                                                    <p class="text-sm text-muted text-uppercase mb-1">Powierzchnia:
                                                        {{ $property->powierzchnia }}</p>
                                                    <p class="text-sm text-muted text-uppercase mb-1">Cena:
                                                        {{ number_format($property->cena, 2, ',', '.') }}
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
                        <!-- Contact-->
                        @if ($property->contact)

                            <div class="card border-0 shadow mb-5">
                                <div class="card-header bg-gray-100 py-4 border-0">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <p class="subtitle text-sm text-primary">Dane zlecającego</p>
                                            <h4 class="mb-0">Kontakt</h4>
                                        </div>
                                        <svg
                                            class="svg-icon svg-icon svg-icon-light w-3rem h-3rem ms-3 text-muted flex-shrink-0">
                                            <use xlink:href="#fountain-pen-1"> </use>
                                        </svg>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled mb-4">
                                        @if ($property->contact->nr_tel)
                                            <li class="mb-2">
                                                <a class="text-gray-00 text-sm text-decoration-none"
                                                    href="tel:{{ $property->contact->nr_tel }}">
                                                    <i class="fa fa-phone me-3"></i>
                                                    <span class="text-muted">{{ $property->contact->nr_tel }}</span>
                                                </a>
                                            </li>
                                        @endif
                                        @if ($property->contact->email)
                                            <li class="mb-2">
                                                <a class="text-sm text-decoration-none"
                                                    href="mailto:{{ $property->contact->email }}">
                                                    <i class="fa fa-envelope me-3"></i>
                                                    <span class="text-muted">{{ $property->contact->email }}</span>
                                                </a>
                                            </li>
                                        @endif
                                        @if ($property->contact->strona_www)
                                            <li class="mb-2">
                                                <a class="text-sm text-decoration-none"
                                                    href="{{ $property->contact->strona_www }}" target="_blank">
                                                    <i class="fa fa-globe me-3"></i>
                                                    <span
                                                        class="text-muted">{{ $property->contact->strona_www }}</span>
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        @endif
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
                                                    style="background-image: url('{{ $property->mainMediaUrl }}'); min-height: 200px;background-attachment: fixed;
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
                                                        {{ Str::limit($property->title, 50) }}
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
            <p class="subtitle text-sm text-primary mb-4">Proponowane dla Ciebie</p>
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
                                        style="background-image: url('{{ $property->mainMediaUrl }}'); min-height: 200px;background-attachment: fixed;
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
                                        <h2 class="text-sm text-muted mb-3">{{ Str::limit($property->title, 50) }}
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
