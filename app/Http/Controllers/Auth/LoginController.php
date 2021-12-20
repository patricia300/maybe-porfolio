<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class LoginController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($this->checkTooManyFailedAttempts())
        {
            return response([
                'error' => true,
                'message' => 'Too many attempts with the email '.request('email').' ..please wait two minutes later'
            ]);
        }

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8'
        ]);

        $user = User::where('email',$credentials['email'])->first();

        if(!$user || !Auth::attempt($credentials))
        {
            RateLimiter::hit($this->throttleKey(), 120);

            return response([
                'message' => 'Wrong Email or password'
            ],401);
        }

        RateLimiter::clear($this->throttleKey());
        $token = $user->createToken('user-porfolio-token')->plainTextToken;

        return response([
            'message' => 'Login successful',
            'token' => $token
        ]);

    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    public function throttleKey()
    {
        return Str::lower(request('email')) . '|' . request()->ip();
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @return void
     */
    public function checkTooManyFailedAttempts()
    {
        return RateLimiter::tooManyAttempts($this->throttleKey(), 3);    
    }


}
