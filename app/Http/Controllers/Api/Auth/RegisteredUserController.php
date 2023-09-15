<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Api\Auth\RegisteredUserRequest;
use Illuminate\Http\Response;
use App\Models\User;
use Hash;
use Constants\Tokens;
use Constants\ApiMessagesConstants\RegistrationMessages;

class RegisteredUserController extends Controller
{
    public function store(RegisteredUserRequest $request) {

        try {
            $request->validated();
        
            $user = User::create([
                'name'=>$request->name,
                'email'=>$request->email,
                'password'=>Hash::make($request->password)
            ]);
    
            return response()->json([
                'status'=>true,
                'message'=>RegistrationMessages::SUCCESSFUL_REGISTRATION,
                'user'=> ['name'=> $user->name],
                'token'=>$user->createToken(Tokens::SANCTUM_TOKEN)->plainTextToken,
            ], Response::HTTP_CREATED);
    
        }

        catch (\Throwable $th) {
            return response()->json([
                'status'=>false,
                'message'=>$th->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
 
    }
}
