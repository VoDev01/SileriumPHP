<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoriesController extends Controller
{
    public function index()
    {
        try
        {
            $categories = Category::all();
            if (!isset($categories))
            {
                throw new NotFoundHttpException('Категории не найдены');
            }
        }
        catch (HttpException $e)
        {
            abort($e->getStatusCode(), $e->getMessage());
        }
        return view('categories.index', ['categories' => $categories]);
    }
    public function subcategories(Category $category)
    {
        try
        {
            $subcategories = $category->subcategories;
            if (!isset($subcategories))
                throw new NotFoundHttpException('Подкатегории не найдены');
        }
        catch (HttpException $e)
        {
            abort($e->getStatusCode(), $e->getMessage());
        }
        return view('categories.subcategories', ['subcategories' => $subcategories]);
    }
}
