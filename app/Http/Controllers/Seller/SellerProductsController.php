<?php

namespace App\Http\Controllers\Seller;

use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Actions\ManualPaginatorAction;
use App\Services\SearchForms\FormInputData\SearchFormInput;
use App\Http\Requests\API\Products\APIProductsCreateRequest;
use App\Http\Requests\API\Products\APIProductsSearchRequest;
use App\Services\SearchForms\FormInputData\SearchFormQueryInput;
use App\Services\SearchForms\ProductSearchFormService;
use App\Services\SearchForms\SearchFormPaginateResponseService;

class SellerProductsController extends Controller
{
    public function list(Request $request)
    {
        $this->authorize('viewAny', Product::class);
        $products = SearchFormPaginateResponseService::paginate('products',  $request->page, 15); 
        if ($products == null)
            $products = Product::paginate(15);
        $inputs = [
            new SearchFormInput('productName', 'Название товара', 'productName', true),
        ];

        $queryInputs = new SearchFormQueryInput('/seller/products/search', 'seller.products.list', null);
        return view('seller.products.products-list', ['products' => $products, 'inputs' => $inputs, 'queryInputs' => $queryInputs]);
    }
    public function create()
    {
        $this->authorize('create', Product::class);
        $categories = Category::with('subcategories')->get();
        return view('seller.products.create', ['categories' => $categories]);
    }
    public function postProduct(APIProductsCreateRequest $request)
    {
        $this->authorize('create', Product::class);
        $response = Http::asJson()->withHeaders(['API-Secret' => $request->api_secret])->post('/api/v1/products/create', ['request' => $request]);
        if ($response->ok())
        {
            return redirect()->route("seller.products.products-list");
        }
    }
    public function update(Request $request)
    {
        $this->authorize('update', Product::class);
        $products = SearchFormPaginateResponseService::paginate('products',  $request->page, 15); 
        $inputs = [
            new SearchFormInput('productName', 'Название товара', 'product_name', true),
        ];
        $queryInputs = new SearchFormQueryInput('/seller/products/search', 'seller.products.update', null);
        $categories = Category::with('subcategories')->get();
        return view('seller.products.update', ['products' => $products, 'categories' => $categories, 'inputs' => $inputs, 'queryInputs' => $queryInputs]);
    }
    public function postUpdatedProduct(Request $request)
    {
        $this->authorize('update', Product::class);
        $response = Http::asJson()->withHeaders(['API-Secret' => $request->api_secret])->put(env('APP_URL') . '/api/v1/products/update', [
            'id' => $request->id,
            'name' => $request->name,
            'description' => $request->description,
            'priceRub' => $request->priceRub,
            'productAmount' => $request->productAmount,
            'available' => $request->available
        ])->json('updated_product');
        if ($response->ok())
        {
            Cache::add("product_{$response['ulid']}", $response, env('CACHE_TTL'));
            return redirect()->route("seller.products.update");
        }
    }
    public function delete(Request $request)
    {
        $this->authorize('delete', Product::class);
        $products = SearchFormPaginateResponseService::paginate('products',  $request->page, 15); 
        $inputs = [
            new SearchFormInput('productName', 'Название товара', 'productName', true),
        ];
        $queryInputs = new SearchFormQueryInput('/seller/products/search', 'seller.products.delete', null);
        return view('seller.products.delete', ['products' => $products, 'inputs' => $inputs, 'queryInputs' => $queryInputs]);
    }
    public function postDeletedProduct(Request $request)
    {
        $this->authorize('delete', Product::class);

        $response = Http::asJson()
        ->withHeaders(['API-Secret' => $request->api_secret])
        ->delete(env('APP_URL') . '/api/v1/products/delete', ['id' => $request->ulid]);

        if ($response->ok())
        {
            Cache::delete("product_{$request->ulid}");
            return redirect()->route("seller.products.delete");
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
        $products = SearchFormPaginateResponseService::paginate('products',  $request->page, 15); 
        $reviews = $request->session()->get('reviews');
        $inputs = [
            new SearchFormInput('productName', 'Название товара', 'productName', true)
        ];
        $queryInputs = new SearchFormQueryInput('/seller/products/search', 'seller.products.reviews', 'reviews');
        return view('seller.products.reviews', ['products' => $products, 'reviews' => $reviews, 'inputs' => $inputs, 'queryInputs' => $queryInputs]);
    }

    public function receiveProductReviews(Request $request)
    {
        $product = Product::with(['reviews', 'reviews.user', 'reviews.product'])->where('ulid', $request->productId)->get()->first();
        $reviews = null;
        if (isset($request->productId))
            if ($product->ulid == $request->productId)
                $reviews = $product->reviews;
        return redirect()->route('seller.products.reviews', ['searchKey' => $request->session()->get('searchKey')])->with('reviews', ManualPaginatorAction::paginate($reviews->toArray()));
    }

    public function searchProducts(APIProductsSearchRequest $request)
    {
        $validated = $request->validated();

        if (!array_key_exists('sellerName', $validated))
            $validated['sellerName'] = null;
        if (!array_key_exists('loadWith', $validated))
            $validated['loadWith'] = null;

        return (new ProductSearchFormService)->search($validated);
    }
}
