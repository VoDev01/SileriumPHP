<?php

namespace App\Http\Controllers;

use App\Enum\OrderStatus;
use App\Facades\ValidateEmail;
use App\Facades\ValidatePhone;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password as FacadesPassword;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function login()
    {
        return view('user.auth.login');
    }
    public function postLogin(Request $request)
    {
        if (Auth::viaRemember()) {
            $request->session()->regenerate();
            return redirect()->intended('/user/profile');
        } 
        else 
        {
            $validator = Validator::make($request->all(), [
                'email' => ['required', 'email', 'exists:users', 'min: 5', 'max: 100'],
                'password' => ['required', 'min: 10']
            ]);
            if($validator->fails())
            {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 400);
            }
            $validated = $validator->validated();
            $user = User::where('email', $validated['email'])->first();
            if (Hash::check($validated['password'], $user->password)) {
                $request->session()->regenerate();
                Auth::login($user, $request->remember_me);
                return response()->json(['success' => true, 'redirect' => '/user/profile'], 200);
            } 
            else 
            {
                return response()->json(['success' => false, 'errors' => 'Пароль не совпадает'], 400);
            }
        }
    }
    public function forgotPassword()
    {
        return view('user.auth.forgotpassword');
    }
    public function postForgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = FacadesPassword::sendResetLink(
            $request->only('email')
        );

        return $status === FacadesPassword::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }
    public function resetPassword(string $token)
    {
        return view('user.auth.resetpassword', ['token' => $token]);
    }
    public function postResetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Password::min(10)->numbers()]
        ]);

        $status = FacadesPassword::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === FacadesPassword::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
    public function verifyEmail()
    {
        return view('user.auth.verifyemail');
    }
    public function emailVerificationHandler(EmailVerificationRequest $request)
    {
        $request->fulfill();
        return redirect()->route('profile');
    }
    public function resendEmailVerification(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return back();
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
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(10)->numbers()],
            'birthDate' => ['nullable', 'date'],
            'country' => ['min:3', 'max:50', 'required'],
            'city' => ['min:5', 'max:50', 'required'],
            'homeAdress' => ['min:5', 'max:200', 'required'],
            'phone' => ['min:8', 'max:20', 'nullable'],
            'pfp' => ['mime:png,jpg,jpeg', 'nullable']
        ]);
        $apiValidationMessage = "";
        if (!ValidateEmail::validate($user_val['email'], $apiValidationMessage)) {
            return back()->withErrors(['email' => $apiValidationMessage]);
        } else if (!ValidatePhone::validate($user_val['phone'], $apiValidationMessage)) {
            return back()->withErrors(['phone' => $apiValidationMessage]);
        }
        if ($request->pfp != null) {
            $pfpPath = Storage::putFile('pfp', $user_val['pfp']);
        } else {
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
    public function editProfile()
    {
        $user = User::find(Auth::id());
        return view('user.editprofile', ['user' => $user]);
    }
    public function postEditProfile(Request $request)
    {
        $user_val = $request->validate([
            'name' => ['min:5', 'max:30', 'required'],
            'surname' => ['min:5', 'max:30'],
            'email' => ['required', 'email', 'unique:users'],
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
    public function cart()
    {
        $products = Cart::session(Auth::id())->getContent();
        return view('user.cart', ['products' => $products]);
    }
    public function changeAmount(Request $request)
    {
        $amount = $request->amount;
        if ($request->amount_change == "up") {
            $amount++;
        } else {
            $amount--;
        }
        $cartAmount = Cart::session(Auth::id())->get($request->product_id)->quantity;
        $totalAmount = $amount - $cartAmount;
        Cart::session(Auth::id())->update($request->product_id, array(
            'quantity' => $totalAmount
        ));
        return redirect()->route('cart');
    }
    public function filterCart(Request $request)
    {
        $orders = Order::all()->where('orderStatus', $request->order_status);
        return redirect()->route('/user/cart', ['orders' => $orders]);
    }
    public function removeFromCart(Request $request)
    {
        Cart::session(Auth::id())->remove($request->product_id);
        return redirect()->route('cart');
    }
    public function editOrder(Order $order)
    {
        return view("user.editorder", ['order' => $order]);
    }
    public function postEditOrder(Request $request)
    {
        $order = Order::find($request->order_id);
        $order->save();
        return redirect()->route('cart');
    }
    public function closeOrder(Request $request)
    {
        $order = Order::find($request->order_id);
        $order->orderStatus = OrderStatus::CLOSED;
        $order->delete();
        $order->save();
        return redirect()->route('cart');
    }
    public function ordersHistory()
    {
        $orders = Order::onlyTrashed()->get();
        return view('user.ordershistory', ['orders' => $orders]);
    }
    public function userReviews()
    {
        $reviews = Review::with('images')->with('user')->with('product')->where('user_id', Auth::id())->paginate(5);
        return view('user.review.userreviews', ['reviews' => $reviews]);
    }
    public function review(Product $product)
    {
        return view('user.review.reviewproduct', ['product' => $product]);
    }
    public function postReview(Request $request)
    {
        if ($request->review_images != null)
            $validator = Validator::make($request->all(), [
                'title' => ['required', 'min:5', 'max:40'],
                'pros' => ['required', 'min:5', 'max:1500'],
                'cons' => ['required', 'min:5', 'max:1500'],
                'comment' => ['min:5', 'max:1500', 'nullable'],
                'rating' => ['required'],
                'review_images' => ['array', 'max:5'],
                'review_images.*' => [File::image()->min('1kb')->max('15mb')->dimensions(Rule::dimensions()->maxWidth(1500)->maxHeight(1500))]
            ]);
        else
            $validator = Validator::make($request->all(), [
                'title' => ['required', 'min:5', 'max:40'],
                'pros' => ['required', 'min:5', 'max:1500'],
                'cons' => ['required', 'min:5', 'max:1500'],
                'comment' => ['min:5', 'max:1500', 'nullable'],
                'rating' => ['required']
            ]);
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        $validated = $validator->validated();
        $review = Review::create([
            'title' => $validated['title'],
            'pros' => $validated['pros'],
            'cons' => $validated['cons'],
            'comment' => $validated['comment'],
            'rating' => $validated['rating'],
            'product_id' => $request->product_id,
            'user_id' => Auth::id()
        ]);
        if ($request->review_images != null) {
            for ($i = 0; $i < $validated['review_images']->count(); $i++) {
                $review->images()->create([
                    'imagePath' => $validated['review_images'][$i],
                    'review_id' => $review->id
                ]);
            }
        }
        $review->save();
        return redirect()->route('userReviews');
    }
    public function editReview(Review $review)
    {
        return view('user.review.reviewproduct', ['review' => $review, 'product' => $review->product]);
    }
    public function postEditReview(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'min:5', 'max:40'],
            'pros' => ['required', 'min:5', 'max:1500'],
            'cons' => ['required', 'min:5', 'max:1500'],
            'comment' => ['min:5', 'max:1500', 'nullable'],
            'review_images' => ['array', 'max:5', 'nullable'],
            'review_images.*' => File::image()->min('1kb')->max('15mb')->dimensions(Rule::dimensions()->maxWidth(1500)->maxHeight(1500))
        ]);
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        $validated = $validator->validated();
        Review::find($request->review_id)->update([
            'title' => $validated['title'],
            'pros' => $validated['pros'],
            'cons' => $validated['cons'],
            'comment' => $validated['comment'],
            'review_images' => $validated['review_images'],
            'product_id' => $request->product_id,
            'user_id' => Auth::id()
        ]);
        return redirect()->route('userReviews');
    }
    public function deleteReview(Request $request)
    {
        Review::destroy($request->review_id);
        return redirect()->route('userReviews');
    }
}
