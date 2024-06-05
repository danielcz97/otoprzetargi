<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('posts:add-cyclic', function () {
    $this->call('posts:add-cyclic');
});

Schedule::command('posts:add-cyclic')->daily();