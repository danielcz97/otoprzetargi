<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('head')
</head>

<body>

    @include('header')
    @include('hero')

    <div class="container-fluid py-5 px-lg-5">
        <div class="row border-bottom mb-4">
            <div class="col-12">
                <h1 class="display-4 fw-bold text-serif mb-4">Ruchomości</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 pt-3">
                <form class="pe-xl-3" action="{{ route('search.ruchomosci') }}" method="GET">
                    <div class="mb-4">
                        <label class="form-label" for="address-input">Adres</label>
                        <input id="address-input" class="form-control" name="address" type="text"
                            placeholder="Wprowadź adres" autocomplete="off">
                    </div>
                    <div class="mb-4">
                        <label class="form-label" for="subject">Przedmiot ogłoszenia</label>
                        <select name="subject" class="form-control">
                            <option value="">Przedmiot ogłoszenia</option>
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
                        <div class="col-sm-6 col-xl-4 mb-5">
                            <a href="{{ route('movable.index', ['slug' => $property->slug]) }}">

                                <div class="card h-100 border-0 shadow">
                                    <div class="card-img-top overflow-hidden  bg-cover"
                                        style="background-image: url(''); min-height: 200px;
    background-repeat: no-repeat;
    background-size: contain;
    background-position: center;">
                                        @php
                                            $transactionDetails = $property->getTransactionDetails() ?? [];
                                        @endphp
                                        @if ($transactionDetails)
                                            <div class="badge badge-transparent badge-pill px-3 py-2">
                                                {{ $transactionDetails['transaction_type'] }}</div>
                                            <div class="badge badge-transparent badge-pill px-3 py-2">
                                                {{ $transactionDetails['property_type'] }}</div>
                                        @endif
                                        <div class="card-img-overlay-bottom z-index-20">

                                        </div>

                                    </div>
                                    <div class="card-body">
                                        <h2 class="text-sm text-muted mb-3">{{ Str::limit($property->title, 100) }}
                                        </h2>

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
                    {{ $properties->links('vendor.pagination.bootstrap-5') }}
                </nav>
            </div>
        </div>
    </div>

    @include('footer')

    @include('scripts')

</body>

</html>
