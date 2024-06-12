<style>
    #map {
        height: 400px;
        width: 100%;
    }
</style>

<div id="map"></div>
@dump($latitude)
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAkRMkhOIogwDHbOqqjyp5zjw5vjFK-Lhc&libraries=places">
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var defaultLat = {{ $latitude }};
        var defaultLng = {{ $record->teryt->longitude ?? 21.0122 }};
        var map, marker;

        function initializeMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                center: {
                    lat: defaultLat,
                    lng: defaultLng
                },
                zoom: 10
            });

            marker = new google.maps.Marker({
                map: map,
                draggable: true,
                position: {
                    lat: defaultLat,
                    lng: defaultLng
                }
            });

            google.maps.event.addListener(map, 'click', function(event) {
                placeMarker(event.latLng);
            });

            google.maps.event.addListener(marker, 'dragend', function(event) {
                updateLatLngInputs(event.latLng.lat(), event.latLng.lng());
            });

            // Initialize Google Places Autocomplete
            var autocomplete = new google.maps.places.Autocomplete(document.getElementById('autocomplete'));
            autocomplete.addListener('place_changed', function() {
                var place = autocomplete.getPlace();
                if (!place.geometry) {
                    return;
                }

                var location = place.geometry.location;
                map.setCenter(location);
                map.setZoom(15);
                placeMarker(location);
            });
        }

        function placeMarker(location) {
            marker.setPosition(location);
            updateLatLngInputs(location.lat(), location.lng());
        }

        function updateLatLngInputs(lat, lng) {
            document.getElementById('data.teryt.latitude').value = lat.toFixed(5);
            document.getElementById('data.teryt.longitude').value = lng.toFixed(5);
        }

        // Initialize the map on load
        initializeMap();

        // Reinitialize the map on state change (this is the key part)
        document.addEventListener('livewire:load', function() {
            document.addEventListener('livewire:message.processed', function(event) {
                initializeMap();
            });
        });
    });
</script>
