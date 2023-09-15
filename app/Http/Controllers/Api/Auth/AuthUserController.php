<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Api\Auth\LoginUserRequest;
use Illuminate\Http\Response;
use App\Models\User;
use Auth;
use Constants\Tokens;
use Constants\ApiMessagesConstants\AuthMessages;

class AuthUserController extends Controller
{
    public function login(LoginUserRequest $request) {
        
        try {
            $request->validated();

        if(!Auth::attempt($request->only(['email', 'password']))){
            return response()->json([
                'status'=>false,
                 'message'=>AuthMessages::FALSE_AUTH_DATA], Response::HTTP_UNAUTHORIZED);
        };

        $user = User::where('email', $request->email)->first();
        $user->tokens()->delete();

            return response()->json([
                'status' => true,
                'message' => AuthMessages::SUCCESSFUL_AUTH,
                'user'=> ['name'=> $user->name],
                'token' => $user->createToken(Tokens::SANCTUM_TOKEN)->plainTextToken
            ], Response::HTTP_OK);
        }

        catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    public function logout(Request $request) {
        
        auth()->user()->tokens()->delete();
        return response()->json([
            'status'=>true,
            'message'=>AuthMessages::SUCCESSFUL_LOGOUT,
        ], Response::HTTP_OK);
    }
}
