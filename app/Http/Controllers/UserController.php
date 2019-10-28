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
            'client_id'=>2,
            'client_secret'=>'wj3ZLkm9tbpE7vWAMvnQHhiq4KznRNAeqRcaFnTV',
            'username'=>$request->email,
            'password'=>$request->password

        ]);
        $tokenRequest = Request::create(
            env('App_URL').'/oauth/token',
            'post'
        );
        $response = Route::dispatch($tokenRequest);
        return $response;
        //return app()->handle($tokenRequest);
    }



}
