<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Alert;
use App\Models\Salaries;
use Illuminate\Support\Facades\Crypt;

class SalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (hasRole(['admin', 'superadmin'])) {
            $salaries = Salaries::all();
            return view('settings.salary.index', compact('salaries'));
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
            return view('settings.salary.create');
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
            'description' => 'required',
            'amount' => 'required',
        ];

        $messages = [

            'description.required' => ' Deskripsi gaji harus diisi!',
            'amount.required' => 'Jumlah gaji harus diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            Alert::error('Gagal menambahkan', $validator->errors()->all());
            return redirect()->back();
        }
        try {
            $data = [
                'description' => $request->description,
                'amount' => $request->amount,
            ];
            Salaries::create($data);
            Alert::success('Berhasil', 'Gaji berhasil ditambahkan');
            return redirect()->route('settings.salary.index');
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
            $salary = Salaries::where('id', $id)->firstOrFail();
            return view('settings.salary.show', compact('salary'));
        } else {
            Abort('403');
        }
    }
    public function update(Request $request)
    {
        $rules = [
            'description' => 'required',
            'amount' => 'required',
        ];

        $messages = [

            'description.required' => ' Deskripsi gaji harus diisi!',
            'amount.required' => 'Jumlah gaji harus diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            Alert::error('Gagal menambahkan', $validator->errors()->all());
            return redirect()->back();
        }
        try {
            $data = [
                'description' => $request->description,
                'amount' => $request->amount,
            ];
            Salaries::where('id', Crypt::decryptString($request->saly))->firstOrFail()->update($data);
            Alert::success('Berhasil', 'Gaji berhasil diperbarui');
            return redirect()->route('settings.salary.index');
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
            $id = Crypt::decryptString($request->salyry);
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
            Salaries::where('id', $id)->firstOrFail()->delete();
            Alert::success('Berhasil', 'Data berhasil dihapus dari sistem');
            return redirect()->route('settings.salary.index');
        } catch (\Exception $e) {
            Alert::error('Gagal', $e->getMessage());
            return redirect()->back();
        }
    }
}
