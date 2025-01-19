<?php

namespace Tests\Feature\API;

use Tests\TestCase;
use App\Models\Role;
use App\Models\ApiUser;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class APIBanTest extends TestCase
{
    use RefreshDatabase;
    
    public function testBan()
    {
        $adminRole = Role::factory()->create(['role' => 'admin']);
        $admin = ApiUser::factory()->hasAttached($adminRole, [], 'roles')->create();
        $role = Role::where('role', 'api_user')->get()->first();
        $user = ApiUser::factory()->hasAttached($role, [], 'roles')->create();

        $response = $this->actingAs($admin)->post('/admin/users/ban', 
            [
                'user_id' => $user->ulid,
                'admin_id' => $admin->ulid,
                'reason' => 'Spam',
                'duration' => 100,
                'timeType' => 'hours'
            ]
        );

        $response->assertRedirect('/admin/users/ban');

        $response->assertValid();

        $this->assertDatabaseHas('banned_users', ['user_id' => $user->ulid]);

        $response = $this->actingAs($user)->get('/api/v1/profile');

        $response->assertRedirect("/banned");
        
        Carbon::setTestNow(Carbon::now()->addHours(100));

        $response = $this->actingAs($user)->get('/api/v1/profile');

        $response->assertOk();

        Carbon::setTestNow();
    }
}
