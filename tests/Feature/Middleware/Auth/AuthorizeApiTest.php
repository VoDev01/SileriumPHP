<?php

namespace Tests\Feature\Middleware\Auth;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Seller;
use App\Models\APIUser;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthorizeApiTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAuth()
    {
        Category::factory()->create();
        Subcategory::factory()->create();
        Seller::factory()->has(Product::factory(15))->create();

        $user = User::factory()->hasAttached(Role::factory()->create(['role' => 'user']), relationship: 'roles')->create();

        $secret = Str::random(32);
        $apiUser = APIUser::factory()->create(['secret' => $secret]);

        $response = $this->getJson('/api/v1/products/index');

        $this->assertTrue(
            $response->baseResponse->status() === 401 ||
                $response->baseResponse->status() === 404 ||
                $response->baseResponse->status() === 403
        );

        $response = $this->withHeaders([
            'API-Key' => '12345',
            'API-Secret' => $secret
        ])->getJson('/api/v1/products/index');

        $this->assertTrue(
            $response->baseResponse->status() === 401 ||
                $response->baseResponse->status() === 404 ||
                $response->baseResponse->status() === 403
        );

        $response = $this->withHeaders([
            'API-Key' => $apiUser->api_key,
            'API-Secret' => '12345'
        ])->getJson('/api/v1/products/index');

        $this->assertTrue(
            $response->baseResponse->status() === 401 ||
                $response->baseResponse->status() === 404 ||
                $response->baseResponse->status() === 403
        );

        $response = $this->withHeaders([
            'API-Key' => '',
            'API-Secret' => ''
        ])->getJson('/api/v1/products/index');

        $this->assertTrue(
            $response->baseResponse->status() === 401 ||
                $response->baseResponse->status() === 404 ||
                $response->baseResponse->status() === 403
        );

        $response = $this->withHeaders([
            'API-Key' => $apiUser->api_key,
            'API-Secret' => $secret
        ])->getJson('/api/v1/products/index');

        $response->assertOk();
    }
}
