<?php

namespace App\Http\Controllers;

use App\Enum\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function login()
    {
        return view('user.auth.login');
    }
    public function postLogin(Request $request)
    {
        if(Auth::viaRemember())
        {
            $request->session()->regenerate();
            return redirect()->intended('/user/profile');
        }
        else
        {
            $user_val = $request->validate([
                'email' => ['min: 5', 'max: 100', 'required', 'email'],
                'password' => ['required', 'min: 10']
            ]);
            $user = User::all()->where('email', $user_val['email'])->first();
            if($user == null)
                return back()->withErrors([
                    'email' => 'Неправильный email.'
                ]);
            if(Hash::check($user_val['password'], $user->password))
            {
                $request->session()->regenerate();
                Auth::login($user, $request->remember_me);
                return redirect()->intended('profile');
            }
            else
            {
                return back()->withErrors([
                    'password' => 'Неправильный пароль.'
                ]);
            }
        }
    }
    public function register()
    {
        return view('user.auth.register');
    }
    public function postRegister(Request $request)
    {
        $user_val = $request->validate([
            'name' => ['min:5', 'max:30', 'required'],
            'surname' => ['min:5', 'max:30'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(10)->numbers()],
            'birthDate' => ['nullable', 'date'],
            'country' => ['min:3', 'max:50', 'required'],
            'city' => ['min:5', 'max:50', 'required'],
            'homeAdress' => ['min:5', 'max:200', 'required'],
            'phone' => ['min:8', 'max:20', 'nullable'],
            'pfp' => ['mime:png,jpg,jpeg', 'nullable']
        ]);
        if($request->pfp != null)
        {
           $pfpPath = Storage::putFile('pfp', $user_val['pfp']);
        }
        else
        {
            $pfpPath = '\\images\\pfp\\default_user.png';
        }
        $user = User::create([
            'name' => $user_val['name'],
            'surname' => $user_val['surname'],
            'email' => $user_val['email'],
            'password' => Hash::make($user_val['password']),
            'country' => $user_val['country'],
            'birthDate' => $user_val['birthDate'],
            'city' => $user_val['city'],
            'homeAdress' => $user_val['homeAdress'],
            'phone' => $user_val['phone'],
            'profilePicture' => $pfpPath,
            'created_at' => Carbon::now(),
            'update_at' => Carbon::now()
        ]);
        $user->save();
        return redirect()->route('login');
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('home');
    }
    public function profile()
    {
        $user = User::find(Auth::id());
        return view('user.profile', ['user' => $user]);
    }
    public function editProfile(Request $request)
    {
        $user_val = $request->validate([
            'name' => ['min:5', 'max:30', 'required'],
            'surname' => ['min:5', 'max:30'],
            'email' => ['required', 'email', 'unique:users,email'],
            'birthDate' => ['nullable', 'date'],
            'country' => ['min:3', 'max:50', 'required'],
            'city' => ['min:5', 'max:50', 'required'],
            'homeAdress' => ['min:5', 'max:200', 'required'],
            'phone' => ['min:8', 'max:20', 'nullable'],
            'pfp' => ['mime:png,jpg,jpeg', 'nullable']
        ]);
        $user = User::find(Auth::id());
        $user->name = $user_val['name'];
        $user->surname = $user_val['surname'];
        $user->email = $user_val['email'];
        $user->birthDate = $user_val['birthDate'];
        $user->country = $user_val['country'];
        $user->city = $user_val['city'];
        $user->homeAdress = $user_val['homeAdress'];
        $user->phone = $user_val['phone'];
        $user->profilePicture = $user_val['pfp'];
        $user->updated_at = Carbon::now();
        $user->save();
        return redirect()->route('profile');
    }
    public function shopCart()
    {
        $products = Cart::session(Auth::id())->getContent();
        return view('user.shopcart', ['products' => $products]);
    }
    public function filterShopCart(Request $request)
    {
        $orders = Order::all()->where('orderStatus', $request->order_status);
        return redirect()->route('/user/shopcart', ['orders' => $orders]);
    }
    public function removeFromCart(Request $request)
    {
        Cart::remove($request->product_id);
        return redirect()->route('shopcart');
    }
    public function editOrder(Order $order)
    {
        return view("user.editorder", ['order' => $order]);
    }
    public function postEditOrder(Request $request)
    {
        $order = Order::find($request->order_id);
        $order->save();
        return redirect()->route('shopCart');
    }
    public function closeOrder(Request $request)
    {
        $order = Order::find($request->order_id);
        $order->orderStatus = OrderStatus::CLOSED;
        $order->delete();
        $order->save();
        return redirect()->route('shopcart');
    }
    public function ordersHistory()
    {
        $orders = Order::onlyTrashed()->get();
        return view('user.ordershistory', ['orders' => $orders]);
    }
}