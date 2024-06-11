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
                <h1 class="display-4 fw-bold text-serif mb-4 text-lg-xxl text-md">Nieruchomości</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 pt-3 d-none d-lg-block">
                <form class="pe-xl-3" action="{{ route('search.nieruchomosci') }}" method="GET">

                    <div class="mb-4">
                        <label class="form-label" for="address-input">Adres</label>
                        <input id="address-input" class="form-control" name="address" type="text"
                            placeholder="Wprowadź adres" autocomplete="off">
                    </div>
                    <div class="mb-4">
                        <label class="form-label" for="transaction_type">Typ transakcji</label>
                        <select name="transaction_type" class="form-control">
                            <option value="">Typ transakcji</option>
                            <option value="10">Sprzedaż</option>
                            <option value="11">Kupno</option>
                            <option value="12">Wynajem</option>
                            <option value="13">Dzierżawa</option>
                            <option value="5">Inne</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label" for="subject">Przedmiot ogłoszenia</label>
                        <select name="subject" class="form-control">
                            <option value="">Przedmiot ogłoszenia</option>
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
                    <div class="mb-4">
                        <label class="form-label" for="radius">Radius</label>
                        <select name="radius" class="form-control">
                            <option value="25">+25 km</option>
                            <option value="15">+15 km</option>
                            <option value="0">0 km</option>
                            <option value="50">+50 km</option>
                            <option value="75">+75 km</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label" for="powierzchnia_od">Powierzchnia od</label>
                        <input type="number" name="powierzchnia_od" class="form-control" placeholder="Powierzchnia od">
                    </div>
                    <div class="mb-4">
                        <label class="form-label" for="powierzchnia_do">Powierzchnia do</label>
                        <input type="number" name="powierzchnia_do" class="form-control" placeholder="Powierzchnia do">
                    </div>
                    <div class="mb-4">
                        <label class="form-label" for="cena_od">Cena od</label>
                        <input type="number" name="cena_od" class="form-control" placeholder="Cena od">
                    </div>
                    <div class="mb-4">
                        <label class="form-label" for="cena_do">Cena do</label>
                        <input type="number" name="cena_do" class="form-control" placeholder="Cena do">
                    </div>
                    <input type="hidden" id="city" name="city">
                    <input type="hidden" id="latitude" name="latitude">
                    <input type="hidden" id="longitude" name="longitude">
                    <div class="mb-4">
                        <button class="btn btn-primary" type="submit"><i class="fas fa-filter me-1"></i>Szukaj</button>
                    </div>
                </form>
            </div>

            <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCKdJpuZy7FePDG5IqSV73IxbgxCiEuh1U&libraries=places">
            </script>
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
            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center flex-column flex-md-row mb-4">
                    <div class="me-3">
                        <p class="mb-3 mb-md-0">Znaleziono <strong>{{ $properties->total() }}</strong> rekordów</p>
                    </div>
                </div>
                <div class="row">
                    @foreach ($properties as $property)
                        <div class="col-sm-6 col-xl-4 mb-5">
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
                    {{ $properties->links('vendor.pagination.bootstrap-5') }}
                </nav>
            </div>
        </div>
    </div>

    @include('footer')

    @include('scripts')

</body>

</html>
