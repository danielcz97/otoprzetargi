<?php

require 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;
use GuzzleHttp\Client;

function getCityNameByLatitudeLongitude($latitude, $longitude)
{
    $APIKEY = 'AIzaSyAUkqOT1W28YXPzewCoOI70b-LfunSPldk'; // Replace this with your Google Maps API key
    $client = new Client();
    $googleMapsUrl = "https://maps.googleapis.com/maps/api/geocode/json?latlng={$latitude},{$longitude}&key={$APIKEY}";

    $response = $client->get($googleMapsUrl);
    $data = json_decode($response->getBody(), true);
    
    if ($data['status'] === 'OK') {
        $addressComponents = $data['results'][0]['address_components'];
        foreach ($addressComponents as $component) {
            $types = $component['types'];
            if (in_array('locality', $types) && in_array('political', $types)) {
                return $component['long_name'];
            }
        }
    }
    
    return null;
}

// Configure the database connection
$db = new DB();
$db->addConnection([
    'driver'    => 'mysql',
    'host'      => '127.0.0.1',
    'database'  => 'testing',
    'username'  => 'sail',
    'password'  => 'password',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

$db->setAsGlobal();
$db->bootEloquent();

// Get all records from the table
$nodesTeryts = DB::table('c144_nodes_teryts')
    ->where('node_id', '>', 24041)
    ->get();
foreach ($nodesTeryts as $node) {
    if ($node->latitude && $node->longitude) {
        $city = getCityNameByLatitudeLongitude($node->latitude, $node->longitude);
        if ($city) {
            // Update the city in the table
            DB::table('c144_nodes_teryts')
                ->where('node_id', $node->node_id)
                ->update(['miasto' => $city]);

            echo "Updated node_id {$node->node_id} with city {$city}\n";
        } else {
            echo "City not found for node_id {$node->node_id}\n";
        }
    } else {
        echo "Coordinates not found for node_id {$node->node_id}\n";
    }
}
