<style>
    .menu-links {
        display: flex;
        gap: 10px;
    }

    @media (max-width: 767.98px) {
        .menu-links {
            display: none;
            flex-direction: column;
        }

        .menu-links.show {
            display: flex;
        }
    }
</style>
<header class="header">
    <?php use Carbon\Carbon;
    
    $today = Carbon::today();
    $formattedDateNumeric = $today->format('d/m/Y');
    $formattedDateText = $today->translatedFormat('d F Y') . ' roku';
    $issn = '2392-215X';
    ?>
    <p class="text-center pt-2">
        Wydanie nr <strong>{{ $formattedDateNumeric }}</strong> z dnia {{ $formattedDateText }}, ISSN
        {{ $issn }}
    </p>
    <nav class="navbar navbar-expand-md navbar-white bg-white" aria-label="Fourth navbar example">
        <div class="container">
            <a class="navbar-brand" href="/">
                <img style="width:250px" src="{{ asset('img/logo.png') }}" class="img-responsive"
                    alt="Logotyp otoprzetargi">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample04"
                aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="navbar-collapse" id="navbarsExample04">
                <div class="menu-links">
                    <a class="navbar-brand text-dark" href="/">Home</a>
                    <a class="navbar-brand" href="/news">Informacje z rynku</a>
                    <a class="navbar-brand" href="#">Regulamin</a>
                    <a class="navbar-brand" href="#">Kontakt</a>
                </div>
            </div>
        </div>
    </nav>
</header>
