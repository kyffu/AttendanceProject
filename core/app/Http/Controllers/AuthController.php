<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Alert;
use App\Models\Roles;
use Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index(){
        if (!Auth::check()) {
            return view('auth.index');
        }
        else{
            return redirect()->route('attendance.index');
        }
    }

    public function login(Request $request){
        $rules =[
            'email' => 'required|string',
            'password' => 'required|string'
        ];

        $messages = [
            'email.required' => 'Email must be filled!',
            'password.required' => 'Password must be filled',
            'email.string' => 'Invalid email',
            'password.string' => 'Invalid Password'
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            Alert::error('Login Failed', $validator->errors()->all());
            return redirect()->back();
        }
        try{
            $data = [
                'email' => $request->email, 
                'password' => $request->password
            ];
            Auth::attempt($data);
            if (Auth::check()) { 
                //Login Success
                // Alert::success('Login Successfully', 'Welcome, '.Auth::user()->name."" );
                // return redirect()->route('attendance.index');
                $check_role = Roles::where('id', Auth::user()->role_id)->firstOrFail();

                if($check_role->slug == 'admin'){
                    return redirect()->route('user.index');
                } 

                if($check_role->slug == 'pm'){
                    return redirect()->route('project.index');
                } 
                
                else {
                    return redirect()->route('attendance.index');
                }

                

                
            } else {
                //Login Fail
                Alert::error('Login Failed', 'Incorrect Username or Password');
                return redirect()->route('login');
            }
        }
        catch(\Exception $e){
            Alert::error('Login Failed', $e->getMessage());
            return redirect()->route('login');
        }
    }

    public function logout(){
        try{
            Auth::logout();
            Alert::success('Logout Successfully', 'See you again :)');
            return redirect()->route('login');
        }
        catch(\Exception $e){
            Alert::error('Logout Failed', $e->getMessage());
            return redirect()->back();
        }
    }
}
