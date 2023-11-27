<?php

namespace App\Http\Controllers;

use App\Http\Resources\PresensiResource;
use App\Models\PresensiPiket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsenController extends Controller
{
    public function store(Request $request)
    {
        try {
            $userid = Auth::user()->id;
            $cek = PresensiPiket::where('organization_id', $request->organization_id)
                ->where('user_id', $userid)
                ->where('created_at', '>', now()->format('Y-m-d'))
                ->count();
            if ($cek != 0) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Anda sudah absen',
                ]);
            }
            PresensiPiket::create([
                'organization_id' => $request->organization_id,
                'user_id' => $userid
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Absen berhasil',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => 'Something wrong',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function show(Request $request)
    {
        try {
            $data = PresensiPiket::where('organization_id', $request->organization_id)->with(['user'])->orderByDesc('id')->get();
            return response()->json([
                'status' => 200,
                'data' => PresensiResource::collection($data)
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => 'Something wrong',
                'error' => $th->getMessage(),
            ], 500);
        }
    }
}
