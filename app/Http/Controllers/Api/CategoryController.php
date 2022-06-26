<?php

namespace App\Http\Controllers\Api;


use App\Models\Category;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');

        if ($search_term)
        {
            $results = ProductCategory::orWhere('title', 'LIKE', '%'.$search_term.'%')
                ->orWhere('description', 'LIKE', '%'.$search_term.'%')
                ->paginate(10);
        }
        else
        {
            $results = ProductCategory::paginate(10);
        }

        return $results;
    }

    public function show($id)
    {
        return ProductCategory::find($id);
    }

    public function category(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');

        if ($search_term)
        {
            $results = Category::orWhere('title', 'LIKE', '%'.$search_term.'%')
                ->orWhere('description', 'LIKE', '%'.$search_term.'%')
                ->paginate(10);
        }
        else
        {
            $results = Category::paginate(10);
        }

        return $results;
    }

    public function category_show($id)
    {
        return Category::find($id);
    }
}
