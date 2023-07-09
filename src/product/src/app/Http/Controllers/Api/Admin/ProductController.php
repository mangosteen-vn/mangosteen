<?php

namespace Mangosteen\Product\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mangosteen\Models\Entities\Product;
use Mangosteen\Product\Http\Resources\ProductCollection;
use Mangosteen\Product\Http\Resources\ProductResource;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Throwable;

/**
 *
 */
class ProductController extends Controller
{

    /**
     * @param Request $request
     * @return ProductCollection
     */
    public function index(Request $request): ProductCollection
    {
        $perPage = (int)$request->get('per_page', 20);

        $query = Product::latest('created_at');

        $products = $perPage === -1 ? $query->get() : $query->paginate($perPage);

        return new ProductCollection($products);
    }

    /**
     * @param Request $request
     * @return ProductResource
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function store(Request $request): ProductResource
    {
        $this->getArr($request);

        try {
            DB::beginTransaction();

            $productData = $request->only(['name', 'description', 'content', 'collection_id', 'original_price', 'current_price', 'quantity', 'thumbnail']);
            $productData['quantity'] = $request->get('quantity') ?: 0;

            $product = Product::create($productData);

            if ($request->filled('tag_names')) {
                $product->attachTags((array)$request->get('tag_names'), 'product');
            }

            if ($request->filled('gallery')) {
                foreach ($request->gallery as $url) {
                    $path = ltrim(parse_url($url, PHP_URL_PATH), '/');
                    $product->addMedia($path)->toMediaCollection('gallery');
                }
            }

            DB::commit();
            return new ProductResource($product);
        } catch (Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(['data' => ['success' => true]]);
    }

    /**
     * @param $id
     * @return ProductResource
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function show($id): ProductResource
    {
        $product = Product::findOrFail($id);
        return new ProductResource($product);
    }

    /**
     * @param Request $request
     * @param $id
     * @return ProductResource
     */
    public function update(Request $request, $id): ProductResource
    {
        $this->getArr($request);

        try {
            DB::beginTransaction();

            $product = Product::findOrFail($id);

            $product->update($request->only(['name', 'description', 'content', 'collection_id']));
            $product->quantity = $request->get('quantity') ?: 0;
            $product->thumbnail = $request->get('thumbnail', $product->thumbnail);
            $product->original_price = $request->get('original_price', $product->original_price);
            $product->current_price = $request->get('current_price', $product->current_price);

            if ($request->filled('gallery')) {
                $gallery = collect($request->get('gallery'));
                $existingGallery = $product->getMedia('gallery');

                $existingGallery->each(function ($media) use (&$gallery) {
                    if ($gallery->contains($media->getFullUrl())) {
                        $gallery = $gallery->reject(function ($item) use ($media) {
                            return $item == $media->getFullUrl();
                        });
                    } else {
                        $media->delete();
                    }
                });

                foreach ($gallery as $url) {
                    $path = ltrim(parse_url($url, PHP_URL_PATH), '/');
                    $product->addMedia($path)->toMediaCollection('gallery');
                }
            }

            if ($request->filled('tag_names')) {
                $product->syncTags((array)$request->get('tag_names'), 'product');
            }

            $product->save();

            DB::commit();

            return new ProductResource($product);
        } catch (Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }


    /**
     * @param Request $request
     * @return void
     */
    public function getArr(Request $request): void
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'thumbnail' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'original_price' => ['nullable', 'integer'],
            'current_price' => ['nullable', 'integer'],
            'quantity' => ['nullable', 'integer'],
            'collection_id' => ['nullable', 'integer', 'exists:collections,id'],
            'gallery' => ['array'],
            'gallery.*' => ['string'],
            'tag_names' => ['nullable','array'],
            'tag_names.*' => ['nullable','string', ]
        ]);
    }


}
