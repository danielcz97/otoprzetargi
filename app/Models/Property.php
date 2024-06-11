<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Property extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'nieruchomosci';
    public $timestamps = false;
    protected $casts = [
        'terms' => 'array',
        'portal' => 'array',
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
        'portal',
        'views',
        'cyclic',
        'cyclic_day'
    ];

    protected $appends = [
        'location',
    ];

    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';

    public function getLocationAttribute(): array
    {
        return [
            "lat" => (float) ($this->teryt->latitude ?? 52.2297),
            "lng" => (float) ($this->teryt->longitude ?? 21.0122),
        ];
    }

    public function setLocationAttribute(?array $location): void
    {
        if (is_array($location)) {
            \Log::info('setLocationAttribute called', ['location' => $location]);

            if ($this->teryt) {
                $this->teryt->latitude = $location['lat'];
                $this->teryt->longitude = $location['lng'];
                $this->teryt->save();

                \Log::info('teryt updated', ['teryt' => $this->teryt]);
            } else {
                \Log::warning('teryt relation not found for Property', ['property_id' => $this->id]);
            }
        } else {
            \Log::warning('Invalid location data', ['location' => $location]);
        }
    }

    public static function getLatLngAttributes(): array
    {
        return [
            'lat' => 'lat',
            'lng' => 'lng',
        ];
    }

    public static function getComputedLocation(): string
    {
        return 'location';
    }

    // Pozostała część Twojego modelu pozostaje bez zmian
    public function nodeFiles()
    {
        return $this->hasMany(NodeFile::class, 'node_id', 'id');
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }

    public function teryt()
    {
        return $this->hasOne(NodesTeryts::class, 'node_id', 'id');
    }

    public function premium()
    {
        return $this->hasOne(Premiums::class, 'node_id', 'id');
    }

    public function getFullLocation()
    {
        $latitude = $this->teryt->latitude ?? 52.2297;
        $longitude = $this->teryt->longitude ?? 21.0122;
        $apiKey = 'AIzaSyCKdJpuZy7FePDG5IqSV73IxbgxCiEuh1U';
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

        return null;
    }

    public function getFullLocationFront()
    {
        if ($this->teryt) {
            $latitude = $this->teryt->latitude;
            $longitude = $this->teryt->longitude;
        } else {
            $latitude = 52.2297; // domyślna szerokość geograficzna
            $longitude = 21.0122; // domyślna długość geograficzna
        }

        $apiKey = 'AIzaSyCKdJpuZy7FePDG5IqSV73IxbgxCiEuh1U';
        $response = Http::get("https://maps.googleapis.com/maps/api/geocode/json", [
            'latlng' => "$latitude,$longitude",
            'key' => $apiKey
        ]);

        if ($response->successful() && $response['status'] === 'OK') {
            $formattedAddress = $response['results'][0]['formatted_address'];
            // Remove the country name "Poland" from the formatted address
            $addressWithoutCountry = preg_replace('/, Poland$/', '', $formattedAddress);
            return $addressWithoutCountry;
        }

        return null;
    }

    public function getFullLocationFrontListing()
    {
        if ($this->teryt) {
            $latitude = $this->teryt->latitude;
            $longitude = $this->teryt->longitude;
        } else {
            $latitude = 52.2297; // domyślna szerokość geograficzna
            $longitude = 21.0122; // domyślna długość geograficzna
        }

        $apiKey = 'AIzaSyCKdJpuZy7FePDG5IqSV73IxbgxCiEuh1U';
        $response = Http::get("https://maps.googleapis.com/maps/api/geocode/json", [
            'latlng' => "$latitude,$longitude",
            'key' => $apiKey
        ]);

        if ($response->successful() && $response['status'] === 'OK') {
            $results = $response['results'][0]['address_components'];
            $city = null;

            foreach ($results as $component) {
                if (in_array('locality', $component['types'])) {
                    $city = $component['long_name'];
                    break;
                }
            }

            return $city;
        }

        return null;
    }
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('default')->useDisk('public');
        $this->addMediaCollection('herb')->useDisk('public');

    }
    protected function terms(): Attribute
    {
        return Attribute::make(
            get: fn($value) => json_decode($value, true),
            set: fn($value) => json_encode($value),
        );
    }


    public function setTermsAttribute($value)
    {
        if (is_string($value)) {
            $value = json_decode($value, true);
        }

        if (is_array($value)) {
            $terms = [];

            foreach ($value as $id => $name) {
                $transactionType = TransactionType::find($id);
                $objectType = ObjectType::find($id);

                if ($transactionType) {
                    $terms[$transactionType->id] = $transactionType->name;
                }

                if ($objectType) {
                    $terms[$objectType->id] = $objectType->name;
                }
            }

            $this->attributes['terms'] = json_encode($terms);
        } else {
            $this->attributes['terms'] = json_encode([]);
        }
    }
    public function getTransactionDetails()
    {
        if (!$this->terms) {
            return [];
        }
        // $terms = json_decode($this->terms, true);
        $values = array_values($this->terms);

        $transactionType = $values[0] ?? 'Nieznany';
        $propertyType = $values[1] ?? 'Nieznany';

        return [
            'transaction_type' => $transactionType,
            'property_type' => $propertyType,
        ];
    }

    public function objectType()
    {
        return $this->belongsTo(ObjectType::class);
    }

    public function transactionType()
    {
        return $this->belongsTo(TransactionType::class);
    }
}
