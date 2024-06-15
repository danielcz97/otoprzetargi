<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('head')
</head>
@include('header')

@include('hero')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Funkcja do przewijania do określonej sekcji
        function scrollToSection() {
            const targetSection = document.querySelector('.container-fluid.py-5.px-lg-5');
            if (targetSection) {
                targetSection.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        }

        // Sprawdź, czy URL zawiera parametr 'page'
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('page')) {
            scrollToSection();
        }
    });
</script>

<body>


    <div class="container-fluid py-5 px-lg-5">
        <div class="row border-bottom mb-4">
            <div class="col-12">
                <h1 class="display-4 fw-bold text-serif mb-4 text-lg-xxl text-md">Ogłoszenia</h1>
            </div>
        </div>
        <div class="row">


            <script>
                function initAutocomplete() {
                    var input = document.getElementById('address-input');
                    var autocomplete = new google.maps.places.Autocomplete(input);

                    autocomplete.addListener('place_changed', function() {
                        var place = autocomplete.getPlace();
                        if (place.geometry) {
                            var latitude = place.geometry.location.lat();
                            var longitude = place.geometry.location.lng();

                            document.getElementById('latitude').value = latitude;
                            document.getElementById('longitude').value = longitude;

                            // Geocode the latitude and longitude to get the city name
                            var geocoder = new google.maps.Geocoder();
                            geocoder.geocode({
                                'location': {
                                    lat: latitude,
                                    lng: longitude
                                }
                            }, function(results, status) {
                                if (status === 'OK' && results[0]) {
                                    var addressComponents = results[0].address_components;
                                    for (var i = 0; i < addressComponents.length; i++) {
                                        if (addressComponents[i].types.includes('locality')) {
                                            var city = addressComponents[i].long_name;
                                            document.getElementById('city').value = city;
                                            break;
                                        }
                                    }
                                }
                            });
                        }
                    });
                }
                google.maps.event.addDomListener(window, 'load', initAutocomplete);
            </script>

            <div class="row">
                @foreach ($paginatedNodes as $property)
                    <div class="col-sm-6 col-xl-3 mb-5">
                        <a href="{{ route('properties.index', ['slug' => $property->slug]) }}">

                            <div class="card h-100 border-0 shadow">
                                <div class="card-img-top overflow-hidden  bg-cover"
                                    style="background-image: url('{{ $property->thumbnail_url ?? '' }}'); min-height: 200px;background-repeat: no-repeat;background-size: contain;background-position: center;">
                                </div>
                                <div class="card-body">
                                    <h2 class="text-sm text-muted mb-3">{{ Str::limit($property->title, 50) }}
                                    </h2>
                                    @if ($property->powierzchnia)
                                        <p class="text-sm text-muted text-uppercase mb-1">Powierzchnia:
                                            {{ $property->powierzchnia }} </p>
                                    @endif
                                    @if ($property->cena)
                                        <p class="text-sm text-muted text-uppercase mb-1">Cena:
                                            {{ number_format($property->cena, 2, ',', '.') }}
                                        </p>
                                    @endif
                                    <p class="text-sm text-muted text-uppercase mb-1">Data:
                                        {{ \Carbon\Carbon::parse($property->created)->format('d.m.Y') }} </p>
                                    @php
                                        $transactionDetails = $property->getTransactionDetails() ?? [];
                                    @endphp
                                    @if ($transactionDetails)
                                        <div class="pt-2">
                                            <div class="badge badge-transparent badge-pill px-3 py-2">
                                                {{ $transactionDetails['transaction_type'] }}</div>
                                            <div class="badge badge-transparent badge-pill px-3 py-2">
                                                {{ $transactionDetails['property_type'] }}</div>
                                        </div>
                                    @endif
                                    @if (!is_null($property->getFullLocationFrontListing()))
                                        <div class="pt-4">
                                            <p>
                                                {{ $property->getFullLocationFrontListing() }}
                                            <p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </a>

                    </div>
                @endforeach
            </div>
            <!-- Pagination -->
            <nav aria-label="Page navigation example">
                {{ $paginatedNodes->links('vendor.pagination.bootstrap-5') }}
            </nav>
        </div>
    </div>
    </div>

    @include('footer')

    @include('scripts')

</body>

</html>
