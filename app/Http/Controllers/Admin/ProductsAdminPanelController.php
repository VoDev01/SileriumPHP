<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Actions\ManualPaginatorAction;
use App\Http\Requests\API\Products\APIProductsDeleteRequest;
use App\Http\Requests\API\Products\APIProductsSearchRequest;
use App\Http\Requests\API\Products\APIProductsUpdateRequest;
use App\Repositories\ProductRepository;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Services\SearchForms\FormInputData\SearchFormInput;
use App\Services\SearchForms\FormInputData\SearchFormQueryInput;
use App\Services\SearchForms\ProductSearchFormService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Services\SearchForms\SearchFormPaginateResponseService;

class ProductsAdminPanelController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Product::class);
        $products = SearchFormPaginateResponseService::paginate('products', $request->page ?? 1, 15) ?? Product::paginate(15); 
        $inputs = [
            new SearchFormInput('productName', 'Название товара', 'productName', true),
            new SearchFormInput('sellerName', 'Название продавца', 'sellerName', false)
        ];
        $queryInputs = new SearchFormQueryInput('/admin/products/search', 'admin.products.index', null);
        return view('admin.products.index', ['products' => $products, 'inputs' => $inputs, 'queryInputs' => $queryInputs]);
    }
    public function update(Request $request)
    {
        $this->authorize('update', Product::class);
        $products = SearchFormPaginateResponseService::paginate('products', $request->page ?? 1, 15); 
        $inputs = [
            new SearchFormInput('productName', 'Название товара', 'productName', true),
            new SearchFormInput('sellerName', 'Название продавца', 'sellerName', false)
        ];
        $queryInputs = new SearchFormQueryInput('/admin/products/search', 'admin.products.update', null);
        $categories = Category::with('subcategories')->get();
        return view('admin.products.update', ['products' => $products, 'categories' => $categories, 'inputs' => $inputs, 'queryInputs' => $queryInputs]);
    }
    public function postUpdatedProduct(APIProductsUpdateRequest $request)
    {
        try
        {
            $this->authorize('update', Product::class);
            $admin = User::where('id', Auth::id())->get()->first();
            $validated = $request->validated();

            $product = (new ProductRepository)->update($validated);

            if ($product !== null)
            {
                Cache::put("product_{$product->id}", $product, env('CACHE_TTL'));
                return redirect()->route("admin.products.update");
            }
            else
            {
                throw new NotFoundHttpException('Товар не был обновлен');
            }
        }
        catch (HttpException $e)
        {
            abort($e->getStatusCode(), $e->getMessage());
        }
    }
    public function delete(Request $request)
    {
        $this->authorize('delete', Product::class);
        $products = SearchFormPaginateResponseService::paginate('products', $request->page ?? 1, 15); 
        $inputs = [
            new SearchFormInput('productName', 'Название товара', 'productName', true),
            new SearchFormInput('sellerName', 'Название продавца', 'sellerName', false)
        ];
        $queryInputs = new SearchFormQueryInput('/admin/products/search', 'admin.products.delete', null);
        return view('admin.products.delete', ['products' => $products, 'inputs' => $inputs, 'queryInputs' => $queryInputs]);
    }
    public function postDeletedProduct(APIProductsDeleteRequest $request)
    {
        $this->authorize('delete', Product::class);
        $user = User::where('id', Auth::id())->get()->first();

        $validated = $request->validated();

        $product = (new ProductRepository)->delete($validated['id']);

        if ($product)
        {
            return redirect()->route("admin.products.delete");
        }
        else
        {
            throw new NotFoundHttpException('Товар не найден');
        }
    }
    public function categories(Request $request, int $id)
    {
        if ($request->ajax())
        {
            $subcategories = Subcategory::where('category_id', $id)->get();
            return response()->json(['subcategories' => $subcategories]);
        }
    }

    public function reviews(Request $request)
    {
        $products = SearchFormPaginateResponseService::paginate('products', $request->page ?? 1, 15); 
        $reviews = Cache::get('reviews');
        $message = Cache::get('message');
        $inputs = [
            new SearchFormInput('sellerName', 'Название продавца', 'sellerName', true),
            new SearchFormInput('productName', 'Название товара', 'productName', false)
        ];
        $queryInputs = new SearchFormQueryInput('/admin/products/search', 'admin.products.reviews', 'reviews');
        return view('admin.products.reviews', ['products' => $products, 'reviews' => $reviews, 'message' => $message, 'inputs' => $inputs, 'queryInputs' => $queryInputs]);
    }

    public function receiveProductReviews(Request $request)
    {
        $product = Product::with(['reviews', 'reviews.user', 'reviews.product'])->where('ulid', $request->id)->get()->first();

        return redirect()->route('admin.products.reviews')->with('reviews', ManualPaginatorAction::paginate($product->reviews->toArray(), page: $request->page));
    }

    public function searchProducts(APIProductsSearchRequest $request)
    {
        $validated = $request->validated();

        if (!array_key_exists('productName', $validated))
            $validated['productName'] = null;
        if (!array_key_exists('loadWith', $validated))
            $validated['loadWith'] = null;
        if (!array_key_exists('reviewsCount', $validated))
            $validated['reviewsCount'] = false;

        return (new ProductSearchFormService)->search($validated);
    }
}
