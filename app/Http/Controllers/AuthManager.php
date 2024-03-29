<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
class AuthManager extends Controller
{
    function ends_with_any($haystack, $needles) {
        foreach ($needles as $needle) {
            if (str_ends_with($haystack, $needle)) {
                return true;
            }
        }
        return false;
    }

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
        $remember = $request->has('remember');


        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            session(['loginId' => $user->id]);
            $successMessage = "Welcome back, " . $user->name . "! You have successfully logged in.";

            return redirect()->intended(route('home'))->with(["success" => $successMessage]);
        }

        return redirect(route('login'))->with("error", "Login details are not valid");


    }

    function registrationPost(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'regex:/^[a-zA-Z\s]*$/'],
            'email' => ['required', 'email', 'unique:users', function ($attribute, $value, $fail) {
                if (!$this->ends_with_any($value, ['@iium.edu.my', '@live.iium.edu.my'])) {
                    $fail('The '.$attribute.' must end with @iium.edu.my or @live.iium.edu.my.');
                }
            }],
            'password' => 'required'
        ], [
            'name.regex' => 'The name may only contain letters and spaces.',
        ]);

        if ($validator->fails()) {
            return redirect(route('registration'))->withErrors($validator)->withInput();
        }

        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['password'] = Hash::make($request->password);

        $user = User::create($data);
        if(!$user){
            return redirect(route('registration'))->with(["error" => "Registration failed, try again."]);//first is key, second is message error that will be displayed
        }
        return redirect(route('login'))->with(["success" => "Registration success, Please login to access the website"]);//first is key, second is message error that will be displayed
    }

    function logout(){
        session()->forget('loginId');
        Auth::logout();
        return redirect(route('home'));
    }

 function changePassword(Request $request){
    $validator = Validator::make($request->all(), [
        'currentPassword' => 'required',
        'newPassword' => 'required|confirmed',
    ]);

    $user = Auth::user();

    if (!Hash::check($request->currentPassword, $user->password)) {
        return view('profile', ['user' => $user, 'currentPasswordError' => 'Current password is incorrect']);
    }
    if ($validator->fails()) {
        return view('profile', ['user' => $user, 'passwordErrors' => $validator->errors()]);
    }

    $user->password = Hash::make($request->newPassword);
    $user->save();

    return view('profile', ['user' => $user, 'passwordSuccess' => 'Password changed successfully']);
}
    function showProfile(){
        $user = Auth::user(); // Get the currently authenticated user
        return view('profile', ['user' => $user]);
    }

    function changeProfile(Request $request){
        $validator = Validator::make($request->all(), [
            'currentName' => 'required',
            'currentEmail' => 'required',
            'currentPhone' => 'filled',
            'profile_photo' => 'image|max:2048', // Validate the profile picture
        ]);

        $user = Auth::user();

        if ($validator->fails()) {
            return view('profile', ['user' => $user, 'profileErrors' => $validator->errors()]);
        }

        // Handle the profile picture upload
        if ($request->hasFile('profile_photo')) {
            // Delete the old picture
            if ($user->profile_photo_path) {
                unlink(public_path($user->profile_photo_path));
            }

            // Store the new picture and update the user model
            $path = $request->file('profile_photo')->store('profile_photo', 'public');
            $user->profile_photo_path = 'storage/' . $path;
        }


        $user->name = $request->currentName;
        $user->email = $request->currentEmail;
        $user->phone = $request->currentPhone;

        $user->save();

        return view('profile', ['user' => $user, 'profileSuccess' => 'Profile Updated']);
    }


}
