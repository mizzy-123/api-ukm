<?php

namespace App\Http\Controllers;

use App\Http\Resources\AllUser;
use App\Http\Resources\ShowAdminOrganization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    public function index()
    {
        if (Gate::check("superAdmin")) {
            $dataUser = User::with([
                'role:name',
                'organization:name_organization,foto'
            ])->whereHas('role', function ($query) {
                $query->whereNotIn('roles.id', [1]);
            })->get();

            return response()->json([
                'status' => 200,
                'data' => AllUser::collection($dataUser),
            ]);
        } elseif (Gate::check("admin")) {
            $dataUser = User::with(['role:name', 'organization:name_organization,foto'])->whereHas('role', function ($query) {
                $query->whereNotIn('roles.id', [1, 2]);
            })->get();
            return response()->json([
                'status' => 200,
                'data' => AllUser::collection($dataUser),
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthorization',
            ], 401);
        }
    }

    public function showRole()
    {
        return response()->json([
            'status' => 200,
            'role' => Auth::user()->role,
        ]);
    }

    public function show_admin_organization()
    {
        return response()->json([
            'status' => 200,
            'organization' => new ShowAdminOrganization(Auth::user()->organization->first()),
        ]);
    }
}
