<?php

namespace App\Http\Controllers\Seller;

use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\Products\APIProductsCreateRequest;
use App\Http\Requests\API\Products\APIProductsSearchRequest;
use App\Http\Requests\API\Products\APIProductsUpdateRequest;
use Illuminate\Support\Facades\Http;
use App\Services\ManualPaginatorService;
use App\Services\SearchFormKeyAuthService;
use App\Services\UpdateSessionValueJson;
use App\View\Components\ComponentsInputs\SearchForm\SearchFormInput;
use App\View\Components\ComponentsInputs\SearchForm\SearchFormQueryInput;
use App\View\Components\ComponentsMethods\SearchForm\SearchFormProductsSearchMethod;
use Illuminate\Support\Str;

class SellerProductsController extends Controller
{
    public function index()
    {
        return view('seller.products.index');
    }
    public function list(Request $request)
    {
        $this->authorize('viewAny', Product::class);
        $products = SearchFormKeyAuthService::AuthenticateKey($request, 'products', 'searchKey');
        if ($products == null)
            $products = Product::paginate(15);
        $inputs = [
            new SearchFormInput('productName', 'Название товара', 'productName', true),
        ];
        $queryInputs = new SearchFormQueryInput('/seller/products/search', 'seller.products.list', null, Str::ulid());
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
        $response = Http::post('/api/v1/products/create', ['request' => $request]);
        if ($response->ok())
        {
            return redirect()->route("seller.products.products-list");
        }
    }
    public function update(Request $request)
    {
        $this->authorize('update', Product::class);
        $products = SearchFormKeyAuthService::AuthenticateKey($request, 'products', 'searchKey');
        $inputs = [
            new SearchFormInput('productName', 'Название товара', 'product_name', true),
        ];
        $queryInputs = new SearchFormQueryInput('/seller/products/search', 'seller.products.update', null, Str::ulid());
        $categories = Category::with('subcategories')->get();
        return view('seller.products.update', ['products' => $products, 'categories' => $categories, 'inputs' => $inputs, 'queryInputs' => $queryInputs]);
    }
    public function postUpdatedProduct(Request $request)
    {
        $this->authorize('update', Product::class);
        $response = Http::asJson()->put('silerium.com/api/v1/products/update', [
            'id' => $request->id,
            'name' => $request->name,
            'description' => $request->description,
            'priceRub' => $request->priceRub,
            'productAmount' => $request->productAmount,
            'available' => $request->available
        ]);
        if ($response->ok())
        {
            UpdateSessionValueJson::update($request, 'products', $response->json(['updated_product']), 'ulid');
            return redirect()->route("seller.products.update");
        }
    }
    public function delete(Request $request)
    {
        $this->authorize('delete', Product::class);
        $products = SearchFormKeyAuthService::AuthenticateKey($request, 'products', 'searchKey');
        $inputs = [
            new SearchFormInput('productName', 'Название товара', 'productName', true),
        ];
        $queryInputs = new SearchFormQueryInput('/seller/products/search', 'seller.products.delete', null, Str::ulid());
        return view('seller.products.delete', ['products' => $products, 'inputs' => $inputs, 'queryInputs' => $queryInputs]);
    }
    public function postDeletedProduct(Request $request)
    {
        $this->authorize('delete', Product::class);
        $response = Http::delete('silerium.com/api/v1/products/delete', ['id' => $request->id]);
        if ($response->ok())
        {
            UpdateSessionValueJson::delete($request, 'products', $response->json(['updated_product']), 'ulid');
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
        $products = SearchFormKeyAuthService::AuthenticateKey($request, 'products', 'searchKey');
        $reviews = $request->session()->get('reviews');
        $inputs = [
            new SearchFormInput('productName', 'Название товара', 'productName', true)
        ];
        $queryInputs = new SearchFormQueryInput('/seller/products/search', 'seller.products.reviews', 'reviews', Str::ulid());
        return view('seller.products.reviews', ['products' => $products, 'reviews' => $reviews, 'inputs' => $inputs, 'queryInputs' => $queryInputs]);
    }

    public function receiveProductReviews(Request $request)
    {
        $product = Product::with(['reviews', 'reviews.user', 'reviews.product'])->where('ulid', $request->productId)->get()->first();
        $reviews = null;
        if (isset($request->productId))
            if ($product->ulid == $request->productId)
                $reviews = $product->reviews;
        return redirect()->route('seller.products.reviews', ['searchKey' => $request->session()->get('searchKey')])->with('reviews', ManualPaginatorService::paginate($reviews->toArray()));
    }

    public function searchProducts(APIProductsSearchRequest $request)
    {
        $validated = $request->validated();

        if (!array_key_exists('sellerName', $validated))
            $validated['sellerName'] = null;
        if (!array_key_exists('loadWith', $validated))
            $validated['loadWith'] = null;

        return SearchFormProductsSearchMethod::searchProducts($validated);
    }
}
