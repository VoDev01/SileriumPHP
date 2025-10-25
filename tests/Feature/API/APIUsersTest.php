<?php

namespace Tests\Feature\API;

use App\Enum\TestRouteMethods;
use DateTime;
use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Services\Testing\TestRouteForAuthService;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class APIUsersTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testSearch()
    {
        $user = User::factory()->hasAttached(Role::factory()->create(['role' => 'admin']), relationship: 'roles')->create();

        $response = TestRouteForAuthService::testAPI(
            '/api/v1/user/search/',
            $this,
            TestRouteMethods::POST,
            [
                'id' => $user->ulid,
                'email' => $user->email
            ]
        );
    }
}
