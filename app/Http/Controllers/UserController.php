<?php

namespace App\Http\Controllers;

use App\Http\Resources\AllUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    public function index()
    {
        if (Gate::check("superAdmin")) {
            $dataUser = User::with(['role:name', 'organization:name_organization,foto'])->whereNotIn('id', [1])->get();
            return response()->json([
                'status' => 200,
                'data' => AllUser::collection($dataUser),
            ]);
        } elseif (Gate::check("admin")) {
            $dataUser = User::with(['role:name', 'organization:name_organization,foto'])->whereNotIn('id', [1, 2])->get();
            return response()->json([
                'status' => 200,
                'data' => AllUser::collection($dataUser),
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthorization',
            ], 404);
        }
    }
}
