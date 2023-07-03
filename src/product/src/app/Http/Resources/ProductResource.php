<?php

namespace Mangosteen\Product\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $name
 * @property mixed $id
 * @property mixed $created_at
 * @property mixed $updated_at
 * @property mixed $description
 * @property mixed $content
 * @property mixed $slug
 * @property mixed $collection
 * @property mixed $original_price
 * @property mixed $current_price
 * @property mixed $quantity
 * @property mixed $thumbnail
 * @property mixed $gallery
 * @property mixed $media
 */
class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'thumbnail' => $this->thumbnail,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'content' => $this->content,
            'original_price' => $this->original_price,
            'current_price' => $this->current_price,
            'quantity' => $this->quantity,
            'collection' => $this->collection,
            'gallery' => $this->media->map(function ($media) {
                return [
                    'id' => $media->id,
                    'url' => $media->getUrl(),
                ];
            }),            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
