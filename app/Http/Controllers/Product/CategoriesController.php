<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', ['categories' => $categories]);
    }
    public function subcategories(Category $category)
    {
        $subcategories = $category->subcategories;
        return view('categories.subcategories', ['subcategories' => $subcategories]);
    }
}