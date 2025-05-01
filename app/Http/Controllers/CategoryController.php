<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{

    public function categoryHome()
    {
        return view('master.category');
    }
    public function CategoryGet()
    {
        $categories = Category::select('id', 'name')->get();

        return response()->json([
            'kategori_item' => $categories
        ]);
    }
}