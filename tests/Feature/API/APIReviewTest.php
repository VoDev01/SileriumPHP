<?php

namespace Tests\Feature\API;

use DateTime;
use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Review;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Category;
use App\Models\UserApiKey;
use App\Models\Subcategory;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\ClientRepository;
use Illuminate\Foundation\Testing\WithFaker;
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
        Passport::actingAs(
            $user,
            ['index']
        );

        $clientRepository = new ClientRepository();
        $client = $clientRepository->createPersonalAccessClient(
            $user->id, 'Test Personal Access Client', 'http://localhost'
        );

        DB::table('oauth_personal_access_clients')->insert([
            'client_id' => $client->id,
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
        ]);

        $user->createToken($user->email . ' token');
        $secret = $user->tokens->first()->id;
        $review = Review::factory(30)->for($user)->for($product)->create()->first();

        $response = $this->withHeader('Api-Secret', $secret)->getJson('/api/v1/reviews/index');

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
        Passport::actingAs(
            $user,
            ['update']
        );

        $clientRepository = new ClientRepository();
        $client = $clientRepository->createPersonalAccessClient(
            $user->id, 'Test Personal Access Client', 'http://localhost'
        );

        DB::table('oauth_personal_access_clients')->insert([
            'client_id' => $client->id,
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
        ]);

        $user->createToken($user->email . ' token');
        $secret = $user->tokens->first()->id;
        $review = Review::factory(30)->for($user)->for($product)->create()->first();

        $response = $this->withHeader('Api-Secret', $secret)->patchJson('/api/v1/reviews/update', [
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
        $product = $seller->products->first();$user = User::factory()->has(Role::factory())->create();
        $user = User::factory()->has(Role::factory())->create();
        Passport::actingAs(
            $user,
            ['delete']
        );

        $clientRepository = new ClientRepository();
        $client = $clientRepository->createPersonalAccessClient(
            $user->id, 'Test Personal Access Client', 'http://localhost'
        );

        DB::table('oauth_personal_access_clients')->insert([
            'client_id' => $client->id,
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
        ]);

        $user->createToken($user->email . ' token');
        $secret = $user->tokens->first()->id;
        $review = Review::factory(30)->for($user)->for($product)->create()->first();

        $response = $this->withHeader('Api-Secret', $secret)->deleteJson('/api/v1/reviews/delete', ['id' => $review->ulid]);

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
        Passport::actingAs(
            $user,
            ['delete']
        );

        $clientRepository = new ClientRepository();
        $client = $clientRepository->createPersonalAccessClient(
            $user->id, 'Test Personal Access Client', 'http://localhost'
        );

        DB::table('oauth_personal_access_clients')->insert([
            'client_id' => $client->id,
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
        ]);

        $user->createToken($user->email . ' token');
        $secret = $user->tokens->first()->id;
        $review = Review::factory(30)->for($user)->for($product)->create()->first();

        $response = $this->withHeader('Api-Secret', $secret)->postJson('/api/v1/reviews/search_user_reviews', [
            'userEmail' => $user->email,
            'userId' => $user->ulid
        ]);

        $response->assertSessionHasNoErrors();

        $response->assertOk()
            ->assertJson( fn(AssertableJson $json) => 
                    $json->has( 'reviews', 30, fn($json) =>
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
        Passport::actingAs(
            $user,
            ['delete']
        );

        $clientRepository = new ClientRepository();
        $client = $clientRepository->createPersonalAccessClient(
            $user->id, 'Test Personal Access Client', 'http://localhost'
        );

        DB::table('oauth_personal_access_clients')->insert([
            'client_id' => $client->id,
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
        ]);

        $user->createToken($user->email . ' token');
        $secret = $user->tokens->first()->id;
        $review = Review::factory(30)->for($user)->for($product)->create()->first();

        $response = $this->withHeader('Api-Secret', $secret)->postJson('/api/v1/reviews/search_product_reviews', [
            'sellerName' => $seller->nickname,
            'productName' => $product->name
        ]);

        $response->assertOk()
            ->assertJson( fn(AssertableJson $json) => 
                $json->has('reviews', 30, fn($json) =>
                    $json->where('id', $review->id)
                        ->etc()
                )
                ->etc()
        );
        
        $response->assertSessionHasNoErrors();
    }
}
