<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\OauthAccessToken;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if($validate->fails())
            return response()->json(['error'=>$validate->error()],401);

        $data = $request->all();
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);
        $success['token'] = $user->createToken('fristToken')->accessToken;
        $success['name'] = $user->name;
        $success['email'] = $user->email;

        return response()->json(['success'=>$success],200);
    }

    public function login()
    {
        if(Auth::attempt(['email' => request('email'),'password' => request('password')]))
        {
            $user = Auth::user();
            $success['token'] = $user->createToken('myapp')->accessToken;
            $success['name'] = $user->name;
            return response()->json(['success'=>$success,'user'=>$user],200); 
        }

        return response()->json(['error'=>'Unauthorised'],401);
    
    }

}
