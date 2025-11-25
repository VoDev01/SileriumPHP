<?php

namespace Tests\Feature\Middleware\Auth;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Seller;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthorizeUserRoutesTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAuth()
    {
        $userRole = Role::factory()->create(['role' => 'user']);
        $userSeller = User::factory()->hasAttached([$userRole, Role::factory()->create(['role' => 'seller'])], relationship: 'roles')
        ->has(Seller::factory())->create(); 
        
        $user = User::factory()->hasAttached($userRole, relationship: 'roles')->create();

        $admin = User::factory()->hasAttached(Role::factory()->create(['role' => 'admin']), relationship: 'roles')->create();

        $response = $this->get('/user/profile');

        $response->assertRedirect();

        $response = $this->actingAs($userSeller)->get('/user/profile');

        $response->assertOk();

        $response = $this->actingAs($admin)->get('/user/profile');

        $this->assertTrue(
            $response->baseResponse->status() === 401 ||
                $response->baseResponse->status() === 404 ||
                $response->baseResponse->status() === 403
        );

        $response = $this->actingAs($user)->get('/user/profile');

        $response->assertOk();
    }
}
