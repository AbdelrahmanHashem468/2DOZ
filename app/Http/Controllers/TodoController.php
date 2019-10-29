<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Todo;
use Illuminate\Support\Facades\Validator;
use Auth;
class TodoController extends Controller
{
    public function index()
    {
        $todos = Todo::all();
        return response()->json($todos,200);
    }

    public function show($id)
    {
        $todo = Todo::find($id);

        if(is_null($todo))
            return response()->json(["message"=>'Record Not Found'],404);
        
        return response()->json($todo,200);
    }

    public function store(Request $request)
    {
       /* $validate = Validator::make($request->all(),[
            'details' => 'required|min:9',
            'user_id' => 'required',
            'deadline' => 'required',
        ]);

        if($validate->fails())
            return response()->json(['error'=>$validate->error()],401);*/

        $todo = Todo::create([
            'details' => $request['details'],
            'user_id' => Auth::user()->id,
            'deadline' => '2019-10-09'
        ]);

        return response()->json($todo,200);
    }
}
