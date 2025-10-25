<?php

namespace Tests\Feature\Services\SearchForm;

use App\Enum\TestRouteMethods;
use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Services\Testing\TestRouteForAuthService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

class UserSearchFormServiceTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testSearch()
    {
        $user = User::factory(15)->hasAttached(Role::factory()->create(['role' => 'user']), relationship: 'roles')->create()->first();
        $admin = User::factory()->hasAttached(Role::factory()->create(['role' => 'admin']), relationship: 'roles')->create();
        
        $response = TestRouteForAuthService::testWeb('/admin/users/search', $this, $admin, TestRouteMethods::POST, [
            'email' => $user->email
        ]);

        $this->assertTrue(Cache::has('users'));
    }
}
