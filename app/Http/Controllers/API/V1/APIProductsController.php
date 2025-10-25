<?php

namespace App\Http\Controllers\API\V1;

use App\Repositories\ProductRepository;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\Products\APIAmountExpirySearchRequest;
use App\Http\Requests\API\Products\APIProductsCreateRequest;
use App\Http\Requests\API\Products\APIProductsDeleteRequest;
use App\Http\Requests\API\Products\APIProductsSearchRequest;
use App\Http\Requests\API\Products\APIProductsUpdateRequest;
use App\Http\Requests\API\Products\APIProfitSearchRequest;
use App\Http\Requests\API\Products\APIConsumptionSearchRequest;
use App\Services\Products\ProductService;
use App\Services\SearchForms\ProductSearchFormService;

class APIProductsController extends Controller
{
    public function index(int $itemsPerPage = 15)
    {
        return response()->json((new ProductRepository)->index($itemsPerPage), 200);
    }
    public function show(string $id)
    {
        $product = (new ProductRepository)->show($id);
        if ($product != null)
            return response()->json($product, 200);
        else
            return response()->json(['message' => 'No product was found with this id'], 400);
    }
    public function create(APIProductsCreateRequest $request)
    {
        $validated = $request->validated();
        $product = (new ProductRepository)->create($validated, null, ['*']);
        return response()->json(['Url' => env('APP_URL') . '/catalog/product/' . $product->ulid], 200);
    }
    public function update(APIProductsUpdateRequest $request)
    {
        $validated = $request->validated();
        
        $product = (new ProductRepository)->update($validated);

        return response()->json(['updated_product' => $product], 200);
    }
    public function delete(APIProductsDeleteRequest $request)
    {
        $validated = $request->validated();

        if (!(new ProductRepository)->delete($validated['id']))
            return response()->json(['message' => 'Trying to delete non-existent entity'], 404);
        else
            return response()->json(null, 200);
    }
    public function search(APIProductsSearchRequest $request)
    {
        $validated = $request->validated();
        
        return (new ProductSearchFormService)->search($validated, 'products');
    }
    public function profitBetweenDate(APIProfitSearchRequest $request)
    {

        $validated = $request->validated();
        $profits = (new ProductService)->profitBetweenDate($validated);
        if (!empty($profits))
            return response()->json(['profits' => $profits], 200);
        else
            return response()->json(['message' => 'За данный период не найдено доходов.'], 404);
    
    }
    public function consumptionBetweenDate(APIConsumptionSearchRequest $request)
    {
        $validated = $request->validated();
        
        $consumption = (new ProductService)->consumptionBetweenDate($validated);

        if (!empty($consumption))
            return response()->json(['consumption' => $consumption], 200);
        else
            return response()->json(['message' => 'Количество продаж товара за указанный период не найдено.'], 404);
    }
    public function amountExpiry(APIAmountExpirySearchRequest $request)
    {
        $validated = $request->validated();
        
        $expiresAt = (new ProductService)->amountExpiry($validated);

        if ($expiresAt != null)
            return response()->json(['expiresAt' => $expiresAt], 200);
        else
            return response()->json(['message' => 'За данный период не найдено товаров.'], 404);
    }
}
