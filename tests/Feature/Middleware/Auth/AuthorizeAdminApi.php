<?php

namespace Tests\Feature\Middleware\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthorizeAdminApi extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAuthAdminApi()
    {
        $response = $this->get('/admin/index');

        $this->assertTrue(
            $response->baseResponse->status() === 401 ||
                $response->baseResponse->status() === 404 ||
                $response->baseResponse->status() === 403
        );
    }
}
