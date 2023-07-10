<?php

namespace Mangosteen\Tag\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Mangosteen\Models\Entities\Tag;
use Mangosteen\Tag\Http\Resources\TagCollection;
use Mangosteen\Tag\Http\Resources\TagResource;
use Illuminate\Http\Request;
use Throwable;

class TagController extends Controller
{
    public function index(Request $request): TagCollection
    {
        $perPage = (int)$request->get('per_page', 20);

        $query = Tag::orderBy('id', 'desc');

        $tag = $perPage === -1 ? $query->get() : $query->paginate($perPage);

        return new TagCollection($tag);
    }

    public function store(Request $request): TagResource
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:tags,name'],
            'type' => ['required', 'string', 'max:255',],
        ]);

        $tag = new Tag();
        $tag->fill($request->only(['name', 'type']));
        $tag->save();

        return new TagResource($tag);
    }

    public function show($id)
    {
        $tag = Tag::findOrFail($id);
        return new TagResource($tag);
    }

    public function update(Request $request, $id): TagResource
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:tags,name' ],
            'type' => ['required', 'string', 'max:255',],
        ]);

        $tag = Tag::findOrFail($id);
        $tag->update($request->only(['name', 'type']));

        return new TagResource($tag);
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $tag = Tag::findOrFail($id);
        $tag->delete();
        return response()->json(['data' => ['success' => true]]);
    }


}
