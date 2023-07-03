<?php

namespace Mangosteen\Collection\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mangosteen\Collection\Http\Resources\CollectionCollection;
use Mangosteen\Collection\Http\Resources\CollectionResource;
use Mangosteen\Models\Entities\Collection;

/**
 *
 */
class CollectionController extends Controller
{
    /**
     * @param Request $request
     * @return CollectionCollection
     */
    public function index(Request $request): CollectionCollection
    {
        $perPage = (int)$request->get('per_page', 20);

        $query = Collection::orderBy('id', 'desc');

        $collections = $perPage === -1 ? $query->get() : $query->paginate($perPage);

        return new CollectionCollection($collections);
    }

    public function store(Request $request): CollectionResource
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:collections'],
            'type' => ['required', 'string', 'unique:collections'],
        ]);

        $collection = new Collection();
        $collection->fill($request->only(['name', 'type']));
        $collection->save();

        return new CollectionResource($collection);
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $collection = Collection::findOrFail($id);
        $collection->delete();
        return response()->json(['data' => ['success' => true]]);
    }

    public function show($id): CollectionResource
    {
        $collection = Collection::findOrFail($id);
        return new CollectionResource($collection);
    }

    public function update(Request $request, $id): CollectionResource
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:collections,name'],
        ]);

        $collection = Collection::findOrFail($id);
        $collection->update($request->only('name'));

        return new CollectionResource($collection);
    }

}
