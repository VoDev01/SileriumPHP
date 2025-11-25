<?php

namespace Tests\Feature\Seller;

use App\Enum\TestRouteMethods;
use Tests\TestCase;
use App\Models\Role;
use App\Models\Seller;
use App\Models\User;
use App\Services\Testing\TestRouteForAuthService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

class SellerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIndex()
    {
        $sellerRole = Role::factory()->create(['role' => 'seller']);
        $sellerUser = User::factory()->hasAttached($sellerRole, relationship: 'roles')->create();
        $seller = Seller::factory()->for($sellerUser)->create();

        $response = $this->actingAs($sellerUser)->get('/seller');

        $response->assertOk();
    }

    public function testAccount()
    {
        $sellerRole = Role::factory()->create(['role' => 'seller']);
        $sellerUser = User::factory()->hasAttached($sellerRole, relationship: 'roles')->create();
        $seller = Seller::factory()->for($sellerUser)->create();

        TestRouteForAuthService::testWeb('/seller/account', $this, $sellerUser, TestRouteMethods::GET);
    }

    public function testEditAccount()
    {
        
        $sellerRole = Role::factory()->create(['role' => 'seller']);
        $sellerUser = User::factory()->hasAttached($sellerRole, relationship: 'roles')->create();
        $seller = Seller::factory()->for($sellerUser)->create();

        TestRouteForAuthService::testWeb('/seller/account/edit', $this, $sellerUser, TestRouteMethods::GET);

        TestRouteForAuthService::testWeb('/seller/account/edit', $this, $sellerUser, TestRouteMethods::POST, [
            'nickname' => 'New nickname',
            'organization_email' => 'newemail@google.com',
            "organization_name" => '',
            "organization_type" => '',
            "tax_system" => '',
            "logo" => ''
        ]);

        $this->assertDatabaseHas('sellers', [
            'nickname' => 'New nickname',
            'organization_email' => 'newemail@google.com'
        ]);
    }
}
