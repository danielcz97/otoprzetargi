<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Casts\Attribute;

class MovableProperty extends Model implements HasMedia
{
    protected $table = 'ruchomosci';
    public $timestamps = false;
    use InteractsWithMedia;

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
        'pierwotna waga przed zmianą na standard',
        'portal',
        'views',
        'cyclic',
        'cyclic_day'
    ];

    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';

    public function getFirstMediaUrl()
    {
        return $this->getFirstMediaUrl();
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

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('default')->useDisk('public');
    }

    public function getAllMediaUrls()
    {
        return $this->getMedia('default')->map(fn($media) => $media->getUrl())->toArray();
    }

    public static function getTypes()
    {
        return self::query()->select('type')->distinct()->pluck('type');
    }
    public function contact()
    {
        return $this->belongsTo(Contact::class, 'contact_id');
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

    public function getFullLocationFront()
    {
        if ($this->teryt) {
            $latitude = $this->teryt->latitude;
            $longitude = $this->teryt->longitude;
        } else {
            $latitude = 52.2297; // domyślna szerokość geograficzna
            $longitude = 21.0122; // domyślna długość geograficzna
        }

        $apiKey = 'AIzaSyAUkqOT1W28YXPzewCoOI70b-LfunSPldk';
        $response = Http::get("https://maps.googleapis.com/maps/api/geocode/json", [
            'latlng' => "$latitude,$longitude",
            'key' => $apiKey
        ]);

        if ($response->successful() && $response['status'] === 'OK') {
            return $response['results'][0]['formatted_address'];
        }

        return 'Lokalizacja nieznana';
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
