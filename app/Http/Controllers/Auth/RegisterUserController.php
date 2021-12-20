<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterUserController extends Controller
{
   
    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'pseudo' => 'required|string|unique:users',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8|confirmed'
        ]);

        $user = User::create([
            'pseudo' => $credentials['pseudo'],
            'email' => $credentials['email'],
            'password' => bcrypt($credentials['password'])
        ]);

        $token = $user->createToken('user-porfolio-token')->plainTextToken;

        Auth::login($user);

        return response([
            'user' => $user,
            'token' => $token
        ]);
    }

    /**
     * Display the curent user.
     *
     * @return \Illuminate\Http\Response
     */
    public function user()
    {
        $user = Auth::user();
        
        return response([
            'user' => $user
        ]);
    }

}
