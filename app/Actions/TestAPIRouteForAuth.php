<?php

namespace App\Actions;

use Closure;
use App\Models\APIUser;
use Illuminate\Support\Str;
use App\Enum\TestAPIRouteMethods;
use Illuminate\Testing\TestResponse;
use Illuminate\Foundation\Testing\TestCase;

class TestAPIRouteForAuth
{
    /**
     * @param string $route API action path
     * @param \App\Enum\TestAPIRouteMethods $httpMethod Route http method
     * @param array $data Data to pass to tested route
     * @param Illuminate\Foundation\Testing\TestCase Instance of the test case in which this function is used
     */
    public static function test(string $route, TestAPIRouteMethods $httpMethod, array $data = null, TestCase $testClass): TestResponse
    {
        $secret = Str::random(32);
        $apiUser = APIUser::factory()->create(['secret' => $secret]);
        //Choose assert function method
        $assert = match($httpMethod)
        {
            TestAPIRouteMethods::GET => function() {
                return function($route, $data, $headers, $testClass) { return $testClass->getJson($route, $headers); };
            },
            TestAPIRouteMethods::POST => function() {
                return function($route, $data, $headers, $testClass) { return $testClass->postJson($route, $data, $headers); };
            },
            TestAPIRouteMethods::PUT => function() {
                return function($route, $data, $headers, $testClass)  { return $testClass->putJson($route, $data, $headers); };
            },
            TestAPIRouteMethods::PATCH => function() {
                return function($route, $data, $headers, $testClass)  { return $testClass->patchJson($route, $data, $headers); };
            },
            TestAPIRouteMethods::DELETE => function() {
                return function($route, $data, $headers, $testClass)  { return $testClass->deleteJson($route, $data, $headers); };
            }
        };
        $assert = call_user_func($assert);

        $response = new TestResponse($assert($route, $data, [
            'API-Secret' => '12345',
            'API-Key' => '12345'
        ], $testClass));

        $response->assertForbidden();

        $response = new TestResponse($assert($route, $data, [
            'API-Secret' => $secret,
            'API-Key' => $apiUser->api_key
        ], $testClass));

        $response->assertOk();

        return $response;
    }
}
