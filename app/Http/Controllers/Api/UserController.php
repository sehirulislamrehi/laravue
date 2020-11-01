<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $email = $request->email;
        $user = User::where('email', $email)->first();

        if (Hash::check($request->password, $user->password)) :
            if ($user) :  
                $token = Str::random(80);
                $user->api_token = $token;
                $user->save();
                return response()->json(['token' => $token, 'user'=>$user], 200);
            endif;
        // if( Auth::user() ):
        //     Auth::user()->api_token = Str::random(80);
        //     Auth::user()->save();
        //     return response()->json(['token'=>$token], 200);
        // endif;
        endif;


        return response()->json(['status' => 'User email or password wrong'], 403);
    }

    public function register(Request $request)
    {

        $validationData = $request->validate([
            'name' => 'required|unique:users,name',
            'email' => 'required|unique:users,email',
            'password' => 'required|confirmed|min:3',
        ]);
        $user = new User();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        if ($user->save()) :
            return response()->json(['status', 'Registration successfully done'], 200);
        else :
            return response()->json(['status', 'Registration Failed'], 403);
        endif;
    }

    public function verify(Request $request){
        return $request->user()->only('name','email');
    }
}
