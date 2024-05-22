<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NodesTeryts extends Model
{
    protected $table = 'c144_nodes_teryts';
    public $timestamps = false;

    protected $fillable = [
        'node_id', 'wojewodztwo', 'powiat', 'gmina', 'rodz', 'sym', 
        'latitude', 'longitude', 'zoom', 'ulica'
    ];

    public function property()
    {
        return $this->belongsTo(Property::class, 'node_id', 'id');
    }
}
