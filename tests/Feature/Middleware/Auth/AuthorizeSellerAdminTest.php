<?php

namespace Tests\Feature\Middleware\Auth;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Enum\TestRouteMethods;
use App\Models\Seller;
use Illuminate\Foundation\Testing\WithFaker;
use App\Services\Testing\TestRouteForAuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthorizeSellerAdminTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAuthorize()
    {
        $admin = User::factory()->hasAttached(Role::factory()->create(['role' => 'admin']), relationship: 'roles')->create();
        $userSeller = User::factory()->hasAttached(Role::factory()->create(['role' => 'seller']), relationship: 'roles')->has(Seller::factory())->create();
        TestRouteForAuthService::testWeb('/admin/index', $this, $admin, TestRouteMethods::GET);
        TestRouteForAuthService::testWeb('/seller/account', $this, $userSeller, TestRouteMethods::GET);
    }
}
