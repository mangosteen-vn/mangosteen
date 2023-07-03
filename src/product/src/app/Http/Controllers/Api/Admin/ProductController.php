<?php

namespace Mangosteen\Product\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mangosteen\Models\Entities\Product;
use Mangosteen\Product\Http\Resources\ProductCollection;
use Mangosteen\Product\Http\Resources\ProductResource;

/**
 *
 */
class ProductController extends Controller
{

    public function index(Request $request): ProductCollection
    {
        $perPage = (int)$request->get('per_page', 20);

        $query = Product::orderBy('id', 'desc');

        $products = $perPage === -1 ? $query->get() : $query->paginate($perPage);

        return new ProductCollection($products);
    }

    public function store(Request $request): ProductResource
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'thumbnail' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'slug' => ['nullable', 'string', 'unique:products'],
            'content' => ['nullable', 'string'],
            'original_price' => ['nullable', 'integer'],
            'current_price' => ['nullable', 'integer'],
            'quantity' => ['nullable', 'integer'],
            'collection_id' => ['nullable', 'integer', 'exists:collections,id']
        ]);

        $product = new Product();
        $product->fill($request->only(['name', 'description', 'content', 'collection_id', 'original_price', 'current_price', 'quantity', 'thumbnail']));
        $product->quantity = $request->get('quantity') ?: 0;
        $product->slug = $request->get('slug') ?? $request->get('slug');

        $url = 'https://antimatter.vn/wp-content/uploads/2022/10/hinh-anh-gai-xinh-de-thuong.jpg';
        $product->addMediaFromUrl($url)->toMediaCollection('gallery');
        $product->save();

        return new ProductResource($product);
    }

    public function destroy($id): JsonResponse
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(['data' => ['success' => true]]);
    }

    public function show($id): ProductResource
    {
        $relations = request()->get('relations', []);

        $productQuery = Product::query();

        if (!empty($relations)) {
            foreach ($relations as $relation) {
                if (method_exists(Product::class, $relation)) {
                    $productQuery->with($relation);
                }
            }
        }
        $product = $productQuery->findOrFail($id);

        return new ProductResource($product);
    }

    public function update(Request $request, $id): ProductResource
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'collection_id' => ['nullable', 'integer', 'exists:collections,id']
        ]);

        $product = Product::findOrFail($id);
        $product->update($request->except('slug'));

        return new ProductResource($product);
    }

}
