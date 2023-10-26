<?php

namespace App\Http\Controllers;

use App\Http\Resources\AllUser;
use App\Http\Resources\ShowAdminOrganization;
use App\Http\Resources\ShowMyroleAndOrganizationResources;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $userid = Auth::user()->id;
        $myrole = User::with(['organization:id,name_organization,foto', 'role' => function ($query) {
            $query->select('roles.id', 'roles.name')->distinct();
        }])->find($userid);
        return response()->json([
            'status' => 200,
            'data' => new ShowMyroleAndOrganizationResources($myrole),
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
