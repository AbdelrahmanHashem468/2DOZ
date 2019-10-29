<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\OauthAccessToken;
use Illuminate\Support\Facades\Route;



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

    public function login(Request $request)
    {   
        $request->request->add([
            'grant_type'=>'password',
            'client_id'=>env('CLINT_ID'),
            'client_secret'=>env('CLINT_SECRET'),
            'username'=>$request->username,
            'password'=>$request->password

        ]);
        $tokenRequest = Request::create(
            env('App_URL').'/oauth/token',
            'post'
        );
        $response = Route::dispatch($tokenRequest);
        return $response;
    }

    public  function logout(Request $request)
    {
        Auth::user()->tokens->each(function($token,$key){
            $token->delete();
        });
        return response()->json('logout is done',200);
    }


}
