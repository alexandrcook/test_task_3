<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\{User, UserAddress};
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\Facades\{Hash, Validator};

class CustomRegisterController extends Controller
{
    public function register(Request $request) : JsonResponse
    {
        //todo: use UserRegisterRequest file for request validation
        $validator = Validator::make($request->all(),[
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'errors' => $validator->errors()
            ]);
        }

        $user = User::create(array_merge(
            $request->all(),
            ['password' => Hash::make(request()->get('password'))]
        ));

        if($request->has('address') && $request->get('address')
            || $request->has('city') && $request->get('city')
            || $request->has('state') && $request->get('state')
            || $request->has('zipcode') && $request->get('zipcode')
        ){
            UserAddress::create(
                array_merge(
                    ['user_id' => $user->id],
                    $request->only(['address', 'city', 'state', 'zipcode'])
                ));
        }

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'data' => [
                'user_id' => $user->id,
                'is_admin' => $user->tokenCan('admin'),
                'api_token' => $token
            ]
        ]);
    }
}
