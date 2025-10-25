<?php

namespace Tests\Feature\Middleware\Utility;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SetMediaCacheHeader extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testHeaders()
    {
        $response = $this->get('/');

        $response->assertOk();

        $response->assertHeaderMissing('Is-Modified-Since');
        $response->assertHeaderMissing('If-Non-Match');

        $response = $this->get('/media/test_file.txt');

        $response->assertOk();

        $response->assertHeader('Last-Modified');
        $response->assertHeader('ETag');
    }
}
