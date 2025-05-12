<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Alert;
use App\Models\AbsentMasters;
use Illuminate\Support\Facades\Crypt;


class AbsentMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (hasRole(['admin', 'superadmin'])) {
            $masters = AbsentMasters::all();
            return view('settings.absent.index', compact('masters'));
        } else {
            Abort('403');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (hasRole(['admin', 'superadmin'])) {
            return view('settings.absent.create');
        } else {
            Abort('403');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'quota' => 'required',
        ];

        $messages = [

            'name.required' => ' Nama Jenis Kehadiran kerja harus diisi!',
            'quota.required' => 'Kuota per bulan harus diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            Alert::error('Gagal menambahkan', $validator->errors()->all());
            return redirect()->back();
        }
        try {
            if ($request->evc) {
                $evc = TRUE;
            } else {
                $evc = FALSE;
            }
            $data = [
                'name' => $request->name,
                'quota' => $request->quota,
                'evc' => $evc
            ];
            AbsentMasters::create($data);
            Alert::success('Berhasil', 'Data berhasil ditambahkan');
            return redirect()->route('settings.absent.index');
        } catch (\Exception $e) {
            Alert::error('Gagal', $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function detail(string $id)
    {
        if (hasRole(['admin', 'superadmin'])) {
            $id = Crypt::decryptString($id);
            $master = AbsentMasters::where('id', $id)->firstOrFail();
            return view('settings.absent.show', compact('master'));
        } else {
            Abort('403');
        }
    }
    public function update(Request $request)
    {
        $rules = [
            'name' => 'required',
            'quota' => 'required',
        ];

        $messages = [

            'name.required' => ' Nama Jenis Kehadiran kerja harus diisi!',
            'quota.required' => 'Kuota per bulan harus diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            Alert::error('Gagal menambahkan', $validator->errors()->all());
            return redirect()->back();
        }
        try {
            if ($request->evc) {
                $evc = TRUE;
            } else {
                $evc = FALSE;
            }
            $data = [
                'name' => $request->name,
                'quota' => $request->quota,
                'evc' => $evc
            ];
            AbsentMasters::where('id', Crypt::decryptString($request->absent))->firstOrFail()->update($data);
            Alert::success('Berhasil', 'Data berhasil diubah');
            return redirect()->route('settings.absent.index');
        } catch (\Exception $e) {
            Alert::error('Gagal', $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        try {
            if ($request->_method !== 'DELETE' || !isset($request->_token)) {
                Alert::error('Error Occured', 'Invalid Credentials');
                return redirect()->back();
            }
            $id = Crypt::decryptString($request->absend);
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
            AbsentMasters::where('id', $id)->firstOrFail()->delete();
            Alert::success('Berhasil', 'Data berhasil dihapus dari sistem');
            return redirect()->route('settings.absent.index');
        } catch (\Exception $e) {
            Alert::error('Gagal', $e->getMessage());
            return redirect()->back();
        }
    }
}
