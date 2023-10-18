<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "name" => "required|string",
            "nim" => "required|string|unique:users",
            "email" => "required|string|email:dns|unique:users",
            "password" => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validate->errors()
            ], 400);
        } else {
            $data = $validate->validated();
            $data['password'] = Hash::make($data['password']);

            try {
                User::create([
                    'name' => $data['name'],
                    'nim' => $data['nim'],
                    'email' => $data['email'],
                    'password' => $data['password'],
                ]);
                return response()->json([
                    'status' => 200,
                    'message' => 'Register successfull',
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => 500,
                    'message' => 'Server error',
                ], 500);
            }
        }
    }
}
