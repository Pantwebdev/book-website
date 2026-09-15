<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Models\Category;
use App\Models\Link;

class CustomerAuthController extends Controller
{
    public function loginForm()
    {

        return redirect('/')->with('error', 'Please login from cart page');


    }

    // CustomerAuthController.php में login method में
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // 🔴 ADMIN LOGOUT
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || $user->type != 2) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied. Please use admin login.',
            ], 403);
        }

        // ✅ CUSTOMER GUARD LOGIN
        if (Auth::guard('customer')->attempt([
            'email' => $request->email,
            'password' => $request->password,
        ])) {

            $customer = Auth::guard('customer')->user();

            // Guest cart → customer
            $this->transferGuestCartToUser($customer->id);

            $request->session()->regenerate();

            // ✅ DEBUG: Log the request
            \Log::info('Login request data', [
                'redirect_to_checkout' => $request->has('redirect_to_checkout') ? $request->redirect_to_checkout : 'not set',
                'all_request' => $request->all(),
            ]);

            // ✅ FIX: सही तरीके से check करें
            if ($request->has('redirect_to_checkout') && $request->input('redirect_to_checkout') == '1') {
                // CART PAGE से आया है → Checkout पर redirect करें
                return response()->json([
                    'success' => true,
                    'message' => 'Login successful!',
                    'redirect' => '/customer/checkout', // Checkout page
                ]);
            } else {
                // HEADER से आया है → Page reload करें
                return response()->json([
                    'success' => true,
                    'message' => 'Login successful!',
                    'redirect' => false, // No redirect, just reload
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid email or password',
        ], 401);
    }

    /**
         * Send OTP for signup
         */
    public function sendSignupOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
        ], [
            'email.unique' => 'This email is already registered.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Generate 6-digit OTP
        $otp = rand(100000, 999999);

        // Store OTP in cache for 10 minutes
        \Cache::put('signup_otp_' . $request->email, $otp, now()->addMinutes(10));

        // Send OTP via email
        Mail::send('emails.signup-otp', [
            'otp' => $otp,
        ], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('OTP Verification - Ajhuie Book Store & Photostat Services');
            $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
        });

        return response()->json([
            'success' => true,
            'message' => 'OTP sent to your email!',
        ]);
    }

    /**
     * Verify OTP for signup
     */
    public function verifySignupOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp' => 'required|digits:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $storedOtp = \Cache::get('signup_otp_' . $request->email);

        if (!$storedOtp) {
            return response()->json([
                'success' => false,
                'message' => 'OTP expired. Please request a new one.',
            ], 400);
        }

        if ($storedOtp != $request->otp) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP. Please try again.',
            ], 400);
        }

        // Mark email as verified in cache for 30 minutes
        \Cache::put('email_verified_' . $request->email, true, now()->addMinutes(30));

        return response()->json([
            'success' => true,
            'message' => 'Email verified successfully!',
        ]);
    }



    public function register(Request $request)
    {
        // Check if email is verified
        if (!\Cache::get('email_verified_' . $request->email)) {
            return response()->json([
                'success' => false,
                'message' => 'Email not verified. Please complete OTP verification.',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|digits:10|unique:users,phone',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Password complexity validation
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $request->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password must contain at least 8 characters, one uppercase letter, one lowercase letter, one number and one special character.',
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'type' => 2, // customer type
        ]);

        // Clear OTP cache
        \Cache::forget('signup_otp_' . $request->email);
        \Cache::forget('email_verified_' . $request->email);

        // ✅ Customer guard के साथ login करें
        Auth::guard('customer')->login($user);

        // Cart items को guest से user में transfer करें
        $this->transferGuestCartToUser($user->id);

        $request->session()->regenerate();

        return response()
            ->json([
                'success' => true,
                'message' => 'Registration successful!',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
            ]);
    }


    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Logged out');
    }


    private function transferGuestCartToUser($userId)
    {
        $guestToken = \Cookie::get('guest_token');

        if ($guestToken) {
            // Guest cart items को user से update करें
            \App\Models\CartItem::where('guest_token', $guestToken)
                ->update([
                    'user_id' => $userId,
                    'guest_token' => null,
                ]);
        }
    }
    public function showForgotPasswordForm()
    {
        $navcategories = Category::with(['subcategories.childSubcategories'])
        ->where('status', 1)
        ->get();
        $linkss = Link::where('status', 1)->get();
        return view('auth.customer-forgot-password', compact('navcategories', 'linkss'));
    }

    /**
     * Send password reset link
     */
    public function sendResetLink(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'We could not find a user with that email address.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check if user is customer
        $user = User::where('email', $request->email)
                    ->where('type', 2)
                    ->first();

        if (!$user) {
            return redirect()->back()
                ->with('error', 'This email is not registered as a customer.');
        }

        // Generate token
        $token = Str::random(64);

        // Delete existing tokens
        DB::table('password_resets')->where('email', $request->email)->delete();

        // Insert new token
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now(),
        ]);

        // Send email
        $resetLink = route('customer.password.reset.form', $token);

        // Send email (we'll create the email view later)
        Mail::send('emails.customer-password-reset', [
            'resetLink' => $resetLink,
            'user' => $user,
        ], function ($message) use ($request, $user) {
            $message->to($request->email);
            $message->subject('Password Reset Request - Ajhuie Book Store & Photostat Services');
            $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
        });

        return redirect()->back()
            ->with('success', 'We have emailed your password reset link!');
    }

    /**
     * Show reset password form
     */
    public function showResetPasswordForm($token)
    {
        $passwordReset = DB::table('password_resets')
            ->where('token', $token)
            ->first();

        if (!$passwordReset) {
            return redirect()->route('customer.password.request')
                ->with('error', 'Invalid or expired reset token.');
        }

        // Check if token is expired (24 hours)
        $createdAt = Carbon::parse($passwordReset->created_at);
        // if ($createdAt->diffInHours(Carbon::now()) > 24) {
        //     DB::table('password_resets')->where('token', $token)->delete();
        //     return redirect()->route('customer.password.request')
        //         ->with('error', 'Reset token has expired. Please request a new one.');
        // }
        // Token expired check — 24 hours se 4 minutes karo
        if ($createdAt->diffInMinutes(Carbon::now()) > 4) {  // ✅ diffInHours → diffInMinutes, 24 → 4
            DB::table('password_resets')->where('token', $token)->delete();
            return redirect()->route('customer.password.request')
                ->with('error', 'Reset token has expired. Please request a new one.');
        }



        $navcategories = Category::with(['subcategories.childSubcategories'])
        ->where('status', 1)
        ->get();
        $linkss = Link::where('status', 1)->get();

        return view('auth.customer-reset-password', [
            'token' => $token,
            'expires_at'   => $createdAt->addMinutes(4),
            'email' => $passwordReset->email,
            'navcategories' => $navcategories,
            'linkss' => $linkss,
        ]);
    }

    /**
     * Reset password
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Verify token
        $passwordReset = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$passwordReset) {
            return redirect()->route('customer.password.request')
                ->with('error', 'Invalid reset token.');
        }

        // Update user password
        $user = User::where('email', $request->email)
                    ->where('type', 2)
                    ->first();

        if (!$user) {
            return redirect()->route('customer.password.request')
                ->with('error', 'User not found.');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // Delete token
        DB::table('password_resets')->where('email', $request->email)->delete();

        // Auto login
        // Auto login with correct guard
        Auth::guard('customer')->login($user);

        return redirect('/')
            ->with('success', 'Password has been reset successfully!');
    }
}
