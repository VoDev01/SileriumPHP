<?php

namespace Tests\Feature\Middleware\Auth;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RefreshUserTokenTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testRefresh()
    {
        $user = User::factory()->hasAttached(Role::factory()->create(['role' => 'user']), relationship: 'roles')->create();
        $token = $user->token;
        
        $response = $this->get('/user/profile');

        $response->assertRedirect();

        $response = $this->actingAs($user)->get('/user/profile');

        $response->assertOk();

        $this->assertTrue($token === $user->token);

        Carbon::setTestNow(Carbon::now()->addHour());

        $response = $this->actingAs($user)->get('/user/profile');

        $response->assertOk();

        $this->assertTrue($token !== $user->token);

        Carbon::setTestNow();
    }
}
