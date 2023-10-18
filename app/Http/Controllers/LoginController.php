<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'account' => 'required',
            'password' => 'required',
        ]);

        try {
            if ($validate->fails()) {
                return response()->json([
                    'status' => 400,
                    'message' => $validate->errors()
                ], 400);
            } else {
                $user = User::where('email', $request->account)->orWhere('nim', $request->account)->first();

                if (!$user || !Hash::check($request->password, $user->password)) {
                    return response()->json([
                        'status' => 400,
                        'message' => 'password or email and nim is wrong',
                    ]);
                }

                return response()->json([
                    'status' => 200,
                    'message' => 'login success',
                    'token' => $user->createToken($request->ip())->plainTextToken
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => $th,
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Logout successfull'
        ]);
    }
}
