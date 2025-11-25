<?php

namespace Tests\Feature\API;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Review;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use App\Enum\TestRouteMethods;
use App\Services\Testing\TestRouteForAuthService;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

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

        $response = TestRouteForAuthService::testAPI(
            '/api/v1/reviews/index',
            $this,
            TestRouteMethods::GET,
        );

        $response->assertOk()
            ->assertJson(
                fn(AssertableJson $json) =>
                $json->has(
                    'reviews.data',
                    15,
                    fn($json) =>
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
        $review = Review::factory(30)->for($user)->for($product)->create()->first();

        $response = TestRouteForAuthService::testAPI(
            '/api/v1/reviews/update',
            $this,
            TestRouteMethods::PATCH,
            [
                'id' => $review->ulid,
                'title' => $review->title,
                'pros' => null,
                'cons' => null,
                'comment' => null,
                'rating' => null
            ]
        );
    }

    public function testDelete()
    {
        Category::factory()->create();
        Subcategory::factory()->create();

        $seller = Seller::factory()->has(Product::factory())->create();
        $product = $seller->products->first();
        $user = User::factory()->has(Role::factory())->create();
        $review = Review::factory(30)->for($user)->for($product)->create()->first();

        TestRouteForAuthService::testAPI(
            '/api/v1/reviews/delete',
            $this,
            TestRouteMethods::DELETE,
            [
                'id' => $review->ulid
            ],
        );

        $this->assertDatabaseMissing('reviews', ['ulid' => $review->ulid]);
    }

    public function testSearchUserReviews()
    {
        Category::factory()->create();
        Subcategory::factory()->create();

        $seller = Seller::factory()->has(Product::factory())->create();
        $product = $seller->products->first();
        $role = Role::factory()->create(['role' => 'admin']);
        $user = User::factory()->hasAttached($role, [], 'roles')->create();
        $review = Review::factory(30)->for($user)->for($product)->create()->first();

        $response = TestRouteForAuthService::testAPI(
            '/api/v1/reviews/search_user_reviews',
            $this,
            TestRouteMethods::POST,
            [
                'userEmail' => $user->email,
                'userId' => $user->ulid
            ]
        );

        $response->assertSessionHasNoErrors();

        $response->assertOk()
            ->assertJson(
                fn(AssertableJson $json) =>
                $json->has(
                    'reviews',
                    30,
                    fn($json) =>
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
        $review = Review::factory(30)->for($user)->for($product)->create()->first();

        $response = TestRouteForAuthService::testAPI(
            '/api/v1/reviews/search_product_reviews',
            $this,
            TestRouteMethods::POST,
            [
                'sellerName' => $seller->nickname,
                'productName' => $product->name
            ]
        );

        $response->assertOk()
            ->assertJson(
                fn(AssertableJson $json) =>
                $json->has(
                    'reviews',
                    30,
                    fn($json) =>
                    $json->where('id', $review->id)
                        ->etc()
                )
                    ->etc()
            );

        $response->assertSessionHasNoErrors();
    }

    public function testProductAverageRating()
    {

        Category::factory()->create();
        Subcategory::factory()->create();

        $seller = Seller::factory()->has(Product::factory())->create();
        $product = $seller->products->first();
        $role = Role::factory()->create(['role' => 'admin']);
        $user = User::factory()->hasAttached($role, [], 'roles')->create();
        $review = Review::factory(30)->for($user)->for($product)->create()->first();

        $response = TestRouteForAuthService::testAPI(
            '/api/v1/reviews/average_rating',
            $this,
            TestRouteMethods::POST,
            [
                'productName' => $product->name
            ]
        );

        $response->assertOk()
            ->assertJson(
                fn(AssertableJson $json) =>
                $json->has(
                    'avgRating',
                    1,
                    fn($json) =>
                    $json->where('product_id', $product->id)
                        ->etc()
                )
                    ->etc()
            );
    }
    public function testProductRatingCount()
    {

        Category::factory()->create();
        Subcategory::factory()->create();

        $seller = Seller::factory()->has(Product::factory())->create();
        $product = $seller->products->first();
        $role = Role::factory()->create(['role' => 'admin']);
        $user = User::factory()->hasAttached($role, [], 'roles')->create();
        $review = Review::factory(30)->for($user)->for($product)->create()->first();

        $response = TestRouteForAuthService::testAPI(
            '/api/v1/reviews/rating_count',
            $this,
            TestRouteMethods::POST,
            [
                'productName' => $product->name
            ]
        );

        $response->assertOk()
            ->assertJson(
                fn(AssertableJson $json) =>
                $json->has(
                    'ratingCount',
                    1,
                    fn($json) =>
                    $json->where('product_id', $product->id)
                        ->etc()
                )
                    ->etc()
            );
    }
}
