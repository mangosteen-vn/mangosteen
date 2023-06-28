<?php

namespace Mangosteen\Collection\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mangosteen\Collection\Http\Resources\CollectionCollection;
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
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 20);

        $query = Collection::orderBy('id', 'desc');

        $result = $perPage === -1 ? $query->get() : $query->paginate($perPage);

        return new CollectionCollection($result);
    }
}
