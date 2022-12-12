<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //user register
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' =>'required',
            'email' => 'required|email',
            'password' => 'required',
            'password_confirm' => 'required|same:password'
        ]);

        if($validator->fails())
        {
            return response()->json([
                'status_code'=>400,
                'message'=>'Validation Error.',
            ]);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email =$request->email;
        $user->password=bcrypt($request->password);

        if($user->save())
        {
            return response()->json([
                'status_code' => 200,
                'message' => 'User created successfully!'
            ]);
        }

        else
        {
            return response()->json([
                'status_code' => 400,
                'message' => 'User not created!'
            ]);
        }
    }

    //user login
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required'

        ]);

        if($validator->fails())
        {
            return $validator->errors();
        }

        $credentials = request(['email','password']);

        if(!Auth::attempt($credentials))
        {
            return response()->json([
                'status_code' =>500,
                'message' =>'Unauthorized'
            ]);
        }

        $user = User::where('email',$request->email)->first();

        $tokenResult = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'status_code' =>200,
            'message' =>'Success.',
            'token' => $tokenResult,
            'user' => $user
        ]);
    }

    //authenticated user
    public function authenticated_user()
    {
        $authenticated_user = Auth::user();

        if(!empty($authenticated_user)){
            return response()->json([
                'status_code' => 200,
                'message' => 'Success.',
                'user' => $authenticated_user
            ]);
        }
        return response()->json([
            'status_code' =>500,
            'message' =>'Unauthorized'
        ]);
    }

    //all user list
    public function get_user_list()
    {
        $user_list = User::get(['id','name','email']);

        //~ Check Availability of data
        if(count($user_list)>0){
            return response()->json([
                'status_code' => 200,
                'message' => 'Success.',
                'count_data' => count($user_list),
                'user_list' => $user_list
            ]);
        }
        return response()->json([
            'status_code' =>204,
            'message' =>'Data not found!'
        ]);
    }

    //user logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status_code' =>200,
            'message' =>'Token deleted successfull!'
        ]);
    }

}
