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
    <div class="container position-relative mt-n6 z-index-20 " style="margin-top: -56px">
        <ul class="nav nav-tabs search-bar-nav-tabs" role="tablist">
            <li class="nav-item me-2"><a class="nav-link active" href="#buy" data-bs-toggle="tab"
                    role="tab">Nieruchomości</a></li>
            <li class="nav-item me-2"><a class="nav-link" href="#rent" data-bs-toggle="tab"
                    role="tab">Ruchomości</a></li>
            <li class="nav-item me-2"><a class="nav-link" href="#sell" data-bs-toggle="tab"
                    role="tab">Komunikaty</a>
            </li>
            <li class="nav-item"><a class="nav-link" href="#wierz" data-bs-toggle="tab"
                    role="tab">Wierzytelności</a>
            </li>
        </ul>
        <div class="search-bar search-bar-with-tabs p-3 p-lg-4">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="buy" role="tabpanel">
                    <form action="{{ route('search.nieruchomosci') }}" method="GET">
                        <div class="row">

                            <div class="col-md-3 col-lg-3 d-flex align-items-center form-group no-divider pb-2">
                                <select name="transaction_type" class="form-control">
                                    <option value="">Typ transakcji</option>
                                    <option value="10">Sprzedaż</option>
                                    <option value="11">Kupno</option>
                                    <option value="12">Wynajem</option>
                                    <option value="13">Dzierżawa</option>
                                    <option value="5">Inne</option>
                                </select>
                            </div>
                            <div class="col-md-3 col-lg-3 d-flex align-items-center form-group no-divider pb-2">
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

                            <div class="col-lg-4 d-flex align-items-center form-group no-divider pb-2">
                                <input id="address-input-buy" class="form-control" name="address" type="text"
                                    placeholder="Wprowadź adres" autocomplete="off">
                            </div>

                            <div class="col-md-2 col-lg-2 d-flex align-items-center form-group no-divider pb-2">
                                <select name="radius" class="form-control">
                                    <option value="25">+25 km</option>
                                    <option value="15">+15 km</option>
                                    <option value="0">0 km</option>
                                    <option value="50">+50 km</option>
                                    <option value="75">+75 km</option>
                                </select>
                            </div>

                            <div class="col-md-6 col-lg-2 d-flex align-items-center form-group no-divider pb-2">
                                <input type="number" name="powierzchnia_od" class="form-control"
                                    placeholder="Powierzchnia od">
                            </div>
                            <div class="col-md-6 col-lg-2 d-flex align-items-center form-group no-divider pb-2">
                                <input type="number" name="powierzchnia_do" class="form-control"
                                    placeholder="Powierzchnia do">
                            </div>
                            <div class="col-md-6 col-lg-2 d-flex align-items-center form-group no-divider pb-2">
                                <input type="number" name="cena_od" class="form-control" placeholder="Cena od">
                            </div>
                            <div class="col-md-6 col-lg-2 d-flex align-items-center form-group no-divider pb-2">
                                <input type="number" name="cena_do" class="form-control" placeholder="Cena do">
                            </div>

                            <input type="hidden" id="city-buy" name="city">
                            <input type="hidden" id="latitude-buy" name="latitude">
                            <input type="hidden" id="longitude-buy" name="longitude">
                            <div class="col-lg-4 d-grid form-group mb-0">
                                <button class="btn btn-primary h-100" type="submit">Szukaj</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade" id="rent" role="tabpanel">
                    <form action="{{ route('search.ruchomosci') }}" method="GET">
                        <div class="row">
                            <div class="col-lg-4 d-flex align-items-center form-group no-divider pb-2">
                                <input id="address-input-rent" class="form-control" name="address" type="text"
                                    placeholder="Wprowadź adres" autocomplete="off">
                            </div>
                            <div class="col-md-6 col-lg-2 d-flex align-items-center form-group no- pb-2">
                                <select name="radius" class="form-control">
                                    <option value="25">+25 km</option>
                                    <option value="15">+15 km</option>
                                    <option value="0">0 km</option>
                                    <option value="50">+50 km</option>
                                    <option value="75">+75 km</option>
                                </select>
                            </div>
                            <div class="col-md-6 col-lg-6 d-flex align-items-center form-group no-divider pb-2">
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
                            <div class="col-md-6 col-lg-4 d-flex align-items-center form-group no-divider">
                                <input type="number" name="cena_od" class="form-control" placeholder="Cena od">
                            </div>
                            <div class="col-md-6 col-lg-4 d-flex align-items-center form-group no-divider">
                                <input type="number" name="cena_do" class="form-control" placeholder="Cena do">
                            </div>
                            <input type="hidden" id="city-rent" name="city">
                            <input type="hidden" id="latitude-rent" name="latitude">
                            <input type="hidden" id="longitude-rent" name="longitude">
                            <div class="col-lg-4 d-grid form-group mb-0">
                                <button class="btn btn-primary h-100" type="submit">Szukaj</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade" id="sell" role="tabpanel">
                    <form action="{{ route('search.komunikaty') }}" method="GET">
                        <div class="row">
                            <div class="col-lg-6 d-flex align-items-center form-group no-divider">
                                <input id="address-input-sell" class="form-control" name="address" type="text"
                                    placeholder="Wprowadź adres" autocomplete="off">
                            </div>
                            <div class="col-md-4 col-lg-4 d-flex align-items-center form-group no-divider">
                                <select name="radius" class="form-control">
                                    <option value="25">+25 km</option>
                                    <option value="15">+15 km</option>
                                    <option value="0">0 km</option>
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
                <div class="tab-pane fade" id="wierz" role="tabpanel">
                    <form action="{{ route('search.wierzytelnosci') }}" method="GET">
                        <div class="row">
                            <div class="col-lg-6 d-flex align-items-center form-group no-divider">
                                <input id="address-input-wierz" class="form-control" name="address" type="text"
                                    placeholder="Wprowadź adres" autocomplete="off">
                            </div>
                            <div class="col-md-4 col-lg-4 d-flex align-items-center form-group no-divider">
                                <select name="radius" class="form-control">
                                    <option value="25">+25 km</option>
                                    <option value="15">+15 km</option>
                                    <option value="0">0 km</option>
                                    <option value="50">+50 km</option>
                                    <option value="75">+75 km</option>
                                </select>
                            </div>
                            <input type="hidden" id="city-wierz" name="city">
                            <input type="hidden" id="latitude-wierz" name="latitude">
                            <input type="hidden" id="longitude-wierz" name="longitude">
                            <div class="col-lg-2 d-grid form-group mb-0">
                                <button class="btn btn-primary h-100" type="submit">Szukaj</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAUkqOT1W28YXPzewCoOI70b-LfunSPldk&libraries=places">
    </script>
    <script>
        function initAutocomplete() {
            const inputIds = ['address-input-buy', 'address-input-rent', 'address-input-sell', 'address-input-wierz'];
            const autocompleteObjects = {};

            inputIds.forEach(id => {
                const input = document.getElementById(id);
                const autocomplete = new google.maps.places.Autocomplete(input, {
                    componentRestrictions: {
                        country: 'pl'
                    } // Restrict to Poland
                });
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
                                let city;

                                for (let i = 0; i < addressComponents.length; i++) {
                                    const types = addressComponents[i].types;

                                    if (types.includes('locality')) {
                                        city = addressComponents[i].long_name;
                                        break; // If city is found, no need to check further
                                    }
                                }

                                document.getElementById(`city-${id.split('-')[2]}`).value = city ||
                                    '';
                            }
                        });
                    }
                });
            });
        }
        google.maps.event.addDomListener(window, 'load', initAutocomplete);
    </script>
