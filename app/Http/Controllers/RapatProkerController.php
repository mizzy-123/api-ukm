<?php

namespace App\Http\Controllers;

use App\Models\RapatProker;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RapatProkerController extends Controller
{

    public function delete_rapat_proker(RapatProker $rapatproker)
    {
        try {
            $rapatproker->delete();
            return response()->json([
                'status' => 200,
                'message' => 'delete berhasil',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => 'Something wrong',
                'error' => $th->getMessage(),
            ], 500);
        }
    }
    public function rapat_proker(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "name" => "required|string",
            "lokasi" => "required|string",
            "tanggal" => "required",
            "waktu" => "required",
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validate->errors()
            ], 400);
        }

        try {
            $data = $validate->validated();
            $datetime = $data['tanggal'] . " " . Carbon::parse($data["waktu"])->format('H:i:s');
            RapatProker::create([
                'name' => $data['name'],
                'lokasi' => $data['lokasi'],
                'waktu' => $datetime,
                'organization_id' => $request->organization_id
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Proker berhasil ditambah',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => 'Something wrong',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function all_rapat_proker(Request $request)
    {
        try {
            $data = RapatProker::where('organization_id', $request->organization_id)->orderByDesc('id')->get();

            return response()->json([
                'status' => 200,
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => 'Something wrong',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function edit_rapat_proker(RapatProker $rapatproker, Request $request)
    {
        $validate = Validator::make($request->all(), [
            "name" => "required|string",
            "lokasi" => "required|string",
            "tanggal" => "required",
            "waktu" => "required",
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validate->errors()
            ], 400);
        }

        try {
            $data = $validate->validated();
            $datetime = $data['tanggal'] . " " . Carbon::parse($data["waktu"])->format('H:i:s');
            $rapatproker->update([
                'name' => $data['name'],
                'lokasi' => $data['lokasi'],
                'waktu' => $datetime,
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Proker berhasil diupdate',
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
