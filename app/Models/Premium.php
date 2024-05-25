<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Premium extends Model
{
    protected $table = 'c144_premiums';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'title',
        'caption',
        'body'
    ];

    public function nodesPremiums()
    {
        return $this->hasMany(Premiums::class, 'premium_id', 'id');
    }
}
