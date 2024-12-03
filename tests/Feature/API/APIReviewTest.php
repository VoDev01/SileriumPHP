<?php

namespace Tests\Feature\API;

use Tests\TestCase;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Review;
use App\Models\Role;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;

class APIReviewTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        Category::factory()->create();
        Subcategory::factory()->create();

        $seller = Seller::factory()->has(Product::factory())->create();
        $product = $seller->products->first();
        $user = User::factory()->has(Role::factory())->create();
        $review = Review::factory(30)->for($user)->for($product)->create()->first();

        $response = $this->actingAs($user)->getJson('/api/v1/reviews/index');

        $response->assertOk()
            ->assertJson(fn (AssertableJson $json) => 
                $json->has('reviews.data', 15, fn($json) => 
                    $json->where('ulid', $review->ulid)
                        ->etc()
                )
                ->etc()
        );
    }

    public function testUpdate()
    {
        Category::factory()->create();
        Subcategory::factory()->create();

        $seller = Seller::factory()->has(Product::factory())->create();
        $product = $seller->products->first();
        $user = User::factory()->has(Role::factory())->create();
        $review = Review::factory()->for($user)->for($product)->create();

        $response = $this->actingAs($user)->putJson('/api/v1/reviews/update', [
            'id' => $review->ulid,
            'title' => $review->title,
            'pros' => null,
            'cons' => null,
            'comment' => null,
            'rating' => null
        ]);

        $response->assertOk()
            ->assertJson( fn(AssertableJson $json) =>
                $json->where('title', $review->title)
                    ->etc()
        );
    }

    public function testDelete()
    {
        Category::factory()->create();
        Subcategory::factory()->create();

        $seller = Seller::factory()->has(Product::factory())->create();
        $product = $seller->products->first();
        $user = User::factory()->has(Role::factory())->create();
        $review = Review::factory()->for($user)->for($product)->create();

        $response = $this->actingAs($user)->deleteJson('/api/v1/reviews/delete', ['id' => $review->ulid]);

        $response->assertOk();

        $this->assertDatabaseMissing('reviews', ['ulid' => $review->ulid ]);
    }

    public function testSearchUserReviews()
    {
        Category::factory()->create();
        Subcategory::factory()->create();

        $seller = Seller::factory()->has(Product::factory())->create();
        $product = $seller->products->first();
        $role = Role::factory()->create(['role' => 'admin']);
        $user = User::factory()->hasAttached($role, [], 'roles')->create();
        $review = Review::factory()->for($user)->for($product)->create();

        $response = $this->actingAs($user)->postJson('/api/v1/reviews/search_user_reviews', [
            'userEmail' => $user->email,
            'userId' => $user->ulid
        ]);

        $response->assertSessionHasNoErrors();

        $response->assertOk()
            ->assertJson( fn(AssertableJson $json) => 
                    $json->has( 'reviews', 1, fn($json) =>
                        $json->where('id', $review->id)
                            ->etc()
                )
                ->etc()
        );
    }

    public function testSearchProductReviews()
    {
        Category::factory()->create();
        Subcategory::factory()->create();

        $seller = Seller::factory()->has(Product::factory())->create();
        $product = $seller->products->first();
        $role = Role::factory()->create(['role' => 'admin']);
        $user = User::factory()->hasAttached($role, [], 'roles')->create();
        $review = Review::factory()->for($user)->for($product)->create();

        $response = $this->actingAs($user)->postJson('/api/v1/reviews/search_product_reviews', [
            'sellerName' => $seller->nickname,
            'productName' => $product->name
        ]);

        $response->assertOk()
            ->assertJson( fn(AssertableJson $json) => 
                $json->has('reviews', 1, fn($json) =>
                    $json->where('id', $review->id)
                        ->etc()
                )
                ->etc()
        );
        
        $response->assertSessionHasNoErrors();
    }
}
