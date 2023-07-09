<?php

namespace Mangosteen\Tag\Services;

use Illuminate\Support\Facades\DB;
use Mangosteen\Models\Entities\Tag;
use Throwable;

class TagService
{
    public function createTagsAndGetIds(array $tagNames, string $type): array
    {
        try {
            DB::beginTransaction();
            $tags = collect($tagNames)->map(function ($tagName) use ($type) {
                return Tag::firstOrCreate(['name' => $tagName, 'type' => $type]);
            });

            return $tags->pluck('id')->toArray();
        } catch (Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
