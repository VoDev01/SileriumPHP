<?php

namespace App\Http\Controllers\API\V1;

use App\ApiFilters\V1\ProductFilter;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ProductsCollection;
use App\Http\Resources\V1\ProductsResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = new ProductFilter();
        $filterItems = $filter->transform($request);

        $products = Product::where($filterItems[0], $filterItems[1], $filterItems[2]);

        $includeImages = $request->query('includeImages');
        $includeSpecifications = $request->query('includeSpecifications');
        $includeReviews = $request->query('includeReviews');

        if($includeImages)
        {
            $products = $products->with('images');
        }
        if($includeReviews)
        {
            $products = $products->with('reviews');
        }
        if($includeSpecifications)
        {
            $products = $products->with('productSpecifications');
        }
        
        return new ProductsCollection($products->paginate()->appends($request->query()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return new ProductsResource($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
