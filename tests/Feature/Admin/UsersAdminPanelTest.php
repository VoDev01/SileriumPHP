<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Order;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use App\Enum\TestRouteMethods;
use Illuminate\Support\Facades\Cache;
use App\Actions\ManualPaginatorAction;
use App\Models\Payment;
use App\Models\Review;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Services\Testing\TestRouteForAuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersAdminPanelTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIndex()
    {
        $users = User::factory(15)->create();
        $user = $users->first();
        $admin = User::factory()->hasAttached(Role::factory()->create(['role' => 'admin']), relationship: 'roles')->create();

        $response = TestRouteForAuthService::testWeb('/admin/users/index', $this, $admin, TestRouteMethods::GET);

        $response->assertViewHas('users', function (LengthAwarePaginator $usersPage) use ($user)
        {
            foreach ($usersPage->items() as $userPage)
            {
                if ($userPage->ulid === $user->ulid)
                {
                    return true;
                }
            }
            return false;
        });
    }

    public function testRoles()
    {
        $roles = collect([
            Role::factory()->create(),
            Role::factory()->create(['role' => 'admin']),
            Role::factory()->create(['role' => 'seller'])
        ]);
        $users = User::factory(15)->hasAttached($roles->where('role', 'user'), [], 'roles')->create();
        $user = $users->first();
        $admin = User::factory()->hasAttached($roles->where('role', 'admin'), [], 'roles')->create();

        $response = TestRouteForAuthService::testWeb('/admin/users/roles', $this, $admin, TestRouteMethods::GET);

        $response->assertViewHas('users', function (LengthAwarePaginator $usersPage) use ($user)
        {
            foreach ($usersPage->items() as $userPage)
            {
                if ($userPage->ulid === $user->ulid)
                {
                    return true;
                }
            }
            return false;
        });
    }

    public function testAddRole()
    {
        $admin = User::factory()->hasAttached(Role::factory()->create(['role' => 'admin']), relationship: 'roles')->create();

        $response = TestRouteForAuthService::testWeb('/admin/users/roles/add', $this, $admin, TestRouteMethods::POST, ['role' => 'user']);

        $this->assertDatabaseHas('roles', ['role' => 'user']);
    }

    public function testAssignRole()
    {

        $roles = collect([
            Role::factory()->create(),
            Role::factory()->create(['role' => 'admin']),
            Role::factory()->create(['role' => 'seller']),
            Role::factory()->create(['role' => 'moderator'])
        ]);
        $user = User::factory()->hasAttached($roles->where('role', 'user'), [], 'roles')->create();
        $admin = User::factory()->hasAttached($roles->where('role', 'admin'), [], 'roles')->create();

        $response = TestRouteForAuthService::testWeb('/admin/users/roles/assign', $this, $admin, TestRouteMethods::POST, [
            'role' => ['seller', 'moderator'],
            'user' => $user->email
        ]);

        $this->assertDatabaseHas('users_roles', ['user_id' => $user->id, 'role_id' => $roles->where('role', 'seller')->first()->id]);
        $this->assertDatabaseHas('users_roles', ['user_id' => $user->id, 'role_id' => $roles->where('role', 'moderator')->first()->id]);
    }

    public function testOrders()
    {
        Category::factory()->create();
        Subcategory::factory()->create();

        $users = User::factory(15)->hasAttached(Role::factory()->create(['role' => 'user']), [], 'roles')->create();
        $user = $users->first();

        $userSeller = User::factory()->hasAttached(Role::factory()->create(['role' => 'seller']), [], 'roles')
            ->has(Seller::factory()->has(Product::factory(15)))->create();

        $admin = User::factory()->hasAttached(Role::factory()->create(['role' => 'admin']), [], 'roles')->create();

        $orders = Order::factory(15)->for($user)->hasAttached(
            $userSeller->seller->products->first(),
            [
                'productAmount' => 1,
                'productsPrice' => $userSeller->seller->products->first()->priceRub
            ],
            'products'
        )->create();
        $order = $orders->first();

        $response = TestRouteForAuthService::testWeb('/admin/users/orders', $this, $admin, TestRouteMethods::POST, [
            'id' => $user->ulid
        ]);

        $this->assertTrue(Cache::has('user') && Cache::has('orders'));

        $response = TestRouteForAuthService::testWeb('/admin/users/orders', $this, $admin, TestRouteMethods::GET);

        $response->assertViewHas('user', $user);

        $response->assertViewHas('orders', function (LengthAwarePaginator $orders) use ($order)
        {
            foreach ($orders->items() as $orderPage)
            {
                if ($orderPage['ulid'] === $order->ulid)
                {
                    return true;
                }
            }
            return false;
        });
    }

    public function testReviews()
    {
        Category::factory()->create();
        Subcategory::factory()->create();
        $roles = collect([
            Role::factory()->create(),
            Role::factory()->create(['role' => 'admin']),
            Role::factory()->create(['role' => 'seller'])
        ]);
        $user = User::factory()->hasAttached($roles->where('role', 'user'), [], 'roles')->create();
        $userSeller = User::factory()->hasAttached($roles->where('role', 'seller'), [], 'roles')->has(Seller::factory()->has(Product::factory(15)))->create();
        $admin = User::factory()->hasAttached($roles->where('role', 'admin'), [], 'roles')->create();
        $reviews = Review::factory(15)->for($user)->for($userSeller->seller->products->first())->create();
        $review = $reviews->first();

        $response = TestRouteForAuthService::testWeb('/admin/users/reviews', $this, $admin, TestRouteMethods::POST, [
            'id' => $user->ulid
        ]);

        $this->assertTrue(Cache::has('user') && Cache::has('reviews'));

        $response = TestRouteForAuthService::testWeb('/admin/users/reviews', $this, $admin, TestRouteMethods::GET);

        $response->assertViewHas('user', $user);

        $response->assertViewHas('reviews', function (LengthAwarePaginator $reviews) use ($review)
        {
            foreach ($reviews->items() as $reviewPage)
            {
                if ($reviewPage['ulid'] === $review->ulid)
                {
                    return true;
                }
            }
            return false;
        });
    }

    public function testPayments()
    {
        Category::factory()->create();
        Subcategory::factory()->create();

        $roles = collect([
            Role::factory()->create(),
            Role::factory()->create(['role' => 'admin']),
            Role::factory()->create(['role' => 'seller'])
        ]);

        $users = User::factory(15)->hasAttached($roles->where('role', 'user'), [], 'roles')->create();
        $user = $users->first();

        $userSeller = User::factory()->hasAttached($roles->where('role', 'seller'), [], 'roles')->has(Seller::factory()->has(Product::factory(15)))->create();
        $admin = User::factory()->hasAttached($roles->where('role', 'admin'), [], 'roles')->create();

        $orders = Order::factory(15)->for($user)->hasAttached(
            $userSeller->seller->products->first(),
            [
                'productAmount' => 1,
                'productsPrice' => $userSeller->seller->products->first()->priceRub
            ],
            'products'
        )
            ->has(Payment::factory())
            ->create();

        $payment = Payment::where('order_id', $orders->first()->ulid)->get()->first();

        $response = TestRouteForAuthService::testWeb('/admin/users/payments/search', $this, $admin, TestRouteMethods::POST, [
            'id' => $user->ulid
        ]);

        $this->assertTrue(Cache::has('user') && Cache::has('payments'));

        $response = TestRouteForAuthService::testWeb('/admin/users/payments', $this, $admin, TestRouteMethods::GET);

        $response->assertViewHas('payments', function (LengthAwarePaginator $payments) use ($payment)
        {
            foreach ($payments->items() as $paymentPage)
            {
                if ($paymentPage['payment_id'] === $payment->payment_id)
                {
                    return true;
                }
            }
            return false;
        });
    }
}
