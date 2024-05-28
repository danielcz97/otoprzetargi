<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'nazwa',
        'email',
        'nr_tel',
        'strona_www',
    ];

    public function properties()
    {
        return $this->hasMany(Property::class, 'contact_id');
    }

    public function movable()
    {
        return $this->hasMany(MovableProperty::class, 'contact_id');
    }

    public function claims()
    {
        return $this->hasMany(Claim::class, 'contact_id');
    }

    public function comunicats()
    {
        return $this->hasMany(Comunicats::class, 'contact_id');
    }
}
