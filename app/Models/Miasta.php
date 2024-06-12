<?php

namespace App\Models;

use Illuminate\Support\Facades\File;

class Miasta
{
    protected $miasta = [];

    public function __construct()
    {
        $this->miasta = json_decode(File::get(storage_path('app/public/data.json')), true);
    }

    public function getMiasta()
    {
        return collect($this->miasta)->pluck('Name', 'Id')->toArray();
    }
}
