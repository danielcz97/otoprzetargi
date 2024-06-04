<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;

class Comunicats extends Model implements HasMedia
{
    use InteractsWithMedia;
    protected $table = 'komunikaty';

    protected $casts = [
        'terms' => 'array',
        'portal' => 'array',

    ];
    public $timestamps = false;
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('default')->useDisk('public');
    }
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

    public static function getTypes()
    {
        return self::query()->select('type')->distinct()->pluck('type');
    }

    public function nodeFiles()
    {
        return $this->hasMany(NodeFile::class, 'node_id', 'id');
    }
    public function contact()
    {
        return $this->belongsTo(Contact::class, 'contact_id');
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

    public function objectType()
    {
        return $this->belongsTo(ObjectType::class);
    }

    public function transactionType()
    {
        return $this->belongsTo(TransactionType::class);
    }
}
