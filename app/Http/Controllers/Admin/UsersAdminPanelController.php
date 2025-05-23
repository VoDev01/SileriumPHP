<?php

namespace App\Http\Controllers\Admin;

use Str;
use App\Models\Role;
use App\Models\User;
use App\Models\ApiUser;
use App\Models\Payment;
use App\Models\BannedUser;
use Illuminate\Http\Request;
use App\Models\BannedApiUser;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Actions\ManualPaginatorAction;
use App\Http\Requests\User\UserBanRequest;
use App\Services\SearchFormPaginateResponseService;
use App\Http\Requests\API\Users\APIUserSearchRequest;
use App\View\Components\ComponentsInputs\SearchForm\SearchFormInput;
use App\View\Components\ComponentsInputs\SearchForm\SearchFormQueryInput;
use App\View\Components\ComponentsMethods\SearchForm\SearchFormUsersSearchMethod;

class UsersAdminPanelController extends Controller
{
    public function index(Request $request)
    {
        $users = SearchFormPaginateResponseService::paginate($request, 'users') ?? User::paginate(15);
        $inputs = [
            new SearchFormInput('email', 'Email', 'email', false),
            new SearchFormInput('name', 'Имя', 'name', false),
            new SearchFormInput('surname', 'Фамилия', 'surname', false),
            new SearchFormInput('phone', 'Телефон', 'phone', false)
        ];
        $queryInputs = new SearchFormQueryInput('/admin/users/search', 'admin.users.index', null);
        return view('admin.users.index', ['users' => $users, 'inputs' => $inputs, 'queryInputs' => $queryInputs]);
    }
    public function roles()
    {
        $users = User::with('roles:role')->paginate(10);
        $roles = Role::paginate(5);
        $inputs = [
            new SearchFormInput('email', 'Email', 'email', false),
            new SearchFormInput('name', 'Имя', 'name', false),
            new SearchFormInput('surname', 'Фамилия', 'surname', false),
            new SearchFormInput('phone', 'Телефон', 'phone', false)
        ];
        $queryInputs = new SearchFormQueryInput('/admin/users/search', 'admin.users.roles', 'roles');
        return view('admin.users.roles', ['users' => $users, 'roles' => $roles, 'inputs' => $inputs, 'queryInputs' => $queryInputs]);
    }
    public function orders(Request $request)
    {
        $users = SearchFormPaginateResponseService::paginate($request, 'users');

        $orders = null;
        $user = null;
        $message = null;

        if ($request->session()->get('orders') != null)
        {
            $orders = $request->session()->get('orders');
            foreach ($orders as $order)
            {
                foreach ($order['products'] as $product)
                    array_push($order['productsNames'], $product['name']);
                explode(', ', $order['productsNames']);
                unset($order['products']);
            }
            $orders = ManualPaginatorAction::paginate($orders);
        }
        if ($request->session()->get('user') != null)
            $user = $request->session()->get('user');
        if ($request->session()->get('message') != null)
            $message = $request->session()->get('message');

        $inputs = [
            new SearchFormInput('email', 'Email', 'email', false),
            new SearchFormInput('name', 'Имя', 'name', false),
            new SearchFormInput('surname', 'Фамилия', 'surname', false),
            new SearchFormInput('phone', 'Телефон', 'phone', false)
        ];
        $queryInputs = new SearchFormQueryInput('/admin/users/search', 'admin.users.orders', 'orders');
        return view('admin.users.orders', ['users' => $users, 'user' => $user, 'message' => $message, 'orders' => $orders, 'inputs' => $inputs, 'queryInputs' => $queryInputs]);
    }
    public function searchUserOrders(Request $request)
    {
        $user = User::with(['orders', 'orders.product'])->where('ulid', $request->id)->get()->first();
        if ($user != null)
        {
            if ($user->orders != null)
            {
                $orders = $user->orders->toArray();
                return redirect()->route('admin.users.orders')->with('orders', $orders)->with('user', $user);
            }
            else
                return redirect()->route('admin.users.orders')->with('message', 'Заказов данного пользователя не существует.');
        }
        else
            return redirect()->route('admin.users.orders')->with('message', 'Данного пользователя не существует.');
    }
    public function reviews(Request $request)
    {
        $users = SearchFormPaginateResponseService::paginate($request, 'users');
        $reviews = null;
        $user = null;
        $message = null;

        if ($request->session()->get('reviews') != null)
            $reviews = ManualPaginatorAction::paginate($request->session()->get('reviews'));
        if ($request->session()->get('user') != null)
            $user = $request->session()->get('user');
        if ($request->session()->get('message') != null)
            $message = $request->session()->get('message');

        $inputs = [
            new SearchFormInput('email', 'Email', 'email', false),
            new SearchFormInput('name', 'Имя', 'name', false),
            new SearchFormInput('surname', 'Фамилия', 'surname', false),
            new SearchFormInput('phone', 'Телефон', 'phone', false)
        ];
        $queryInputs = new SearchFormQueryInput('/admin/users/search', 'admin.users.reviews', 'reviews');
        return view('admin.users.reviews', ['users' => $users, 'user' => $user, 'reviews' => $reviews, 'message' => $message, 'inputs' => $inputs, 'queryInputs' => $queryInputs]);
    }
    public function payments(Request $request)
    {
        $payments = SearchFormPaginateResponseService::paginate($request, 'payments', 15) ?? Payment::with(['order', 'order.user'])->paginate(15);
        $inputs = [
            new SearchFormInput('name', 'Имя заказчика', 'name', true),
            new SearchFormInput('surname', 'Фамилия заказчика', 'surname', true)
        ];
        $queryInputs = new SearchFormQueryInput('/admin/users/payments/search', 'admin.users.payments', 'order, order.user');
        return view('admin.users.payments', ['payments' => $payments, 'inputs' => $inputs, 'queryInputs' => $queryInputs]);
    }
    public function searchUserReviews(Request $request)
    {
        $user = User::with(['reviews', 'reviews.product'])->where('ulid', $request->id)->get()->first();
        if ($user != null)
        {
            if ($user->reviews != null)
            {
                $reviews = $user->reviews->toArray();
                return redirect()->route('admin.users.reviews')->with('reviews', $reviews)->with('user', $user);
            }
            else
                return redirect()->route('admin.users.reviews')->with('message', 'Отзывов данного пользователя не существует.');
        }
        else
            return redirect()->route('admin.users.reviews')->with('message', 'Данного пользователя не существует.');
    }
    public function ban(Request $request)
    {
        $users = SearchFormPaginateResponseService::paginate($request, 'users', 15);
        $message = $request->session()->get('message');
        $inputs = [
            new SearchFormInput('email', 'Email', 'email', false),
            new SearchFormInput('name', 'Имя', 'name', false),
            new SearchFormInput('surname', 'Фамилия', 'surname', false),
            new SearchFormInput('phone', 'Телефон', 'phone', false)
        ];
        $queryInputs = new SearchFormQueryInput('/admin/users/search', 'admin.users.ban', null);
        return view('admin.users.ban', ['users' => $users, 'inputs' => $inputs, 'queryInputs' => $queryInputs, 'message' => $message]);
    }
    public function postBan(UserBanRequest $request)
    {
        $validated = $request->validated();

        $admin_id = User::where('ulid', $validated['admin_id'])->get()->first()->ulid;
        if (!key_exists('api_user', $validated))
        {
            $user_id = User::where('ulid', $validated['user_id'])->get()->first()->ulid;
            BannedUser::create([
                'user_id' => $user_id,
                'admin_id' => $admin_id,
                'userIp' => $request->ip(),
                'reason' => $validated['reason'],
                'duration' => $validated['duration'],
                'timeType' => $validated['timeType'],
                'bannedAt' => Carbon::now()
            ]);
        }
        else
        {
            $user_id = User::where('ulid', $validated['user_id'])->get()->first()->ulid;
            BannedUser::create([
                'user_id' => $user_id,
                'admin_id' => $admin_id,
                'userIp' => $request->ip(),
                'reason' => $validated['reason'],
                'duration' => $validated['duration'],
                'timeType' => $validated['timeType'],
                'bannedAt' => Carbon::now()
            ]);
        }
        return redirect()->route('admin.users.ban');
    }
    public function searchUsers(APIUserSearchRequest $request)
    {
        $validated = $request->validated();
        if (!array_key_exists('id', $validated))
            $validated['id'] = null;
        if (!array_key_exists('email', $validated))
            $validated['email'] = null;
        if (!array_key_exists('name', $validated))
            $validated['name'] = null;
        if (!array_key_exists('surname', $validated))
            $validated['surname'] = null;
        if (!array_key_exists('phone', $validated))
            $validated['phone'] = null;
        if (!array_key_exists('redirect', $validated))
            $validated['redirect'] = null;
        if (!array_key_exists('loadWith', $validated))
            $validated['loadWith'] = null;
        return SearchFormUsersSearchMethod::searchUsers($request, $validated);
    }
}