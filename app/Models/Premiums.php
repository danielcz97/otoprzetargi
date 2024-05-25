<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Premiums extends Model
{
    protected $table = 'c144_nodes_premiums';
    public $timestamps = false;

    protected $fillable = [
        'id', 'node_id', 'premium_id', 'datefrom', 'dateto', 'datelock', 
        'platnoscpremium', 'platnosc_total', 'accepted', 'regulamin', 'paid'
    ];

    public function premium()
    {
        return $this->belongsTo(Property::class, 'node_id', 'id');
    }

    public function premiumDetails()
    {
        return $this->belongsTo(Premium::class, 'premium_id', 'id');
    }
}
