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

    /*
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
*/

    public function login(Request $request)
    {
        $http = new \GuzzleHttp\Client;
        try
        {
            $response = $http->post('http://127.0.0.1:8000/oauth/token', [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => 2,
                    'client_secret' => '7BnYc7ohuv8nKJVVy6Hato5i9iltZutiOpxPbc3k',
                    'username' => $request->email,
                    'password'=>$request->password,
                ],
            ]);
            return $response->getBody();
        }

        catch(\GuzzleHttp\Exception\BadResponseException $e)
        {   
            if($e->getcode()==400)
                return response()->json('invalid request .enter email and password ',$e->getcode());
            elseif($e->getcode()==401)
                return response()->json('your credaintials incorrect .enter email and password ',$e->getcode());
        }
    }
}
