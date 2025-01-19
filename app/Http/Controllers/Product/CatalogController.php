<?php

namespace App\Http\Controllers\Product;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Http\Controllers\Controller;
use App\Actions\OrderItemsAction;
use App\Facades\ProductCartServiceFacade as ProductCart;
use App\Http\Requests\API\Products\APIProductsSearchRequest;
use App\View\Components\ComponentsInputs\SearchForm\SearchFormCheckboxInput;
use App\View\Components\ComponentsInputs\SearchForm\SearchFormHiddenInput;
use App\View\Components\ComponentsInputs\SearchForm\SearchFormInput;
use App\View\Components\ComponentsInputs\SearchForm\SearchFormQueryInput;
use App\View\Components\ComponentsMethods\SearchForm\SearchFormProductsSearchMethod;

class CatalogController extends Controller
{
    public function products(int $sortOrder = 1, int $available = 1, string $subcategory = "all", string $product = "")
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
        $queryInputs = new SearchFormQueryInput('/catalog/products/search', '/catalog/products', 'images, productSpecifications');

        $relationships = null;
        if(session('loadWith') != null)
            $relationships = explode(', ', session('loadWith'));
        $query = ProductService::getFilterQuery($relationships, $subcategory, $product, $available);
        $products = OrderItemsAction::orderItem($query, $sortOrder, 15);
        if(session('products_currency') == 'dol')
        {
            ProductCart::convertCurrency($products);
        }
        return view('catalog.products', [
            'products' => $products,
            'sortOrder' => $sortOrder, 
            'subcategories' => Subcategory::all(), 
            'subcategory' => $subcategory, 
            'product' => $product,
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
        return redirect()->route('allproducts', ['sortOrder' => $request->sortOrder, 
        'available' => $request->available, 'subcategory' => $request->subcategory, 
        'product' => $request->name])->with(['loadWith' => $request->loadWith]);
    }
    public function product(int $productId)
    {
        $product = Product::where('id', $productId)->with(['productSpecifications', 'images', 'reviews'])->get()->first(); 
        $reviews = $product->reviews()->with('user')->paginate(5);
        return view('catalog.product', ['product' => $product, 'reviews' => $reviews]);
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

        return SearchFormProductsSearchMethod::searchProducts($validated);
    }
}
