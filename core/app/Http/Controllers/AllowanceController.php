<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Alert;
use App\Models\Allowances;
use Illuminate\Support\Facades\Crypt;

class AllowanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (hasRole(['admin', 'superadmin'])) {
            $allowances = Allowances::all();
            return view('settings.allowance.index', compact('allowances'));
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
            return view('settings.allowance.create');
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
            'allowance_name' => 'required',
            'amount' => 'required',
            'quota' => 'required',
        ];

        $messages = [

            'allowance_name.required' => ' Nama tunjangan harus diisi!',
            'amount.required' => ' Jumlah tunjangan harus diisi!',
            'quota.required' => ' Jumlah kuota harus diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            Alert::error('Gagal menambahkan', $validator->errors()->all());
            return redirect()->back();
        }
        try {
            $data = [
                'name' => $request->allowance_name,
                'amount' => $request->amount,
                'quota' => $request->quota,
            ];
            Allowances::create($data);
            Alert::success('Berhasil', 'Tunjangan berhasil ditambahkan');
            return redirect()->route('settings.allowance.index');
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
            $allowance = Allowances::where('id', $id)->firstOrFail();
            return view('settings.allowance.show', compact('allowance'));
        } else {
            Abort('403');
        }
    }

    public function update(Request $request)
    {
        $rules = [
            'allowance_name' => 'required',
            'amount' => 'required',
            'quota' => 'required',
        ];

        $messages = [

            'allowance_name.required' => ' Nama tunjangan harus diisi!',
            'amount.required' => ' Jumlah tunjangan harus diisi!',
            'quota.required' => ' Jumlah kuota harus diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            Alert::error('Gagal mengubah', $validator->errors()->all());
            return redirect()->back();
        }
        try {
            $data = [
                'name' => $request->allowance_name,
                'amount' => $request->amount,
                'quota' => $request->quota
            ];
            Allowances::where('id', Crypt::decryptString($request->allow))->firstOrFail()->update($data);
            Alert::success('Berhasil', 'Tunjangan berhasil diperbarui');
            return redirect()->route('settings.allowance.index');
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
            $id = Crypt::decryptString($request->disallow);
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
            Allowances::where('id', $id)->firstOrFail()->delete();
            Alert::success('Berhasil', 'Data berhasil dihapus dari sistem');
            return redirect()->route('settings.allowance.index');
        } catch (\Exception $e) {
            Alert::error('Gagal', $e->getMessage());
            return redirect()->back();
        }
    }
}
