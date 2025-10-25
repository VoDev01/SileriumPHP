<?php

namespace Tests\Feature\Admin;

use App\Services\Testing\TestRouteForAuthService;
use App\Enum\TestRouteMethods;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIndex()
    {
        $admin = User::factory()->hasAttached(Role::factory()->create(['role' => 'admin']), relationship: 'roles')->create();
        TestRouteForAuthService::testWeb('/admin/index', $this, $admin, TestRouteMethods::GET);
    }

    public function testProfile()
    {
        $admin = User::factory()->hasAttached(Role::factory()->create(['role' => 'admin']), relationship: 'roles')->create();
        TestRouteForAuthService::testWeb('/admin/profile', $this, $admin, TestRouteMethods::GET);
    }

    public function testLogout()
    {
        $admin = User::factory()->hasAttached(Role::factory()->create(['role' => 'admin']), relationship: 'roles')->create();
        $response = $this->actingAs($admin)->get('/admin/profile');

        $response->assertOk();

        $response = $this->actingAs($admin)->post('/admin/logout');

        $response->assertRedirect('/');

        $response = $this->actingAs($admin)->get('/admin/profile');

        $response->assertNotFound();
    }
}
