<?php

namespace Mangosteen\Category\Http\Controlles\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mangosteen\Category\Resources\CategoryCollection;
use Mangosteen\Category\Resources\CategoryResource;
use Mangosteen\Models\Entities\Category;

class CategoryController extends Controller
{
    public function index(Request $request): CategoryCollection
    {
        $perPage = (int)$request->get('per_page', 20);

        $query = Category::latest('id');

        if ($request->filled('type')){
            $query = $query->where('type', $request->get('type'));
        }

        $categories = $perPage === -1 ? $query->get() : $query->paginate($perPage);

        return new CategoryCollection($categories);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
            'type' => ['required', 'string', 'max:255',],
            'thumbnail' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'parent_id' => ['nullable', 'integer', 'exists:categories,id']
        ]);

        $category = Category::create($validatedData);

        return new CategoryResource($category);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'thumbnail' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'parent_id' => ['nullable', 'integer', 'exists:categories,id']
        ]);

        $category = Category::findOrFail($id);
        $category->update($validatedData);

        return new CategoryResource($category);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json(['data' => ['success' => true]]);
    }


}
