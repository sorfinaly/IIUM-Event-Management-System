<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthManager extends Controller
{
    function login(){
        return view('login');
    }

    function register(){
        return view('registration');
    }

    function loginPost(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email','password');
        if(Auth::attempt($credentials)){
            $user = Auth::user();
            session(['loginId' => $user->id]);
            $successMessage = "Welcome back, " . $user->name . "! You have successfully logged in.";
            // store users id as session[loginId] and route to /
            return redirect()->intended(route('home'))->with(["success" => $successMessage]);
        }
        // route to login with error
        return redirect(route('login'))->with("error", "Login details are not valid");

    }

    function registrationPost(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);

        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['password'] = Hash::make($request->password);

        $user = User::create($data);
        if(!$user){
            return redirect(route('registration'))->with(["error" => "Registration failed, try again."]);  //first is key, second is message error that will be displayed
        }
        return redirect(route('login'))->with(["success" => "Registration success, Please login to access the website"]);  //first is key, second is message error that will be displayed
    }

    function logout(){
        session()->forget('loginId');
        Auth::logout();
        return redirect(route('home'));
    }
}
