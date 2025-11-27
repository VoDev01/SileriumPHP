<?php

namespace App\Services\Testing;

use App\Models\User;
use App\Models\APIUser;
use Illuminate\Support\Str;
use App\Enum\TestRouteMethods;
use Illuminate\Testing\TestResponse;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TestRouteForAuthService
{
    /**
     * @param string $route API action path
     * @param TestRouteMethods $httpMethod Route http method
     * @param array $data Data to pass to tested route
     * @param Illuminate\Foundation\Testing\TestCase Instance of the test case in which this function is used
     */
    public static function testAPI(
        string $route,
        TestCase $testCase,
        TestRouteMethods $httpMethod = TestRouteMethods::GET,
        array $data = [],
        array $headers = []
    ): TestResponse
    {
        $secret = Str::random(32);
        $apiUser = APIUser::factory()->create(['secret' => $secret]);
        //Choose assert function method
        $assert = match ($httpMethod)
        {
            TestRouteMethods::GET => function ()
            {
                return function ($route, $data, $headers, $testClass)
                {
                    return $testClass->getJson($route, $headers);
                };
            },
            TestRouteMethods::POST => function ()
            {
                return function ($route, $data, $headers, $testClass)
                {
                    return $testClass->postJson($route, $data, $headers);
                };
            },
            TestRouteMethods::PUT => function ()
            {
                return function ($route, $data, $headers, $testClass)
                {
                    return $testClass->putJson($route, $data, $headers);
                };
            },
            TestRouteMethods::PATCH => function ()
            {
                return function ($route, $data, $headers, $testClass)
                {
                    return $testClass->patchJson($route, $data, $headers);
                };
            },
            TestRouteMethods::DELETE => function ()
            {
                return function ($route, $data, $headers, $testClass)
                {
                    return $testClass->deleteJson($route, $data, $headers);
                };
            }
        };
        $assert = call_user_func($assert);

        $response = call_user_func(
            $assert,
            $route,
            $data,
            array_merge($headers, [
                'API-Secret' => '12345',
                'API-Key' => '12345'
            ]),
            $testCase
        );

        $testCase->assertTrue(
            $response->baseResponse->status() === 401 ||
                $response->baseResponse->status() === 404 ||
                $response->baseResponse->status() === 403
        );

        $response = call_user_func(
            $assert,
            $route,
            $data,
            array_merge($headers, [
                'API-Secret' => $secret,
                'API-Key' => $apiUser->api_key
            ]),
            $testCase
        );

        $response->assertOk();

        return $response;
    }


    /**
     * @param string $route API action path
     * @param TestRouteMethods $httpMethod Route http method
     * @param array $data Data to pass to tested route
     * @param Illuminate\Foundation\Testing\TestCase Instance of the test case in which this function is used
     */
    public static function testWeb(
        string $route,
        TestCase $testCase,
        User $user,
        TestRouteMethods $httpMethod,
        array $data = [],
        array $headers = []
    ): TestResponse
    {
        //Choose assert function method
        $assert = match ($httpMethod)
        {
            TestRouteMethods::GET => function ()
            {
                return function ($route, $data, $headers, $testClass, $user = null)
                {
                    if (isset($user))
                        $testClass = $testClass->actingAs($user);
                    return $testClass->get($route, $headers);
                };
            },
            TestRouteMethods::POST => function ()
            {
                return function ($route, $data, $headers, $testClass, $user = null)
                {
                    if (isset($user))
                        $testClass = $testClass->actingAs($user);
                    return $testClass->post($route, $data, $headers);
                };
            },
            TestRouteMethods::PUT => function ()
            {
                return function ($route, $data, $headers, $testClass, $user = null)
                {
                    if (isset($user))
                        $testClass = $testClass->actingAs($user);
                    return $testClass->put($route, $data, $headers);
                };
            },
            TestRouteMethods::PATCH => function ()
            {
                return function ($route, $data, $headers, $testClass, $user = null)
                {
                    if (isset($user))
                        $testClass = $testClass->actingAs($user);
                    return $testClass->patch($route, $data, $headers);
                };
            },
            TestRouteMethods::DELETE => function ()
            {
                return function ($route, $data, $headers, $testClass, $user = null)
                {
                    if (isset($user))
                        $testClass = $testClass->actingAs($user);
                    return $testClass->delete($route, $data, $headers);
                };
            }
        };
        $assert = call_user_func($assert);

        Auth::guard('web')->logout();

        $response = call_user_func(
            $assert,
            $route,
            $data,
            $headers,
            $testCase
        );

        $testCase->assertTrue(
            $response->baseResponse->status() === 401 ||
            $response->baseResponse->status() === 404 ||
            $response->baseResponse->status() === 403 ||
            $response->baseResponse->status() === 302
        );

        $response = call_user_func(
            $assert,
            $route,
            $data,
            $headers,
            $testCase,
            $user
        );

        $testCase->assertTrue(
            $response->baseResponse->status() === 200 ||
                $response->baseResponse->status() === 302
        );

        return $response;
    }
}
