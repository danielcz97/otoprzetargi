    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key') }}&libraries=places">
    </script>

    <style>
        .ru-button {
            display: none;
        }

        .nier-button {
            display: none;
        }



        @media(max-width:576px) {
            .search-bar-nav-tabs {
                display: block;

            }

            .search-bar-nav-tabs li {
                text-align: center;
                border-bottom: 1px solid black;
                padding-bottom: 10px;
                background: white;
            }

            .nier-trans {
                display: none;
            }

            .nier-sub {
                display: none;
            }

            .nier-cen-od {
                display: none;
            }

            .nier-cen-do {
                display: none;
            }

            .nier-od {
                display: none;
            }

            .nier-do {
                display: none;
            }

            .ru-sub {
                display: none;
            }

            .ru-od {
                display: none;
            }

            .ru-do {
                display: none;
            }

            .ru-button {
                display: block;
            }

            .nier-button {
                display: block;
            }
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function saveFormData(excludedElementIds) {
                const formElements = document.querySelectorAll('form input, form select');

                formElements.forEach(element => {
                    if (!excludedElementIds.includes(element.id)) { // Check if ID is in the exclusion list
                        element.addEventListener('change', function() {
                            localStorage.setItem(element.name, element.value);
                        });
                    }
                });
            }

            function loadFormData(excludedElementIds) {
                const formElements = document.querySelectorAll('form input, form select');

                formElements.forEach(element => {
                    if (!excludedElementIds.includes(element.id)) { // Check if ID is in the exclusion list
                        const value = localStorage.getItem(element.name);
                        if (value) {
                            element.value = value;
                        }
                    }
                });
            }
            const button = document.querySelector('.nier-button');
            const elementsToToggle = [
                '.nier-trans',
                '.nier-sub',
                '.nier-cen-od',
                '.nier-cen-do',
                '.nier-od',
                '.nier-do'
            ];

            button.addEventListener('click', function() {
                elementsToToggle.forEach(selector => {
                    const element = document.querySelector(selector);
                    if (element.style.display === 'none') {
                        element.style.display = 'flex';
                    } else {
                        element.style.display = 'none';
                    }
                });

                if (button.textContent === 'Filtry Zaawansowane') {
                    button.textContent = 'Ukryj Filtry';
                } else {
                    button.textContent = 'Filtry Zaawansowane';
                }
            });

            const button2 = document.querySelector('.ru-button');
            const elementsToToggle2 = [
                '.ru-sub',
                '.ru-od',
                '.ru-do'
            ];

            button2.addEventListener('click', function() {
                elementsToToggle2.forEach(selector => {
                    const element = document.querySelector(selector);
                    if (element.style.display === 'none') {
                        element.style.display = 'flex';
                    } else {
                        element.style.display = 'none';
                    }
                });

                if (button2.textContent === 'Filtry Zaawansowane') {
                    button2.textContent = 'Ukryj Filtry';
                } else {
                    button2.textContent = 'Filtry Zaawansowane';
                }
            });

            const excludedIds = ['address-input-buy', 'address-input-rent', 'address-input-sell',
                'address-input-wierz'
            ];

            saveFormData(excludedIds);
            loadFormData(excludedIds);
            initAutocomplete();
        });

        function initAutocomplete() {
            const inputIds = ['address-input-buy', 'address-input-rent', 'address-input-sell', 'address-input-wierz'];
            const autocompleteObjects = {};

            inputIds.forEach(id => {
                const input = document.getElementById(id);
                const localStorageKey = `address-${id.split('-')[2]}`; // Create a specific key for each input

                // Check localStorage for a previously saved address
                const storedAddress = localStorage.getItem(localStorageKey);
                if (storedAddress) {
                    input.value = storedAddress; // Pre-populate the input field
                }

                const autocomplete = new google.maps.places.Autocomplete(input, {
                    componentRestrictions: {
                        country: 'pl'
                    }
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
                                    } else if (types.includes('administrative_area_level_3')) {
                                        city = addressComponents[i].long_name;
                                        break; // If a locality is not found, check for a level 3 administrative area
                                    } else if (types.includes('administrative_area_level_2')) {
                                        city = addressComponents[i].long_name;
                                        break; // If a level 3 administrative area is not found, check for a level 2 administrative area
                                    } else if (types.includes('administrative_area_level_1')) {
                                        city = addressComponents[i].long_name;
                                        break; // If a level 2 administrative area is not found, check for a level 1 administrative area
                                    }
                                }

                                document.getElementById(`city-${id.split('-')[2]}`).value = city ||
                                    '';
                            }
                        });
                    }
                    localStorage.setItem(localStorageKey, input.value);

                });
            });
        }
        google.maps.event.addDomListener(window, 'load', initAutocomplete);
    </script>
    <section class="d-flex align-items-center dark-overlay bg-cover"
        style="background-image: url({{ asset('img/hero.jpg') }});">
        <div class="container py-6 py-lg-7 text-white overlay-content">
            <div class="row">
                <div class="col-xl-8">
                    <h1 class="display-3 fw-bold text-shadow d-none d-sm-block">Otoprzetargi</h1>
                    <p class="text-lg text-shadow mb-6 d-none d-sm-block">GC Trader z siedzibą w Warszawie.</p>
                </div>
            </div>
        </div>
    </section>
    <div class="container position-relative mt-n6 z-index-20 " style="margin-top: -56px">

        <ul class="nav nav-tabs search-bar-nav-tabs" role="tablist">
            <li class="nav-item me-2">
                <a class="nav-link {{ Request::is('ogloszenia') || Request::is('/') || Request::is('nieruchomosci') ? 'active' : '' }}"
                    href="#buy" data-bs-toggle="tab" role="tab">Nieruchomości</a>
            </li>
            <li class="nav-item me-2">
                <a class="nav-link {{ Request::is('ruchomosci') ? 'active' : '' }}" href="#rent" data-bs-toggle="tab"
                    role="tab">Ruchomości</a>
            </li>
            <li class="nav-item me-2">
                <a class="nav-link {{ Request::is('komunikaty') ? 'active' : '' }}" href="#sell" data-bs-toggle="tab"
                    role="tab">Komunikaty</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('wierzytelnosci') ? 'active' : '' }}" href="#wierz"
                    data-bs-toggle="tab" role="tab">Wierzytelności</a>
            </li>
        </ul>

        <div class="search-bar search-bar-with-tabs p-3 p-lg-4">
            <div class="tab-content">
                <div class="tab-pane fade {{ Request::is('ogloszenia') || Request::is('/') || Request::is('nieruchomosci') ? 'show active' : '' }}"
                    id="buy" role="tabpanel">
                    <form action="{{ route('search.nieruchomosci') }}" method="GET">
                        <div class="row">

                            <div class="col-md-3 col-lg-3 align-items-center form-group no-divider pb-2">
                                <select name="transaction_type" class="form-control nier-trans">
                                    <option value="">Typ transakcji</option>
                                    <option value="10">Sprzedaż</option>
                                    <option value="11">Kupno</option>
                                    <option value="13">Wynajem</option>
                                    <option value="12">Dzierżawa</option>
                                    <option value="5">Inne</option>
                                </select>
                            </div>
                            <div class="col-md-3 col-lg-3 align-items-center form-group no-divider pb-2 nier-sub">
                                <select name="subject" class="form-control ">
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
                                    value="{{ request()->address }}" placeholder="Wprowadź adres" autocomplete="on">
                            </div>

                            <div class="col-md-2 col-lg-2 d-flex align-items-center form-group no-divider pb-2">
                                <select name="radius" class="form-control">
                                    <option value="25">+25 km</option>
                                    <option value="0">0 km</option>
                                    <option value="15">+15 km</option>
                                    <option value="50">+50 km</option>
                                    <option value="75">+75 km</option>
                                </select>
                            </div>

                            <div class="col-md-6 col-lg-2  align-items-center form-group no-divider pb-2 nier-od">
                                <input type="number" name="powierzchnia_od" class="form-control"
                                    placeholder="Powierzchnia od">
                            </div>
                            <div class="col-md-6 col-lg-2  align-items-center form-group no-divider pb-2 nier-do">
                                <input type="number" name="powierzchnia_do" class="form-control"
                                    placeholder="Powierzchnia do">
                            </div>
                            <div class="col-md-6 col-lg-2  align-items-center form-group no-divider pb-2 nier-cen-od">
                                <input type="number" name="cena_od" class="form-control" placeholder="Cena od">
                            </div>
                            <div class="col-md-6 col-lg-2 align-items-center form-group no-divider pb-2 nier-cen-do">
                                <input type="number" name="cena_do" class="form-control" placeholder="Cena do">
                            </div>

                            <input type="hidden" id="city-buy" name="city">
                            <input type="hidden" id="latitude-buy" name="latitude">
                            <input type="hidden" id="longitude-buy" name="longitude">
                            <button class="btn btn-primary mb-4 nier-button" type="button">Filtry
                                zaawansowane</button>

                            <div class="col-lg-4 d-grid form-group mb-0">
                                <button class="btn btn-primary h-100" type="submit">Szukaj</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade {{ Request::is('ruchomosci') ? 'show active' : '' }}" id="rent"
                    role="tabpanel">
                    <form action="{{ route('search.ruchomosci') }}" method="GET">
                        <div class="row">
                            <div class="col-lg-4 d-flex align-items-center form-group no-divider pb-2">
                                <input id="address-input-rent" class="form-control" name="address" type="text"
                                    value="{{ request()->address }}" placeholder="Wprowadź adres"
                                    autocomplete="off">
                            </div>
                            <div class="col-md-6 col-lg-2 d-flex align-items-center form-group no- pb-2">
                                <select name="radius" class="form-control">
                                    <option value="25">+25 km</option>
                                    <option value="0">0 km</option>
                                    <option value="15">+15 km</option>
                                    <option value="50">+50 km</option>
                                    <option value="75">+75 km</option>
                                </select>
                            </div>
                            <div class="col-md-6 col-lg-6 align-items-center form-group no-divider pb-2 ru-sub">
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
                            <div class="col-md-6 col-lg-4 align-items-center form-group no-divider ru-od">
                                <input type="number" name="cena_od" class="form-control" placeholder="Cena od">
                            </div>
                            <div class="col-md-6 col-lg-4 align-items-center form-group no-divide ru-do">
                                <input type="number" name="cena_do" class="form-control" placeholder="Cena do">
                            </div>
                            <input type="hidden" id="city-rent" name="city">
                            <input type="hidden" id="latitude-rent" name="latitude">
                            <input type="hidden" id="longitude-rent" name="longitude">

                            <button class="btn btn-primary mb-4 ru-button" type="button">Filtry zaawansowane</button>

                            <div class="col-lg-4 d-grid form-group mb-0">
                                <button class="btn btn-primary h-100" type="submit">Szukaj</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade {{ Request::is('komunikaty') ? 'show active' : '' }}" id="sell"
                    role="tabpanel">
                    <form action="{{ route('search.komunikaty') }}" method="GET">
                        <div class="row">
                            <div class="col-lg-6 d-flex align-items-center form-group no-divider">
                                <input id="address-input-sell" class="form-control" name="address" type="text"
                                    value="{{ request()->address }}" placeholder="Wprowadź adres"
                                    autocomplete="off">
                            </div>
                            <div class="col-md-4 col-lg-4 d-flex align-items-center form-group no-divider">
                                <select name="radius" class="form-control">
                                    <option value="25">+25 km</option>
                                    <option value="0">0 km</option>

                                    <option value="15">+15 km</option>
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
                <div class="tab-pane fade {{ Request::is('wierzytelnosci') ? 'show active' : '' }}" id="wierz"
                    role="tabpanel">
                    <form action="{{ route('search.wierzytelnosci') }}" method="GET">
                        <div class="row">
                            <div class="col-lg-6 d-flex align-items-center form-group no-divider">
                                <input id="address-input-wierz" class="form-control" name="address" type="text"
                                    value="{{ request()->address }}" placeholder="Wprowadź adres"
                                    autocomplete="off">
                            </div>
                            <div class="col-md-4 col-lg-4 d-flex align-items-center form-group no-divider">
                                <select name="radius" class="form-control">
                                    <option value="25">+25 km</option>
                                    <option value="0">0 km</option>
                                    <option value="15">+15 km</option>
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
                                    } else if (types.includes('administrative_area_level_3')) {
                                        city = addressComponents[i].long_name;
                                        break; // If a locality is not found, check for a level 3 administrative area
                                    } else if (types.includes('administrative_area_level_2')) {
                                        city = addressComponents[i].long_name;
                                        break; // If a level 3 administrative area is not found, check for a level 2 administrative area
                                    } else if (types.includes('administrative_area_level_1')) {
                                        city = addressComponents[i].long_name;
                                        break; // If a level 2 administrative area is not found, check for a level 1 administrative area
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
