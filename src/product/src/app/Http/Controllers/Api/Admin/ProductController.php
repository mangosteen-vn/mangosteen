<?php

namespace Mangosteen\Product\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Mangosteen\Models\Entities\Product;

/**
 *
 */
class ProductController extends Controller
{
    public function index(){
        return Product::with('collection')->get();
    }
}
