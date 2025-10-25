<?php

namespace Tests\Feature\Middleware\Auth;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Enum\TestRouteMethods;
use App\Services\Testing\TestRouteForAuthService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthorizeAdminPanel extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAuth()
    {
        $admin = User::factory()->hasAttached(Role::factory()->create(['role' => 'admin']), relationship: 'roles')->create();
        TestRouteForAuthService::testWeb('/admin/index', $this, $admin, TestRouteMethods::GET);
    }
}
