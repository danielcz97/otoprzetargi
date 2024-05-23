<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class Property extends Model
{
    protected $table = 'nieruchomosci';
    public $timestamps = false;
    protected $casts = [
        'images' => 'array',
    ];

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'body',
        'excerpt',
        'status',
        'mime_type',
        'comment_status',
        'comment_count',
        'promote',
        'path',
        'terms',
        'sticky',
        'lft',
        'rght',
        'visibility_roles',
        'type',
        'updated',
        'created',
        'cena',
        'powierzchnia',
        'referencje',
        'stats',
        'old_id',
        'hits',
        'weight',
        'weightold',
        'pierwotna waga przed zmianą na standard',
        'portal'
    ];

    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';

    public static function getTypes()
    {
        return self::query()->select('type')->distinct()->pluck('type');
    }

    public function nodeFiles()
    {
        return $this->hasMany(NodeFile::class, 'node_id', 'id');
    }

    public function getFirstImage()
    {
        $files = $this->nodeFiles()->get();

        foreach ($files as $file) {
            if (!empty($file->filename) && !empty($file->folder)) {
                $filePath = 'https://otoprzetargi.pl/files/' . $file->folder . '/' . $file->filename;

                return $filePath;
            }
        }

        return null;
    }

    public function teryt()
    {
        return $this->hasOne(NodesTeryts::class, 'node_id', 'id');
    }

    public function premium()
    {
        return $this->hasOne(Premiums::class, 'node_id', 'id');
    }

    public function getAllImages()
    {
        $files = $this->nodeFiles()->get();
        $imagePaths = [];

        foreach ($files as $file) {
            if (!empty($file->filename) && !empty($file->folder)) {
                $filePath = 'https://otoprzetargi.pl/files/' . $file->folder . '/' . $file->filename;
                $imagePaths[] = $filePath;
            }
        }

        return $imagePaths;
    }

    public function getFullLocation()
    {
        $latitude = $this->teryt->latitude ?? 52.2297;
        $longitude = $this->teryt->longitude ?? 21.0122;
        $apiKey = 'AIzaSyAUkqOT1W28YXPzewCoOI70b-LfunSPldk';
        $response = Http::get("https://maps.googleapis.com/maps/api/geocode/json", [
            'latlng' => "$latitude,$longitude",
            'key' => $apiKey
        ]);

        if ($response->successful() && $response['status'] === 'OK') {
            $addressComponents = $response['results'][0]['address_components'];

            $region = '';
            $city = '';

            foreach ($addressComponents as $component) {
                if (in_array('administrative_area_level_1', $component['types'])) {
                    $region = $component['long_name'];
                }
                if (in_array('locality', $component['types'])) {
                    $city = $component['long_name'];
                }
            }

            return "położonej w $region, $city";
        }

        return 'Lokalizacja nieznana';
    }

    public function getTransactionDetails()
    {
        $terms = json_decode($this->terms, true);
        $values = array_values($terms);

        $transactionType = $values[0] ?? 'Nieznany';
        $propertyType = $values[1] ?? 'Nieznany';

        return [
            'transaction_type' => $transactionType,
            'property_type' => $propertyType,
        ];
    }
}
