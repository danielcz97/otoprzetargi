<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Property PDF</title>
    <style>
        * {
            font-family: "DejaVu Sans Mono", monospace;
        }

        @font-face {
            font-family: 'DejaVu Sans';
            font-style: normal;
            font-weight: normal;
            src: url('{{ storage_path('fonts/DejaVuSans.ttf') }}') format('truetype');
        }

        h1 {
            font-size: 20px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
        }

        .header-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .property-details {
            margin: 20px 0;
        }

        .footer-section {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
        }

        .publication-info {
            font-size: 9px;
        }

        table {
            width: 100%;
            table-layout: fixed;
        }

        table td {
            border: 1px solid #333;
            word-wrap: break-word;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        table th {
            border: 1px solid #333;
            word-wrap: break-word;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .table-container {
            width: 100%;
            overflow-x: auto;
        }
    </style>
</head>

<body>
    <div class="table-container">
        <table class="header-table">
            <tr>
                <td style="width: 50%;">
                    <img width="125px" src="https://otoprzetargi.pl/img/logo.png" alt="Oto Przetargi logo">
                </td>
                <td style="width: 50%; text-align: right;">
                    <p class="publication-info">Wydanie nr <strong>{{ $formattedDateNumeric }}</strong> z dnia
                        {{ $formattedDateText }} roku, ISSN 2392-215X</p>
                </td>
            </tr>
        </table>
        <h1>{{ $property->title }}</h1>
        <h4 style="color:blue;"> <u>{{ $fullLocation }}</u></h4>
        <div>Przedmiot ogłoszenia: {{ $transactionDetails['transaction_type'] }}</div>
        <div>Typ transakcji: {{ $transactionDetails['property_type'] }}</div>

        <div class="property-details">
            @if ($property->cena)
                <div>Cena: {{ $property->cena }}</div>
            @endif
            @if ($property->powierzchnia)
                <div>Powierzchnia: {{ $property->powierzchnia }}</div>
            @endif

        </div>
        <div class="details">
            <h3>Szczegóły</h3>
            <p>{!! $property->body !!}</p>
        </div>
        <div class="footer-section">
            <table class="header-table">
                <tr>
                    <td style="width: 50%;">
                        <div>
                            <p>GC Trader z siedzibą w Warszawie.<br>ul. Wasilkowskiego 1a lok. 10</p>
                        </div>
                    </td>
                    <td style="width: 50%; text-align: right;">
                        <div>
                            <p>Copyright © 2012 - 2024 Otoprzetargi.pl<br>wdrożenie: Daniel Czerepak
                                firma.dcode@gmail.com
                            </p>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

</body>

</html>
