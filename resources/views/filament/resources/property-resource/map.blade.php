<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Map</title>
    <style>
        #map {
            height: 400px;
            width: 100%;
        }
    </style>
</head>
<body>

<div id="map"></div>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAUkqOT1W28YXPzewCoOI70b-LfunSPldk&libraries=places"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var defaultLat = {{ $record->teryt->latitude ?? 52.2297 }};
    var defaultLng = {{ $record->teryt->longitude ?? 21.0122 }};
    var map = new google.maps.Map(document.getElementById('map'), {
        center: { lat: defaultLat, lng: defaultLng },
        zoom: 10
    });

    var marker = new google.maps.Marker({
        map: map,
        draggable: true,
        position: { lat: defaultLat, lng: defaultLng }
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
    autocomplete.addListener('place_changed', function () {
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
</body>
</html>
