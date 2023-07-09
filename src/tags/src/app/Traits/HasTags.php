<?php

namespace Mangosteen\Tag\Traits;

use Mangosteen\Models\Entities\Tag;

trait HasTags
{
    public function tags(): \Illuminate\Database\Eloquent\Relations\MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
    public function attachTags(array $tagNames, string $type): void
    {
        $tags = collect($tagNames)->map(function ($tagName) use ($type) {
            return Tag::firstOrCreate(['name' => $tagName, 'type' => $type]);
        });

        $tagIds = $tags->pluck('id')->toArray();

        $this->tags()->attach($tagIds);
    }
    public function syncTags(array $tagNames, string $type): void
    {
        $tags = collect($tagNames)->map(function ($tagName) use ($type) {
            return Tag::firstOrCreate(['name' => $tagName, 'type' => $type]);
        });

        $tagIds = $tags->pluck('id')->toArray();

        $this->tags()->sync($tagIds);
    }
}
