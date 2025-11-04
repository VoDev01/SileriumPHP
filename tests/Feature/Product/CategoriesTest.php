<?php

namespace Tests\Feature\Product;

use Tests\TestCase;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoriesTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCategories()
    {
        $categories = Category::factory(2)->create();
        $subcategories = Subcategory::factory(4)->for($categories->first())->create();
        
        $response = $this->get('/categories/all');

        $response->assertStatus(200);

        $response->assertViewHas('categories', $categories);

        $response = $this->get("/categories/{$categories->first()->id}/subcategories");

        $response->assertStatus(200);

        $response->assertViewHas('subcategories', $subcategories);
    }
}
