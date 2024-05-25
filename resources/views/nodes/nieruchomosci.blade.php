<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('head')
</head>
@include('header')

@include('hero')

<body style="padding-top: 72px;">


    <div class="container-fluid py-5 px-lg-5">
        <div class="row border-bottom mb-4">
            <div class="col-12">
                <h1 class="display-4 fw-bold text-serif mb-4">Nieruchomości</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 pt-3">
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
                        <label class="form-label" for="subject">Rodzaj transakcji</label>
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
                    <div class="mb-4">
                        <label class="form-label" for="radius">Radius</label>
                        <select name="radius" class="form-control">
                            <option value="0">0 km</option>
                            <option value="25">+25 km</option>
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

            <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAUkqOT1W28YXPzewCoOI70b-LfunSPldk&libraries=places">
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
                        <div class="col-sm-6 col-xl-4 mb-5 hover-animate">
                            <a href="{{ route('properties.index', ['slug' => $property->slug]) }}">

                                <div class="card h-100 border-0 shadow">
                                    <div class="card-img-top overflow-hidden  bg-cover"
                                        style="background-image: url('{{ $property->getFirstImage() }}'); min-height: 200px;">
                                        <div class="card-img-overlay-bottom z-index-20">

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
                                            {{ $property->powierzchnia }} </p>
                                        <p class="text-sm text-muted text-uppercase mb-1">Cena: {{ $property->cena }}
                                        </p>
                                        <p class="text-sm text-muted text-uppercase mb-1">Data:
                                            {{ \Carbon\Carbon::parse($property->created)->format('d.m.Y') }} </p>
                                    </div>
                                </div>
                            </a>

                        </div>
                    @endforeach
                </div>
                <!-- Pagination -->
                <nav aria-label="Page navigation example">
                    <ul class="pagination pagination-template d-flex justify-content-center">
                        {{ $properties->links() }}
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    @include('footer')

    @include('scripts')

</body>

</html>
