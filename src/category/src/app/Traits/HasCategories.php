<?php

namespace Mangosteen\Category\Traits;

use Mangosteen\Models\Entities\Category;

trait HasCategories
{
    public function categories(): \Illuminate\Database\Eloquent\Relations\MorphToMany
    {
        return $this->morphToMany(Category::class, 'categoryable');
    }

    public function attachCategories(array $category_ids): void
    {
        $this->categories()->attach($category_ids);
    }

    public function syncCategories(array $category_ids): void
    {
        $this->categories()->sync($category_ids);
    }
}
