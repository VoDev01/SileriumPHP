<?php

namespace Tests\Feature\API;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\APIUser;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class APIBanTest extends TestCase
{
    use RefreshDatabase;
    
    public function testBanApiUser()
    {
        $adminRole = Role::factory()->create(['role' => 'admin']);
        $admin = User::factory()->hasAttached($adminRole, [], 'roles')->create();
        $secret = Str::random(32);
        $user = APIUser::factory()->create(['secret' => $secret]);

        $response = $this->actingAs($admin)->post('/admin/users/ban', 
            [
                'user_id' => $user->api_key,
                'admin_id' => $admin->ulid,
                'api_user' => true,
                'reason' => 'Suspicious activity',
                'duration' => 100,
                'timeType' => 'hours'
            ]
        );

        $response->assertRedirect('/admin/users/ban');

        $response->assertValid();

        $this->assertDatabaseHas('banned_users', ['user_id' => $user->api_key]);

        $response = $this->withHeaders([
            'API-Key' => $user->api_key,
            'API-Secret' => $secret
        ])->get('/api/v1/products/index/15');

        $response->assertRedirect("/banned");
        
        Carbon::setTestNow(Carbon::now()->addHours(100));

        $response = $this->withHeaders([
            'API-Key' => $user->api_key,
            'API-Secret' => $secret
        ])->get('/api/v1/products/index/15');

        $response->assertOk();

        Carbon::setTestNow();
    }
}
