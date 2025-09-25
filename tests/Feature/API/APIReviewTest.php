<?php

namespace Tests\Feature\API;

use DateTime;
use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Review;
use App\Models\Seller;
use App\Models\APIUser;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Support\Str;
use App\Enum\TestAPIRouteMethods;
use App\Actions\TestAPIRouteForAuth;
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

        $response = TestAPIRouteForAuth::test(
            '/api/v1/reviews/index',
            TestAPIRouteMethods::GET,
            null,
            $this
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

        $response = TestAPIRouteForAuth::test(
            '/api/v1/reviews/update',
            TestAPIRouteMethods::PATCH,
            [
                'id' => $review->ulid,
                'title' => $review->title,
                'pros' => null,
                'cons' => null,
                'comment' => null,
                'rating' => null
            ],
            $this
        );

        $response->assertOk()
            ->assertJson(
                fn(AssertableJson $json) =>
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
        $user = User::factory()->has(Role::factory())->create();
        $review = Review::factory(30)->for($user)->for($product)->create()->first();

        TestAPIRouteForAuth::test(
            '/api/v1/reviews/delete',
            TestAPIRouteMethods::DELETE,
            ['id' => $review->ulid],
            $this
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

        $response = TestAPIRouteForAuth::test(
            '/api/v1/reviews/search_user_reviews',
            TestAPIRouteMethods::POST,
            [
                'userEmail' => $user->email,
                'userId' => $user->ulid
            ],
            $this
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

        $response = TestAPIRouteForAuth::test(
            '/api/v1/reviews/search_product_reviews',
            TestAPIRouteMethods::POST,
            [
                'sellerName' => $seller->nickname,
                'productName' => $product->name
            ],
            $this
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

        $response = TestAPIRouteForAuth::test(
            '/api/v1/reviews/average_rating',
            TestAPIRouteMethods::POST,
            [
                'productName' => $product->name
            ],
            $this
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

        $response = TestAPIRouteForAuth::test(
            '/api/v1/reviews/rating_count',
            TestAPIRouteMethods::POST,
            [
                'productName' => $product->name
            ],
            $this
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
