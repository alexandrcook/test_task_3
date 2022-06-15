<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\Facades\{Auth, Validator};

class CustomLoginController extends Controller
{
    public function login(Request $request) : JsonResponse
    {
        //todo: use UserLoginRequest file for request validation
        $validator = Validator::make($request->all(),[
            'email' => 'required|email|exists:users,email',
            'password' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'errors' => $validator->errors()
            ]);
        }

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            $token = $user->createToken('api_token', ($user->is_admin ? ['admin'] : []));

            return response()->json([
                'data' => [
                    'user_id' => $user->id,
                    'is_admin' => !!$user->is_admin,
                    'api_token' => $token->plainTextToken
                ]
            ]);
        }
        else{
            return response()->json([
                'errors' => $validator->errors()
            ]);
        }
    }
}
