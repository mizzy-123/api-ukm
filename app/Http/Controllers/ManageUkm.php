<?php

namespace App\Http\Controllers;

use App\Models\JadwalPiket;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
}
