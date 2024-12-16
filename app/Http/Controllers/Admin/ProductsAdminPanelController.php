<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Services\ManualPaginatorService;
use App\Services\UpdateSessionValueJson;
use App\Services\SearchFormPaginateResponseService;
use App\Http\Requests\API\Products\APIProductsSearchRequest;
use App\View\Components\ComponentsInputs\SearchForm\SearchFormInput;
use App\View\Components\ComponentsInputs\SearchForm\SearchFormQueryInput;
use App\View\Components\ComponentsMethods\SearchForm\SearchFormProductsSearchMethod;

class ProductsAdminPanelController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Product::class);
        $products = SearchFormPaginateResponseService::paginate($request, 'products', 15) ?? Product::paginate(15);
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
        $products = SearchFormPaginateResponseService::paginate($request, 'products', 15);
        $inputs = [
            new SearchFormInput('productName', 'Название товара', 'productName', true),
            new SearchFormInput('sellerName', 'Название продавца', 'sellerName', false)
        ];
        $queryInputs = new SearchFormQueryInput('/admin/products/search', 'admin.products.update', null);
        $categories = Category::with('subcategories')->get();
        return view('admin.products.update', ['products' => $products, 'categories' => $categories, 'inputs' => $inputs, 'queryInputs' => $queryInputs]);
    }
    public function postUpdatedProduct(Request $request)
    {
        $this->authorize('update', Product::class);
        $user = User::with('apiKey')->where('id', Auth::id())->get()->first();
        $response = Http::asJson()->withBasicAuth($user->email, $user->password)->withHeaders(['API-Key', $user->apiKey])->put('silerium.com/api/v1/products/update', [
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
            return redirect()->route("admin.products.update");
        }
    }
    public function delete(Request $request)
    {
        $this->authorize('delete', Product::class);
        $products = SearchFormPaginateResponseService::paginate($request, 'products', 15);
        $inputs = [
            new SearchFormInput('productName', 'Название товара', 'productName', true),
            new SearchFormInput('sellerName', 'Название продавца', 'sellerName', false)
        ];
        $queryInputs = new SearchFormQueryInput('/admin/products/search', 'admin.products.delete', null);
        return view('admin.products.delete', ['products' => $products, 'inputs' => $inputs, 'queryInputs' => $queryInputs]);
    }
    public function postDeletedProduct(Request $request)
    {
        $this->authorize('delete', Product::class);
        $user = User::with('apiKey')->where('id', Auth::id())->get()->first();
        $response = Http::asJson()->withBasicAuth($user->email, $user->password)->withHeaders(['API-Key', $user->apiKey])->delete('silerium.com/api/v1/products/delete', ['id' => $request->id]);
        if ($response->ok())
        {
            UpdateSessionValueJson::delete($request, 'products', $response->json(['updated_product']), 'ulid');
            return redirect()->route("admin.products.delete");
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
        $products = SearchFormPaginateResponseService::paginate($request, 'products', 15);
        $reviews = $request->session()->get('reviews');
        $message = $request->session()->get('message');
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
        $reviews = null;
        if (isset($request->id))
            if ($product->ulid == $request->id)
                $reviews = $product->reviews;
        return redirect()->route('admin.products.reviews')->with('reviews', ManualPaginatorService::paginate($reviews->toArray()));
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

        return SearchFormProductsSearchMethod::searchProducts($validated);
    }
}
