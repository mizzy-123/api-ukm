<?php

namespace App\Http\Controllers;

use App\Models\JadwalPiket;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;

class ManageUkm extends Controller
{
    public function all_organization()
    {
        try {
            $organization = Organization::whereNotIn('id', [1])->orderByDesc('id')->withCount('users')->get();
            return response()->json([
                'status' => 200,
                'data' => $organization
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => 'Something wrong',
            ], 500);
        }
    }

    public function tambah_ukm(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "name_organization" => "required|string",
            "name" => "required|string",
            "nim" => "required|string|unique:users",
            "email" => "required|string|email:dns|unique:users",
            "password" => ['required', Rules\Password::defaults()],
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validate->errors()
            ], 400);
        } else {
            $data = $validate->validated();
            $data['password'] = Hash::make($data['password']);

            DB::beginTransaction();

            try {
                $organization = Organization::create([
                    'name_organization' => $data['name_organization'],
                ]);
                $user = User::create([
                    'name' => $data['name'],
                    'nim' => $data['nim'],
                    'email' => $data['email'],
                    'password' => $data['password'],
                ]);

                $user->role()->attach(2, ['organization_id' => $organization->id]);

                $hari = [
                    [
                        'nama_hari' => 'Senin',
                    ],
                    [
                        'nama_hari' => 'Selasa',
                    ],
                    [
                        'nama_hari' => 'Rabu',
                    ],
                    [
                        'nama_hari' => 'Kamis',
                    ],
                    [
                        'nama_hari' => "Jumat",
                    ],
                    [
                        'nama_hari' => 'Sabtu',
                    ],
                    [
                        'nama_hari' => 'Minggu',
                    ],
                ];

                foreach ($hari as $h) {
                    JadwalPiket::create([
                        'nama_hari' => $h['nama_hari'],
                        'organization_id' => $organization->id,
                    ]);
                }

                DB::commit();
                return response()->json([
                    'status' => 200,
                    'message' => 'Register successfull',
                ]);
            } catch (\Throwable $th) {
                DB::rollBack();
                return response()->json([
                    'status' => 500,
                    'message' => 'Server error',
                ], 500);
            }
        }
    }

    public function edit_ukm(Organization $organization, Request $request)
    {
        try {
            $organization->update([
                'name_organization' => $request->name_organization,
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Update berhasil',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => 'Server error',
            ], 500);
        }
    }

    public function password_reset(User $user)
    {
        try {
            $user->update([
                'password' => Hash::make('123456789'),
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Reset password berhasil',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => 'Server error',
            ], 500);
        }
    }

    public function data_organization(Request $request)
    {
        try {
            $data = Organization::find($request->organization_id);
            return response()->json([
                'status' => 200,
                'data' => $data,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => 'Server error',
            ], 500);
        }
    }

    public function update_data_organization(Request $request, Organization $organization)
    {
        try {
            if ($request->file('foto')) {
                if ($organization->foto != null) {
                    Storage::delete($organization->foto);
                }
                $gambar = $request->file('foto')->store('gambar-ukm');
                $organization->foto = $gambar;
            }
            $organization->name_organization = $request->name_organization;
            $organization->visi = $request->visi;
            $organization->misi = $request->misi;
            $organization->save();

            return response()->json([
                'status' => 200,
                'message' => 'update data organization berhasil',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => 'Something wrong',
            ], 500);
        }
    }

    public function get_all_organization()
    {
        $organizations = Organization::orderByDesc('id')->whereNotIn('id', [1])->get();
        $totalOrganization = $organizations->count();

        return response()->json([
            'status' => 200,
            'data' => [
                'organization' => $organizations,
                'total' => $totalOrganization
            ]
        ]);
    }
}
