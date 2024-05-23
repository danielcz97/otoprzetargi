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
