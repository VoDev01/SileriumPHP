<?php

namespace Tests\Feature\API;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class APIBanTest extends TestCase
{
    use RefreshDatabase;
    
    public function testBanUser()
    {
        $adminRole = Role::factory()->create(['role' => 'admin']);
        $admin = User::factory()->hasAttached($adminRole, [], 'roles')->create();
        $user = User::factory()->hasAttached(Role::factory(), [], 'roles')->create();

        $response = $this->actingAs($admin)->post('/admin/users/ban', 
            [
                'user_id' => $user->ulid,
                'admin_id' => $admin->ulid,
                'api_user' => true,
                'reason' => 'Suspicious activity',
                'duration' => 100,
                'timeType' => 'hours'
            ]
        );

        $response->assertRedirect('/admin/users/ban');

        $response->assertValid();

        $this->assertDatabaseHas('banned_users', ['user_id' => $user->ulid]);

        $response = $this->actingAs($user)->get('/user/profile');

        $response->assertRedirect("/banned");
        
        Carbon::setTestNow(Carbon::now()->addHours(100));

        $response = $this->actingAs($user)->get('/user/profile');

        $response->assertOk();

        Carbon::setTestNow();
    }
}
