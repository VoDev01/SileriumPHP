<?php

namespace App\Http\Controllers\Product;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Actions\ManualPaginatorAction;
use App\Enum\SortOrder;
use App\Facades\ProductCartServiceFacade as ProductCart;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Services\SearchForms\FormInputData\SearchFormInput;
use App\Http\Requests\API\Products\APIProductsSearchRequest;
use App\Repositories\ProductRepository;
use App\Services\Products\ProductService;
use App\Services\SearchForms\FormInputData\SearchFormQueryInput;
use App\Services\SearchForms\FormInputData\SearchFormHiddenInput;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Services\SearchForms\FormInputData\SearchFormCheckboxInput;
use App\Services\SearchForms\ReviewSearchFormService;
use App\Services\SearchForms\ProductSearchFormService;
use App\Services\SearchForms\SearchFormPaginateResponseService;
use App\Services\User\ReviewService;

class CatalogController extends Controller
{
    public function products(Request $request)
    {
        $sortOrder = $request->sortOrder ?? SortOrder::NAME_ASC->value;
        $subcategory = $request->subcategory ?? 'all';
        $name = $request->name ?? '';
        $available = $request->available ?? true;
        $page = $request->page ?? 1;

        $inputs = [
            new SearchFormInput('productName', 'Название товара', 'productName', false)
        ];
        $checkboxInputs = [
            new SearchFormCheckboxInput('available', 'В продаже', 'available', false, false, 'available', 1)
        ];
        $hiddenInputs = [
            new SearchFormHiddenInput('sortOrder', 'sortOrder',  $sortOrder),
            new SearchFormHiddenInput('available', 'availableHidden', 0),
            new SearchFormHiddenInput('subcategory', 'subcategory', $subcategory),
            new SearchFormHiddenInput('page', 'page', $page)
        ];
        $queryInputs = new SearchFormQueryInput('/catalog/products/search', session('loadWith', 'images'));

        try
        {
            $products = SearchFormPaginateResponseService::paginate("products_{$sortOrder}_$page", $page, 15);
            if ($products === null)
            {
                $products = (new ProductService)->getFilteredProducts(
                    $subcategory,
                    $name,
                    [
                        'ulid',
                        'id',
                        'name',
                        'available',
                        'priceRub',
                        'productAmount'
                    ],
                    $available,
                    $sortOrder
                );

                Cache::put("products_{$sortOrder}_$page", $products, env('CACHE_TTL'));
            }


            if (!isset($products))
                throw new NotFoundHttpException('Не найдено товаров по этому запросу');

            if (session('products_currency') == 'dol')
            {
                ProductCart::convertCurrency($products);
            }
        }
        catch (HttpException $e)
        {
            return response($e->getMessage(), $e->getStatusCode());
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
        session(['loadWith' => $request->loadWith], 'images');

        return redirect()->route('allproducts', [
            'sortOrder' => $request->sortOrder,
            'available' => $request->available,
            'subcategory' => $request->subcategory,
            'product' => $request->name
        ]);
    }
    public function product(Request $request, string $ulid)
    {
        start_measure('product_cache', 'product cache');

        $page = isset($request->page) ? $request->page : 1;

        try
        {
            $product = Cache::remember("product_$ulid", env('CACHE_TTL'), function () use ($ulid)
            {
                return (new ProductRepository)->show($ulid);
            });

            if (!isset($product))
                throw new NotFoundHttpException('По данному запросу товар не найден');

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
            $reviews = Cache::remember("product_{$ulid}_reviews_$page", env('CACHE_TTL'), function () use ($product)
            {
                return (new ReviewSearchFormService)->searchProductReviews([
                    'productName' => $product->name,
                    'productId' => $product->id,
                    'sellerName' => $product->nickname
                ]);
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

                $ratingCount = Cache::remember("product_{$ulid}_rating_count_$page", env('CACHE_TTL'), function () use ($product)
                {
                    return (new ReviewService)->ratingCount([
                        'productName' => $product->name
                    ]);
                });

                $avgRating = Cache::remember("product_{$ulid}_avg_rating_$page", env('CACHE_TTL'), function () use ($product)
                {
                    return (new ReviewService)->averageRating([
                        'productName' => $product->name
                    ]);
                });

                stop_measure('api_requests');
            }
        }
        catch (HttpException $e)
        {
            return response($e->getMessage(), $e->getStatusCode());
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

        return (new ProductSearchFormService)->search($validated, "products_{$validated['sortOrder']}_{$validated['page']}", '/catalog/products');
    }
}
