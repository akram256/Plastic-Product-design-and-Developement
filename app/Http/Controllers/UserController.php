<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource as UserResource;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function getusers(){
        $users = User::get();
        $response = UserResource::collection($users);
        if(count($users) <= 0){
            return response()->json([
                "status" => false,
                "message" => "No user found"
            ])->setStatusCode(404);
        }
        return response()->json([
            "status" => true,
            "message" => "users retrieved successfully",
            "users" => $response
        ])->setStatusCode(200);
    }

    public function singleuser($email){
        $user = User::get()->where('email', $email);
        if(count($user) <= 0){
            return response()->json([
                "status" => false,
                "message" => "User not found"
            ])->setStatusCode(404);
        }
        return response()->json([
            "status" => true,
            "message" => "user retrieved successfully",
            "user" => UserResource::collection($user)
        ])->setStatusCode(200);
    }

    public function signup(Request $req){
        $request = $req->input();
        $validator = Validator::make($req->all(), [
            "email" => "required|email|unique:users",
            "fullname" => "required",
            "contact" => "required|regex:/[0-9]{10}/",
            "students_number" => "required",
            "field_of_study" => "required",
            "institution" => "required",
            "usertype" => "required",
            "password" => [
                "required",
                "string",
                "min:6",
                "regex:/[a-z]/",
                "regex:/[A-Z]/",
                "regex:/[0-1]/"
            ],
        ]);

        if($validator->fails()){
            return response()->json([
                "status" => false,
                "message" => $validator->messages()
            ])->setStatusCode(400);
        }
        $request['password'] = Hash::make($request['password']);
        User::create($request);
        
        return response()->json([
            "status" => true,
            "message" => "user created successfully",
            "data" => $request
        ])->setStatusCode(200);
    }

    public function login(Request $req){
        $user = User::get()->where('email', $req['email'])->first();    
        if(!is_null($user)){
            if(Hash::check($req['password'], $user->password)){
                $status = true;
                $message = 'user retrieved successfully';
                $code = 200;
                $user = $user;
            }else{
                $code = 401;
                $status = false;
                $message = 'password is wrong';
                $user = [];
            }

        }else{
            $status = false;
            $message = 'User not found';
            $code = 404;
            $user = [];
        }
        return response()->json([
            "status" => $status,
            "message" => $message,
            "user" => $user
        ])->setStatusCode($code);
    }

    public function passwordChange(Request $req){
        $user = User::get()->where('email', $req['email'])->first();
        
        if(!is_null($user)){
            $req['password'] = Hash::make($req['password']);
            $user->password = $req['password'];
            $user->save();
            $code = 200;
            $message = "password changed successfully";
            $status = true;
        }else{
            $status = false;
            $message = "User not found";
            $code = 404;
        }

        return response()->json([
            "status" => $status,
            "message" => $message
        ])->setStatusCode($code);
    }
}
