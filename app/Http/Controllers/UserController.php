<?php

namespace App\Http\Controllers;

use App\Http\Resources\AllUser;
use App\Http\Resources\ShowAdminOrganization;
use App\Http\Resources\ShowMyroleAndOrganizationResources;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;

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
            $organization_id = Auth::user()->organization->first()->id;
            $dataUser = User::with(['role:name', 'organization:name_organization,foto'])->whereHas('role', function ($query) {
                $query->whereNotIn('roles.id', [1, 2]);
            })->whereHas('organization', function ($query) use ($organization_id) {
                $query->where('organizations.id', $organization_id);
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
        // $userid = Auth::user()->id;
        // $myrole = User::with(['organization:id,name_organization,foto', 'role' => function ($query) {
        //     $query->select('roles.id', 'roles.name')->distinct();
        // }])->find($userid);
        // return response()->json([
        //     'status' => 200,
        //     'data' => new ShowMyroleAndOrganizationResources($myrole),
        // ]);
        $userid = Auth::user()->id;
        $myrole = User::find($userid)->role()->distinct()->get();
        return response()->json([
            'status' => 200,
            'data' => $myrole,
        ]);
    }

    public function show_admin_organization()
    {
        return response()->json([
            'status' => 200,
            'organization' => new ShowAdminOrganization(Auth::user()->organization->first()),
        ]);
    }

    public function me()
    {
        return response()->json([
            'status' => 200,
            'data' => Auth::user()
        ]);
    }

    public function myorganization(Request $request)
    {
        $user = Auth::user();
        $myorganization = Organization::whereHas('users', function ($query) use ($user) {
            $query->where('users.id', $user->id);
        })->whereHas('roles', function ($query) use ($request) {
            $query->where('roles.id', $request->role_id);
        })->get();
        return response()->json([
            'status' => 200,
            'data' => $myorganization
        ]);
    }

    public function change_password(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "password" => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = Auth::user();

        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validate->errors()
            ], 400);
        } else {
            $data = $validate->validated();
            $data['password'] = Hash::make($data['password']);

            try {
                $user->password = $data['password'];
                $user->save();
                return response()->json([
                    'status' => 200,
                    'message' => 'Change password success',
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
