<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NodeFile extends Model
{
    protected $table = 'c144_node_files';
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'album_id', 'position', 'title', 'description', 
        'small', 'filename', 'head', 'node_id', 'folder', 'secure', 
        'type'
    ];
}
