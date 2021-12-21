<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class NewPasswordController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'current_password' => 'required|min:8|max:255|string',
            'new_password' => 'required|min:8|max:255|string|confirmed'
        ]);

        $user = Auth::user();


        if(!$user || !Hash::check($credentials['current_password'], $user->password))
        {
            return response([
                'message' => 'Wrong password'
            ]);
        }

        // Password::reset()

        $user->forceFill([
            'password' => bcrypt($credentials['new_password'])
        ])->save();
        
        return response([
            'message' => 'password modified with success'
        ]);
    }

}
