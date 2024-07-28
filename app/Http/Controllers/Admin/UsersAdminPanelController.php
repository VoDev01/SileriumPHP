<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Services\ManualPaginatorService;

class UsersAdminPanelController extends Controller
{
    private ManualPaginatorService $paginator;
    public function __construct(ManualPaginatorService $paginator)
    {
        $this->paginator = $paginator;
    }
    public function index()
    {
        $users = User::paginate(15);
        return view('admin.users.index', ['users' => $users]);
    }
    public function roles() 
    {
        $users = User::with('roles:role')->paginate(5);
        $roles = Role::all();
        return view('admin.users.roles', ['users' => $users, 'roles' => $roles]);
    }
    public function orders(int $page = 1)
    {
        if(!session('user'))
        {
            return view('admin.users.orders', ['userInfoReceived' => false]);
        }
        else
        {
            $user = session('user');
            if(!array_key_exists('orders', $user))
            {
                session()->forget('user');
                return view('admin.users.orders', ['userInfoReceived' => false]);
            }
            $userPaginatedOrders = $this->paginator->paginate($user['orders'], 5, $page, ['path' => '/admin/users/orders']);
            return view('admin.users.orders', ['userInfoReceived' => true, 'user' => $user, 'userPaginatedOrders' => $userPaginatedOrders]);
        }
    }
    public function reviews(int $page = 1)
    {
        if(!session('user'))
        {
            return view('admin.users.reviews', ['userInfoReceived' => false]);
        }
        else
        {
            $user = session('user');
            if(!array_key_exists('reviews', $user))
            {
                session()->forget('user');
                return view('admin.users.reviews', ['userInfoReceived' => false]);
            }
            $userPaginatedReviews = $this->paginator->paginate($user['reviews'], 5, $page, ['path' => '/admin/users/reviews']);
            return view('admin.users.reviews', ['userInfoReceived' => true, 'user' => $user, 'userPaginatedReviews' => $userPaginatedReviews]);
        }
    }
    public function findUsers(Request $request)
    {
        $params = $request->load_with . "/" . $request->email;
        if($request->name != null)
            $params = $params . "/" . $request->name; 
        if($request->surname != null)
            $params = $params . "/" . $request->surname;
        if($request->id != null)
            $params = $params . "/" . $request->id;
        if($request->phone != null)
            $params = $params . "/" . $request->phone;
        $response = Http::get('/api/v1/users/find/' .  $params)['users'];
        if($request->ajax())
        {
            return response()->json(['users' => $response]);
        }
        else
        {
            session(['user' => $response[0]]);
            return redirect()->route($request->redirect);
        }
    }
    public function postUserSearch(Request $request)
    {
        return redirect()->action([UsersAdminPanelController::class, 'findUsers'], 
        [
            'load_with' => $request->load_with, 
            'email' => $request->email, 
            'name' => $request->name,
            'surname' => $request->surname,
            'id' => $request->id,
            'phone' => $request->phone,
            'redirect' => $request->redirect
        ]);
    }
}
