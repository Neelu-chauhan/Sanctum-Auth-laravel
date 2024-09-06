<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthCoontroller extends BaseController
{
    public function register(Request $request){
        $validator =Validator::make($request->all(),[
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required',
            'confirm_password'=>'required|same:password'
        ]);
        if($validator->fails()){
            return $this->SendError($validator->errors()->all());
        }
        $user =new User;
        $user->name =$request->name;
        $user->email =$request->email;
        $user->password =Hash::make($request->password);
        $user->save();
        return $this->SendResponse($user,'User created successfully'); 
    }
    public function login(Request $request){
        $validator =Validator::make($request->all(),[
            'email'=>'required|email',
            'password'=>'required'
        ]);
        if($validator->fails()){
            return $this->SendError($validator->errors()->all());
        }
        $userExist =User::where('email',$request->email)->first();
        if(!$userExist){
            return $this->SendError('User Not Found');
        }
        else if(Auth::attempt(['email'=>$userExist->password,'password'=>$request->password]));
            $user = User::where('email',$request->email)->select('name','email')->first();
            $token_time = 60*60*24;
            $token = $userExist->createToken('MyApp')->plainTextToken;
            $success['token']=$token;
            $success['user']=$user;
            $success['token_time']=$token_time;
            return $this->SendResponse($success,'Login Successfull');
    }

    public function Getdata(){
        $user =User::all();
        return $this->SendResponse($user,'User Data');
    }
}
