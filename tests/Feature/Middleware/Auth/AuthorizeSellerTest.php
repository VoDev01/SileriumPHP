<?php

namespace Tests\Feature\Middleware\Auth;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Seller;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthorizeSellerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAuth()
    {
        $userSeller = User::factory()->hasAttached(Role::factory()->create(['role' => 'seller']), relationship: 'roles')
        ->has(Seller::factory())->create(); 
        
        $user = User::factory()->hasAttached(Role::factory()->create(['role' => 'user']), relationship: 'roles')->create();

        $admin = User::factory()->hasAttached(Role::factory()->create(['role' => 'admin']), relationship: 'roles')->create();

        $response = $this->get('/seller/account');

        $response->assertRedirect();

        $response = $this->actingAs($user)->get('/seller/account');

        $this->assertTrue(
            $response->baseResponse->status() === 401 ||
                $response->baseResponse->status() === 404 ||
                $response->baseResponse->status() === 403
        );

        $response = $this->actingAs($admin)->get('/seller/account');

        $this->assertTrue(
            $response->baseResponse->status() === 401 ||
                $response->baseResponse->status() === 404 ||
                $response->baseResponse->status() === 403
        );

        $response = $this->actingAs($userSeller)->get('/seller/account');
    }
}
