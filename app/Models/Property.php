<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $table = 'nieruchomosci';
    public $timestamps = false;
    protected $casts = [
        'images' => 'array',
    ];

    protected $fillable = [
        'user_id', 'title', 'slug', 'body', 'excerpt', 'status', 
        'mime_type', 'comment_status', 'comment_count', 'promote', 
        'path', 'terms', 'sticky', 'lft', 'rght', 'visibility_roles', 
        'type', 'updated', 'created', 'cena', 'powierzchnia', 
        'referencje', 'stats', 'old_id', 'hits', 'weight', 'weightold', 
        'pierwotna waga przed zmianą na standard', 'portal'
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
}
