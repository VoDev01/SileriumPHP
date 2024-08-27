<?php

namespace Tests\Feature\API;

use Tests\TestCase;
use App\Models\User;
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
    public function testFind()
    {
        $user = User::factory()->create();

        $response = $this->getJson('/api/v1/user/find/' . $user->email);

        $response
        ->assertOk()
        ->assertJson(fn (AssertableJson $json) =>
            $json->where('0.email', $user->email)
                ->etc()
        );
    }
}
