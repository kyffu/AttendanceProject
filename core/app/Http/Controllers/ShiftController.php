<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shifts;
use Validator;
use Alert;
use Illuminate\Support\Facades\Crypt;

class ShiftController extends Controller
{
    public function index()
    {
        if (hasRole(['admin', 'superadmin'])) {
            $shifts = Shifts::orderBy('id')->get();
            return view('shift.index', compact('shifts'));
        } else {
            Abort('403');
        }
    }

    public function create()
    {
        if (hasRole(['admin', 'superadmin'])) {
            return view('shift.create');
        } else {
            Abort('403');
        }
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|min:3|max:35',
            'start_time' => 'required',
            'end_time' => 'required',
            'late_tolerance' => 'required|numeric',
        ];

        $messages = [

            'name.required' => ' Nama Shift harus diisi!',
            'name.min' => ' Nama Shift minimal 3 karakter!',
            'name.max' => ' Nama Shift maksimal 35 karakter!',
            'start_time.required' => 'Waktu masuk harus diisi!',
            'end_time.required' => 'Waktu keluar harus diisi!',
            'late_tolerance.required' => 'Batas keterlambatan harus diisi!',
            'late_tolerance.numeric' => 'Batas keterlambatan harus berupa Angka!',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            Alert::error('Gagal', $validator->errors()->all());
            return redirect()->back();
        }
        try {
            Shifts::create($request->all());
            Alert::success('Sukses', 'Shift berhasil dibuat');
            return redirect()->route('shift.index');
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Shift gagal dibuat');
            return redirect()->back();
        }
    }

    public function detail($id)
    {
        if (hasRole(['admin', 'superadmin'])) {
            $shift = Shifts::where('id', Crypt::decryptString($id))->firstOrFail();
            return view('shift.show', compact('shift'));
        } else {
            Abort('403');
        }
    }

    public function update(Request $request)
    {
        $rules = [
            'name' => 'required|min:3|max:35',
            'start_time' => 'required',
            'end_time' => 'required',
            'late_tolerance' => 'required|numeric',
        ];

        $messages = [

            'name.required' => ' Nama Shift harus diisi!',
            'name.min' => ' Nama Shift minimal 3 karakter!',
            'name.max' => ' Nama Shift maksimal 35 karakter!',
            'start_time.required' => 'Waktu masuk harus diisi!',
            'end_time.required' => 'Waktu keluar harus diisi!',
            'late_tolerance.required' => 'Batas keterlambatan harus diisi!',
            'late_tolerance.numeric' => 'Batas keterlambatan harus berupa Angka!',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            Alert::error('Gagal', $validator->errors()->all());
            return redirect()->back();
        }
        try {
            Shifts::where('id',Crypt::decryptString($request->svty))->firstOrFail()->update([
                'name' => $request->name,
                'start_time'=>$request->start_time,
                'end_time' => $request->end_time,
                'late_tolerance' => $request->late_tolerance
            ]);
            Alert::success('Sukses', 'Shift berhasil diubah');
            return redirect()->route('shift.index');
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Shift gagal diubah');
            return redirect()->back();
        }
    }

    public function destroy(Request $request)
    {
        try {
            if ($request->_method !== 'DELETE' || !isset($request->_token)) {
                Alert::error('Error Occured', 'Invalid Credentials');
                return redirect()->back();
            }
            $id = Crypt::decryptString($request->ytvs);
            $rules = [
                'confirmation' => 'required',
            ];

            $messages = [
                'confirmation.required' => ' Anda harus mencentang konfirmasi penghapusan data!',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                Alert::error('Gagal', $validator->errors()->all());
                return back()->with('autofocus', true);
            }
            Shifts::where('id', $id)->firstOrFail()->delete();
            Alert::success('Berhasil', 'Data berhasil dihapus dari sistem');
            return redirect()->route('shift.index');
        } catch (\Exception $e) {
            Alert::error('Gagal', $e->getMessage());
            return redirect()->back();
        }
    }
}
