<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Hashing\BcryptHasher;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Admin;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function reg(Request $request){
        $name = $request->input('name');
        $phone = $request->input('phone');
        $email = $request->input('email');
        $pass = $request->input('password');
        $password = (new BcryptHasher)->make($request->input('password'));

        $admin = new Admin([
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'password' => $password,
            'api_token' => Str::random(60),
        ]);

        $admin->save();
        return response()->json(['status' => 'success', 'message' => 'You are registered'], 200);
    }

    public function login(Request $request){

        $admin = Admin::where('email', $request->input('email'))->first();

        if(!$admin) {
            return response()->json(['status' => 'error', 'message' => 'User not found'], 401);
        }

        $pass = $request->input('password');
        if(password_verify ( $pass, $admin->password )){
            $admin->api_token = Str::random(60);
            $admin->save();
            return response()->json(['status' => 'success', 'message' => 'Logged in succesfully'], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Incorrect password'], 401);
    }

    //
}
