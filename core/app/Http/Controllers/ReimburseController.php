<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Alert;
use Carbon\Carbon;
use App\Models\Reimbursments;
use Illuminate\Support\Facades\Crypt;

class ReimburseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (hasRole(['admin', 'superadmin'])) {
            $data = Reimbursments::orderBy('id')->get();
        } else {
            $data = Reimbursments::where('user_id', auth()->user()->id)->get();
        }
        return view('attendance.reimburse.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('attendance.reimburse.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'date' => 'required',
            'desc' => 'required',
            'amount' => 'required',
            'evidence' => 'required|mimes:jpeg,jpg|max:2048',
        ];

        $messages = [

            'date.required' => ' Tgl. Reimburse harus dipilih!',
            'desc.required' => ' Keterangan harus diisi!',
            'amount.required' => ' Jumlah Reimburse harus diisi!',
            'evidence.required' => ' Bukti reimburse harus diunggah!',
            'evidence.mimes' => ' Bukti reimburse harus bertipe JPEG/JPG!',
            'evidence.max' => ' Bukti reimburse maksimal berukuran 2MB!',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            Alert::error('Gagal mengajukan', $validator->errors()->all());
            return redirect()->back();
        }
        try {
            if ($request->evidence) {
                $filename = auth()->user()->id . '-RMBRS-' . now()->timestamp . '.' . $request->evidence->extension();
                $request->evidence->move('assets/reimburse', $filename);
            }
            $data = [
                'user_id' => auth()->user()->id,
                'amount' => $request->amount,
                'description' => $request->desc,
                'reimbursement_date' => Carbon::createFromFormat('d-m-Y', $request->date)->format('Y-m-d'),
                'evidence_photo' => 'assets/reimburse/' . $filename,
            ];
            Reimbursments::create($data);
            Alert::success('Berhasil', 'Pengajuan reimburse berhasil dilakukan, tunggu verifikasi!');
            return redirect()->route('attendance.reimburse.index');
        } catch (\Exception $e) {
            Alert::error('Gagal mengajukan', $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function detail(string $id)
    {
        $reimburse = Reimbursments::where('id', Crypt::decryptString($id))->firstOrFail();
        return view('attendance.reimburse.detail', compact('reimburse'));
    }


    public function validation(Request $request)
    {
        $rules = [
            'validation' => 'required',
        ];
        $messages = [
            'validation.required' => ' Status Validasi harus dipilih!',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            Alert::error('Gagal validasi', $validator->errors()->all());
            return redirect()->back();
        }
        try {
            $id = Crypt::decryptString($request->reimburse);
            Reimbursments::where('id', $id)->firstOrFail()->update([
                'status' => Crypt::decryptString($request->validation),
                'validated_by' => auth()->user()->id,
                'validated_at' => now()
            ]);
            Alert::success('Berhasil', 'Pengajuan berhasil divalidasi');
            return redirect()->route('attendance.reimburse.index');
        } catch (\Exception $e) {
            Alert::error('Gagal validasi', $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
