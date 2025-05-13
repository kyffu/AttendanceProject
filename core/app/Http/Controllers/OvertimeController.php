<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Alert;
use Carbon\Carbon;
use App\Models\Overtimes;
use Illuminate\Support\Facades\Crypt;

class OvertimeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (hasRole(['admin', 'superadmin'])) {
            $data = Overtimes::orderBy('id')->get();
        } 

        else if (hasRole(['spv'])) {
            $data = Overtimes::orderBy('id')
            ->join('users', 'overtimes.user_id', '=', 'users.id')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->select('overtimes.*')
            ->where('roles.slug', 'karyawan')
            ->orWhere('overtimes.user_id', auth()->user()->id)
            ->get();
        } 

        else if (hasRole(['mandor'])) {
            $data = Overtimes::orderBy('id')
            ->join('users', 'overtimes.user_id', '=', 'users.id')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->select('overtimes.*')
            ->where('roles.slug', 'tukang')
            ->orWhere('overtimes.user_id', auth()->user()->id)
            ->get();
        } 
        
        else {
            $data = Overtimes::where('user_id', auth()->user()->id)->get();
        }
        return view('attendance.overtime.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('attendance.overtime.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'date' => 'required',
            'duration' => 'required',
        ];

        $messages = [
            'date.required' => ' Tgl. Lembur harus dipilih!',
            'duration.required' => ' Durasi lembur harus diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            Alert::error('Gagal mengajukan', $validator->errors()->all());
            return redirect()->back();
        }
        try {
            $data = [
                'user_id' => auth()->user()->id,
                'date' => Carbon::createFromFormat('d-m-Y', $request->date)->format('Y-m-d'),
                'hours' => $request->duration,
            ];
            Overtimes::create($data);
            Alert::success('Berhasil', 'Pengajuan lembur berhasil dilakukan, tunggu verifikasi!');
            return redirect()->route('attendance.overtime.index');
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
        $overtime = Overtimes::where('id', Crypt::decryptString($id))->firstOrFail();
        return view('attendance.overtime.detail', compact('overtime'));
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
            $id = Crypt::decryptString($request->ovt);
            Overtimes::where('id', $id)->firstOrFail()->update([
                'status' => Crypt::decryptString($request->validation),
                'validated_by' => auth()->user()->id,
                'validated_at' => now(),
                'note' => $request->note
            ]);
            Alert::success('Berhasil', 'Pengajuan berhasil divalidasi');
            return redirect()->route('attendance.overtime.index');
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
