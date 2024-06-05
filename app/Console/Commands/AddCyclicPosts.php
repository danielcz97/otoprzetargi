<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Property;
use App\Models\MovableProperty;
use App\Models\Claim;
use App\Models\Comunicats;
use Carbon\Carbon;

class AddCyclicPosts extends Command
{
    protected $signature = 'posts:add-cyclic';
    protected $description = 'Add cyclic posts';

    public function handle()
    {
        $today = Carbon::now()->day;

        $this->addCyclicPosts(Property::class, $today);
        $this->addCyclicPosts(MovableProperty::class, $today);
        $this->addCyclicPosts(Claim::class, $today);
        $this->addCyclicPosts(Comunicats::class, $today);

        $this->info('Cyclic posts added successfully.');
    }

    protected function addCyclicPosts($modelClass, $today)
    {
        $cyclicPosts = $modelClass::where('cyclic', true)
            ->where('cyclic_day', $today)
            ->get();

        foreach ($cyclicPosts as $post) {
            $newPost = $post->replicate();
            $newPost->created_at = now();
            $newPost->updated_at = now();
            $newPost->slug = $this->generateNewSlug($modelClass, $post->slug);
            $newPost->save();
        }
    }

    protected function generateNewSlug($modelClass, $slug)
    {
        $latestPost = $modelClass::where('slug', 'LIKE', "$slug-%")
            ->latest('id')
            ->first();

        if (!$latestPost) {
            return "$slug-1";
        }

        $parts = explode('-', $latestPost->slug);
        $number = intval(end($parts));

        return "$slug-" . ($number + 1);
    }
}
