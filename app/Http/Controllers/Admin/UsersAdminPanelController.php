<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\User;
use App\Models\APIUser;
use App\Models\Payment;
use App\Models\BannedUser;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Actions\ManualPaginatorAction;
use App\Http\Requests\User\UserBanRequest;
use App\Http\Requests\Admin\AdminAddRoleRequest;
use App\Http\Requests\Admin\AdminAssignRoleRequest;
use App\Services\SearchForms\UserSearchFormService;
use App\Http\Requests\API\Users\APIUserSearchRequest;
use App\Services\SearchForms\FormInputData\SearchFormInput;
use App\Services\SearchForms\SearchFormPaginateResponseService;
use App\Services\SearchForms\FormInputData\SearchFormQueryInput;
use Illuminate\Support\Facades\DB;

class UsersAdminPanelController extends Controller
{
    public function index(Request $request)
    {
        $users = SearchFormPaginateResponseService::paginate('users', $request->page ?? 1) ?? User::paginate(15);
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
        $users = User::with('roles:role')->paginate(15);
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
    public function addRole()
    {
        return view('admin.users.addrole');
    }
    public function postRole(AdminAddRoleRequest $request)
    {
        $validated = $request->validated();
        Role::create(['role' => $validated['role']]);
        return redirect('/admin/users/roles');
    }
    public function assignRole()
    {
        $users = User::with('roles:role')->paginate(15);
        $roles = Role::all();
        $inputs = [
            new SearchFormInput('email', 'Email', 'email', false),
            new SearchFormInput('name', 'Имя', 'name', false),
            new SearchFormInput('surname', 'Фамилия', 'surname', false),
            new SearchFormInput('phone', 'Телефон', 'phone', false)
        ];
        $queryInputs = new SearchFormQueryInput('/admin/users/search', 'admin.users.roles', 'roles');
        return view('admin.users.assignrole', ['users' => $users, 'roles' => $roles, 'inputs' => $inputs, 'queryInputs' => $queryInputs]);
    }
    public function postAssignedRole(AdminAssignRoleRequest $request)
    {
        $validated = $request->validated();

        $userId = User::where('email', $validated['user'])->get()->first()->id;
        DB::delete('DELETE users_roles FROM users_roles RIGHT JOIN users ON users_roles.user_id = users.id WHERE users.email = ?', [$validated['user']]);
        unset($validated['user']);

        foreach ($validated['role'] as $role)
        {
            $roleId = DB::select('SELECT id FROM roles WHERE role = ?', [$role])[0]->id;
            DB::insert('INSERT INTO users_roles (user_id, role_id) VALUES(?, ?)', [$userId, $roleId]);
        }

        return redirect('/admin/users/roles');
    }
    public function orders(Request $request)
    {
        $users = SearchFormPaginateResponseService::paginate('users', $request->page ?? 1);

        $orders = null;
        $user = null;
        $message = null;

        if (Cache::has('orders'))
        {
            $orders = ManualPaginatorAction::paginate(Cache::get('orders'));
        }
        if (Cache::has('user'))
            $user = Cache::get('user');
        if (Cache::has('message'))
            $message = Cache::get('message');

        $inputs = [
            new SearchFormInput('email', 'Email', 'email', false),
            new SearchFormInput('name', 'Имя', 'name', false),
            new SearchFormInput('surname', 'Фамилия', 'surname', false),
            new SearchFormInput('phone', 'Телефон', 'phone', false)
        ];

        $queryInputs = new SearchFormQueryInput('/admin/users/search', 'admin.users.orders', 'orders');
        return view('admin.users.orders', [
            'users' => $users,
            'user' => $user,
            'message' => $message,
            'orders' => $orders,
            'inputs' => $inputs,
            'queryInputs' => $queryInputs
        ]);
    }
    public function searchUserOrders(Request $request)
    {
        $user = User::with(['orders', 'orders.products'])->where('ulid', $request->id)->get()->first();
        if ($user != null)
        {
            if ($user->orders != null)
            {
                $orders = $user->orders->toArray();
                Cache::put('orders', $orders, env('CACHE_TTL'));
                Cache::put('user', $user, env('CACHE_TTL'));
                return redirect()->back();
            }
            else
                return redirect()->back()->with('message', 'Заказов данного пользователя не существует.');
        }
        else
            return redirect()->back()->with('message', 'Данного пользователя не существует.');
    }
    public function reviews(Request $request)
    {
        $users = SearchFormPaginateResponseService::paginate('users');
        $reviews = null;
        $user = null;
        $message = null;

        if (Cache::get('reviews') != null)
            $reviews = ManualPaginatorAction::paginate(Cache::get('reviews'));

        if (Cache::get('user') != null)
            $user = Cache::get('user');

        if (Cache::get('message') != null)
            $message = Cache::get('message');

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
        $payments = SearchFormPaginateResponseService::paginate('payments', $request->page ?? 1, 15) ?? Payment::with(['order', 'order.user'])->paginate(15);
        $inputs = [
            new SearchFormInput('name', 'Имя заказчика', 'name', true),
            new SearchFormInput('surname', 'Фамилия заказчика', 'surname', true),
            new SearchFormInput('ulid', 'Id заказчика', 'ulid', false)
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
                Cache::put('reviews', $reviews, env('CACHE_TTL'));
                Cache::put('user', $user, env('CACHE_TTL'));
                return redirect()->back();
            }
            else
                return redirect()->back()->with('message', 'Отзывов данного пользователя не существует.');
        }
        else
            return redirect()->back()->with('message', 'Данного пользователя не существует.');
    }
    public function ban(Request $request)
    {
        $users = SearchFormPaginateResponseService::paginate('users', perPage: 15);
        $message = Cache::get('message');
        $inputs = [
            new SearchFormInput('email', 'Email', 'email', false),
            new SearchFormInput('name', 'Имя', 'name', false),
            new SearchFormInput('surname', 'Фамилия', 'surname', false),
            new SearchFormInput('phone', 'Телефон', 'phone', false)
        ];
        $queryInputs = new SearchFormQueryInput('/admin/users/search', 'admin.users.ban', null);
        return view('admin.users.ban', [
            'users' => $users,
            'inputs' => $inputs,
            'queryInputs' => $queryInputs,
            'message' => $message,
            'admin_id' => Auth::user()->ulid
        ]);
    }
    public function postBan(UserBanRequest $request)
    {
        $validated = $request->validated();

        $admin_id = User::where('ulid', $validated['admin_id'])->get()->first()->ulid;
        if (!key_exists('api_user', $validated))
        {
            $user_id = User::where('ulid', $validated['user_id'])->get()->first()->ulid;
        }
        else if (key_exists('api_user', $validated))
        {
            if ($validated['api_user'])
                $user_id = APIUser::where('api_key', $validated['user_id'])->get()->first()->api_key;
            else
                $user_id = User::where('ulid', $validated['user_id'])->get()->first()->ulid;
        }
        BannedUser::create([
            'user_id' => $user_id,
            'admin_id' => $admin_id,
            'userIp' => $request->ip(),
            'reason' => $validated['reason'],
            'duration' => $validated['duration'],
            'timeType' => $validated['timeType'],
            'bannedAt' => Carbon::now()
        ]);
        return redirect()->route('admin.users.ban');
    }
    public function searchUsers(APIUserSearchRequest $request)
    {
        $validated = $request->validated();
        return (new UserSearchFormService)->search($validated);
    }
    public function searchPayments(Request $request)
    {
        $user = User::with(['orders', 'orders.payment'])->where('ulid', $request->id)->get()->first();
        if ($user != null)
        {
            if ($user->orders !== null)
            {
                $payments = [];

                foreach ($user->orders as $order)
                    array_push($payments, $order->payment);

                if (empty($payments))
                    return redirect()->back()->with('message', 'У данного пользователя нет платежей.');

                Cache::put('payments', $payments, env('CACHE_TTL'));
                Cache::put('user', $user, env('CACHE_TTL'));
                return redirect()->back();
            }
            else
                return redirect()->back()->with('message', 'У данного пользователя нет заказов.');
        }
        else
            return redirect()->back()->with('message', 'Данного пользователя не существует.');
    }
}
