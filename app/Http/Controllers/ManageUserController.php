<?php

namespace App\Http\Controllers;

use App\Http\Resources\AllUser;
use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use App\Models\UserOrganization;
use App\Models\UserRoleOrganization;
use Illuminate\Http\Request;

class ManageUserController extends Controller
{

    public function get_role(Request $request)
    {
        try {
            $role = UserRoleOrganization::where('organization_id', $request->organization_id)->where('user_id', $request->user_id)->first()->role()->first();
            return response()->json([
                'status' => 200,
                'data' => $role,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => 'Something wrong',
            ], 500);
        }
    }
    public function all_organization()
    {
        try {
            $organizations = Organization::whereNotIn('id', [1])->get();
            return response()->json([
                'status' => 200,
                'data' => $organizations,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => 'Something wrong',
            ], 500);
        }
    }

    public function user_organization(Request $request)
    {
        try {
            // UserRoleOrganization::where('organization_id', 2)->where('user_id', 8)->first();
            // $user = User::with(['role'])->whereHas('organization', function ($query) use ($request) {
            //     $query->where('organizations.id', $request->organization_id);
            // })->whereHas('role', function ($query) {
            //     $query->whereNotIn('roles.id', [1]);
            // })->orderByDesc('id')
            //     ->filter(request(['search']))
            //     ->paginate(8)
            //     ->withQueryString();
            // $user = User::with('role', 'organization')->whereHas('organization', function ($query) use ($request) {
            //     $query->where('organizations.id', 2);
            // })->orderByDesc('id')->get();

            $user = User::with(['role' => function ($query) use ($request) {
                // Batasi peran berdasarkan organisasi
                $query->where('organization_id', $request->organization_id);
            }])->whereHas('organization', function ($query) use ($request) {
                $query->where('organizations.id', $request->organization_id);
            })->whereHas('role', function ($query) {
                $query->whereNotIn('roles.id', [1]);
            })->orderByDesc('id')
                ->filter(request(['search']))
                ->paginate(8)
                ->withQueryString();
            return response()->json([
                'status' => 200,
                'data' => $user,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => 'Something wrong',
            ], 500);
        }
    }

    public function ganti_role(Request $request)
    {
        try {
            $user = User::find($request->user_id);
            $user->organization()->updateExistingPivot($request->organization_id, ['role_id' => $request->role_id]);
            return response()->json([
                'status' => 200,
                'message' => 'Berhasil diupdate'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => 'Something wrong',
            ], 500);
        }
    }

    public function all_role_except_sa()
    {
        try {
            $role = Role::whereNotIn('id', [1])->get();
            return response()->json([
                'status' => 200,
                'data' => $role
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => 'Something wrong',
            ], 500);
        }
    }
}
