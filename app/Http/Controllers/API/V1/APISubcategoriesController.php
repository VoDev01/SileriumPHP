<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class APISubcategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(int $itemsPerPage = 15)
    {
        $subcategories = Subcategory::orderBy('id')->paginate($itemsPerPage);
        return response()->json($subcategories, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $subcategory = Subcategory::create([
            'name' => $request->name,
            'image' => $request->image,
            'category_id' => $request->categoryId
        ]);
        return response()->json(null, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $subcategory = Subcategory::find($id);
        return response()->json($subcategory, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $subcategory = Subcategory::find($request->id);
        $subcategory->name = $request->name;
        $subcategory->image = $request->image;
        $subcategory->save();
        return response()->json(null, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        Subcategory::destroy($request->id);
        return response()->json(null, 200);
    }
}
