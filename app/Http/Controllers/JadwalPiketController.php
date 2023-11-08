<?php

namespace App\Http\Controllers;

use App\Http\Resources\JadwalPiketResource;
use App\Models\JadwalPiket;
use App\Models\UserPiket;
use Illuminate\Http\Request;

class JadwalPiketController extends Controller
{
    public function showAll(Request $request)
    {
        try {
            $jadwalpiket = JadwalPiket::where('organization_id', $request->organization_id)->with('user_piket')->get();
            return response()->json([
                'status' => 200,
                'data' => JadwalPiketResource::collection($jadwalpiket)
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => 'Something wrong',
            ], 500);
        }
    }

    public function show_piket_org(Request $request)
    {
        $piket = JadwalPiket::where('organization_id', $request->organization_id)->get();
        return response()->json([
            'status' => 200,
            'data' => $piket
        ]);
    }

    public function add_petugas_piket(Request $request)
    {
        try {
            UserPiket::create([
                "user_id" => $request->mahasiswa_id,
                "jadwal_piket_id" => $request->hari_id
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Berhasil ditambah'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => 'Something wrong'
            ], 500);
        }
    }

    public function delete_petugas_piket(UserPiket $userpiket)
    {
        try {
            $userpiket->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Delete successfull'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => 'Something wrong'
            ], 500);
        }
    }

    public function update_petugas_piket(Request $request)
    {
        try {
            $userpiket = UserPiket::find($request->userpiket_id);
            $userpiket->update([
                'jadwal_piket_id' => $request->hari_id
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Berhasil diubah'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => 'Something wrong'
            ], 500);
        }
    }
}
