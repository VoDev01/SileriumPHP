<?php

namespace App\Http\Controllers\Product;

use App\Actions\EncodeImageBinaryToBase64Action;
use App\Actions\ManualPaginatorAction;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Http\Controllers\Controller;
use App\Actions\OrderItemsAction;
use App\Facades\ProductCartServiceFacade as ProductCart;
use App\Http\Requests\API\Products\APIProductsSearchRequest;
use App\Models\Seller;
use App\View\Components\ComponentsInputs\SearchForm\SearchFormCheckboxInput;
use App\View\Components\ComponentsInputs\SearchForm\SearchFormHiddenInput;
use App\View\Components\ComponentsInputs\SearchForm\SearchFormInput;
use App\View\Components\ComponentsInputs\SearchForm\SearchFormQueryInput;
use App\View\Components\ComponentsMethods\SearchForm\SearchFormProductsSearchMethod;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class CatalogController extends Controller
{
    public function products(Request $request, string $subcategory = "all", int $sortOrder = 1, int $available = 1, string $name = "")
    {
        $inputs = [
            new SearchFormInput('name', 'Название товара', 'name', false)
        ];
        $checkboxInputs = [
            new SearchFormCheckboxInput('available', 'В продаже', 'available', false, false, 'available', 1)
        ];
        $hiddenInputs = [
            new SearchFormHiddenInput('sortOrder', 'sortOrder',  $sortOrder),
            new SearchFormHiddenInput('available', 'availableHidden', 0),
            new SearchFormHiddenInput('subcategory', 'subcategory', $subcategory),
        ];
        $queryInputs = new SearchFormQueryInput('/catalog/products/search', '/catalog/products', 'images');

        $page = isset($request->page) ? $request->page : 1;

        $products = Cache::remember("page_{$page}_products", 86400, function () use (
            $subcategory,
            $name,
            $available,
            $sortOrder
        )
        {
            return ProductService::getFilteredProducts(
                [
                    'id',
                    'name',
                    'available',
                    'priceRub',
                    'productAmount'
                ],
                $subcategory,
                $name,
                $available,
                $sortOrder
            );
        });

        if (session('products_currency') == 'dol')
        {
            ProductCart::convertCurrency($products);
        }

        return view('catalog.products', [
            'products' => $products,
            'sortOrder' => $sortOrder,
            'subcategories' => Subcategory::all(),
            'subcategory' => $subcategory,
            'product' => $name,
            'categories' => Category::all(),
            'available' => $available,
            'inputs' => $inputs,
            'checkboxInputs' => $checkboxInputs,
            'hiddenInputs' => $hiddenInputs,
            'queryInputs' => $queryInputs
        ]);
    }
    public function filterProducts(Request $request)
    {
        session(['loadWith' => $request->loadWith]);
        return redirect()->route('allproducts', [
            'sortOrder' => $request->sortOrder,
            'available' => $request->available,
            'subcategory' => $request->subcategory,
            'product' => $request->name
        ])->with(['loadWith' => $request->loadWith]);
    }
    public function product(Request $request, int $productId)
    {
        start_measure('product_cache', 'product cache');

        $page = isset($request->page) ? $request->page : 1;

        $product = Cache::remember("product_$productId", 86400, function () use ($productId)
        {
            return DB::table('products')
                ->selectRaw('products.*,
            GROUP_CONCAT(ps.specification SEPARATOR \', \') AS specs,
            GROUP_CONCAT(ps.name SEPARATOR \', \') AS specs_names,
            GROUP_CONCAT(product_images.imagePath SEPARATOR \', \') as images')
                ->where('products.id', $productId)
                ->joinSub(
                    DB::table('products_specifications')
                        ->select('product_id', 'product_specifications.name', 'product_specifications.specification')
                        ->where('product_id', $productId)
                        ->rightJoin('product_specifications', 'products_specifications.specification_id', '=', 'product_specifications.id'),
                    'ps',
                    'products.id',
                    '=',
                    'ps.product_id',
                    'left'
                )
                ->leftJoin('product_images', 'products.id', '=', 'product_images.product_id')
                ->groupBy(
                    'products.ulid',
                    'products.id',
                    'products.name',
                    'products.description',
                    'products.priceRub',
                    'products.productAmount',
                    'products.available',
                    'products.subcategory_id',
                    'products.seller_id',
                    'products.timesPurchased'
                )
                ->get()->first();
        });

        stop_measure('product_cache');

        $product->images = $product->images != null ? explode(', ', $product->images) : null;
        $product->specs_names = $product->specs_names ? explode(', ', $product->specs_names) : null;
        $product->specs = $product->specs ? explode(', ', $product->specs) : null;

        if (isset($product->specs))
        {
            for ($i = 0; $i < count($product->specs); $i++)
            {
                $tmp[$i] = ['name' => $product->specs_names[$i], 'spec' => $product->specs[$i]];
            }
            $product->specs = $tmp;
        }

        start_measure('reviews_cache', 'Reviews cache');
        $reviews = Cache::remember("product_{$productId}_reviews_$page", 86400, function () use ($product)
        {
            return Http::withoutVerifying()
                ->withHeaders(['Token' => Auth::user()->token])
                ->post(env('APP_URL') . '/api/v1/reviews/search_product_reviews', [
                    'productName' => $product->name,
                    'productId' => $product->id,
                    'sellerName' => Seller::where('id', $product->seller_id)->get()->first()->nickname
                ])
                ->json('reviews');
        });
        $reviews = ManualPaginatorAction::paginate(
            $reviews,
            5,
            $request->page
        );
        stop_measure('reviews_cache');

        $ratingCount = null;
        $avgRating = null;

        if (isset($reviews))
        {
            start_measure('api_requests', 'Rating api requests');

            $ratingCount = Cache::remember("product_{$productId}_rating_count_$page", 86400, function () use ($product)
            {
                return Http::withoutVerifying()
                    ->withHeaders(['Token' => Auth::user()->token])
                    ->post(env('APP_URL') . '/api/v1/reviews/rating_count', ['productName' => $product->name])
                    ->json('ratingCount')[0];
            });

            $avgRating = Cache::remember("product_{$productId}_avg_rating_$page", 86400, function () use ($product)
            {
                return round(Http::withoutVerifying()
                    ->withHeaders(['Token' => Auth::user()->token])
                    ->post(env('APP_URL') . '/api/v1/reviews/average_rating', ['productName' => $product->name])
                    ->json('avgRating')[0]['averageRating'], 1);
            });

            stop_measure('api_requests');
        }

        return view('catalog.product', ['product' => $product, 'reviews' => $reviews, 'ratingCount' => $ratingCount, 'avgRating' => $avgRating]);
    }
    public function rubCurrency()
    {
        session(['products_currency' => 'rub']);
        return redirect()->route('allproducts');
    }
    public function dolCurrency()
    {
        session(['products_currency' => 'dol']);
        return redirect()->route('allproducts');
    }
    public function searchProducts(APIProductsSearchRequest $request)
    {
        $validated = $request->validated();

        if (!array_key_exists('sellerName', $validated))
            $validated['sellerName'] = null;
        if (!array_key_exists('loadWith', $validated))
            $validated['loadWith'] = null;

        return SearchFormProductsSearchMethod::search($request, $validated);
    }
}
