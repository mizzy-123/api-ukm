<?php

namespace App\Http\Controllers;

use App\Jobs\RejectSendEmailAccount;
use App\Jobs\SendEmailAccount;
use App\Models\DataForm;
use App\Models\Form;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DaftarUkmController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validasi = Validator::make($request->all(), [
                'form_id' => 'required',
                'name' => 'required|string',
                'nim' => 'required|string',
                'email' => 'required|string|email:dns',
                'no_telepon' => 'required|string',
                'kelamin' => 'required|in:PRIA,WANITA',
            ]);

            if ($validasi->fails()) {
                return response()->json([
                    'status' => 400,
                    'message' => $validasi->errors()
                ]);
            }

            $validated = $validasi->validated();
            DataForm::create([
                'form_id' => $validated['form_id'],
                'name' => $validated['name'],
                'nim' => $validated['nim'],
                'email' => $validated['email'],
                'no_telepon' => $validated['no_telepon'],
                'kelamin' => $validated['kelamin'],
                'status' => 2,
            ]);

            return response()->json([
                'status' => 200,
                'message' => "Succesfull",
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => $th,
            ]);
        }
    }

    public function index(Request $request)
    {
        $data = Form::where('organization_id', $request->organization_id)->orderByDesc('id')->withCount('dataform')->get();
        return response()->json([
            'status' => 200,
            'data' => $data
        ]);
    }

    public function showAll(Request $request)
    {
        // $data = DataForm::where('form_id', $request->form_id)->whereNotIn('status', [true])
        //     ->orderByDesc('id')->filter(request(['search']))->paginate(8)->withQueryString();
        $data = DataForm::where('form_id', $request->form_id)
            ->orderByDesc('id')
            ->filter(request(['search', 'status']))
            ->paginate(8)
            ->withQueryString();
        return response()->json([
            'status' => 200,
            'data' => $data
        ]);
    }

    public function angkat_calon(DataForm $dataform)
    {
        DB::beginTransaction();
        try {
            //code...
            $user = User::firstOrCreate([
                'name' => $dataform->name,
                'nim' => $dataform->nim,
                'email' => $dataform->email,
                'no_telepon' => $dataform->no_telepon,
                'password' => Hash::make('123456789'),
            ]);

            $organization_id = $dataform->form()->first()->organization()->first()->id;
            $user->role()->attach(3, ['organization_id' => $organization_id]);
            $dataform->update([
                'status' => true
            ]);

            SendEmailAccount::dispatch($user);
            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Pengangkatan anggota ukm berhasil'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'message' => 'Something wrong'
            ], 500);
        }
    }

    public function select_angkat_calon(Request $request)
    {
        DB::beginTransaction();
        try {
            foreach ($request->data as $d) {
                $dataform = DataForm::find($d['id']);
                $cek = User::where('email', $d['email'])->orWhere('nim', $d['nim'])->count();
                if ($cek == 0) {
                    $user = User::firstOrCreate([
                        'name' => $d['name'],
                        'nim' => $d['nim'],
                        'email' => $d['email'],
                        'no_telepon' => $d['no_telepon'],
                        'password' => Hash::make('123456789'),
                    ]);
                    $organization_id = $dataform->form()->first()->organization()->first()->id;
                    $user->role()->attach(3, ['organization_id' => $organization_id]);
                    $dataform->update([
                        'status' => true
                    ]);

                    SendEmailAccount::dispatch($user);
                }
            }
            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Pengangkatan anggota ukm berhasil'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'message' => "Something wrong"
            ], 500);
        }
    }

    public function select_reject_calon(Request $request)
    {
        DB::beginTransaction();
        try {
            foreach ($request->data as $d) {
                $dataform = DataForm::find($d['id']);
                $dataform->update([
                    'status' => 3
                ]);
                RejectSendEmailAccount::dispatch($d);
            }
            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Pengangkatan anggota ukm berhasil'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'message' => "Something wrong"
            ], 500);
        }
    }
}
