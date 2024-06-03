<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Node extends Model
{
    protected $table = 'c144_nodes';
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'title', 'slug', 'body', 'excerpt', 'status', 
        'mime_type', 'comment_status', 'comment_count', 'promote', 
        'path', 'terms', 'sticky', 'lft', 'rght', 'visibility_roles', 
        'type', 'updated', 'created', 'cena', 'powierzchnia', 
        'referencje', 'stats', 'old_id', 'hits', 'weight', 'weightold', 
        'portal'
    ];

    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';

    public static function getTypes()
    {
        return self::query()->select('type')->distinct()->pluck('type');
    }
}
