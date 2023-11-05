<?php

namespace App\Http\Controllers;

use App\Http\Resources\TampilFormPendaftaranResources;
use App\Models\Form;
use Illuminate\Http\Request;

class RekrutUkmController extends Controller
{
    public function rekrut(Request $request)
    {
        $cek = Form::create([
            'organization_id' => $request->organization_id,
            'expired' => $request->expired,
            'status' => true,
        ]);

        if (!$cek) {
            return response()->json([
                'status' => 500,
                'message' => 'Server error'
            ]);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Rekruitment successfull'
        ]);
    }

    public function showAll()
    {
        $form = Form::with(['organization' => function ($query) {
            $query->select('id', 'name_organization', 'foto');
        }])->whereNotIn('status', [false])->get();
        return response()->json([
            'status' => 200,
            'data' => TampilFormPendaftaranResources::collection($form),
        ]);
    }

    public function cancel(Form $form)
    {
        $form->update([
            'status' => false,
        ]);
        return response()->json([
            'status' => 200,
            'message' => 'Cancel successfull'
        ]);
    }
}
