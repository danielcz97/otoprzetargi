<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObjectType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'model_type'];

    public function claims()
    {
        return $this->hasMany(Claim::class);
    }

    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    public function movableProperties()
    {
        return $this->hasMany(MovableProperty::class);
    }

    public function comunicats()
    {
        return $this->hasMany(Comunicats::class);
    }
}
