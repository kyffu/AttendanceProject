<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobPositions;
use App\Models\Salaries;
use Validator;
use Alert;
use App\Models\Roles;
use Illuminate\Support\Facades\Crypt;


class JobPositionController extends Controller
{
    public function index()
    {
        if (hasRole(['admin', 'superadmin'])) {
            $positions = JobPositions::orderBy('title')->get();
            return view('settings.position.index', compact('positions'));
        } else {
            Abort('403');
        }
    }

    public function create()
    {
        if (hasRole(['admin', 'superadmin'])) {
            $salaries = Salaries::all();
            $roles = Roles::all();
            return view('settings.position.create', compact('salaries', 'roles'));
        } else {
            Abort('403');
        }
    }

    public function store(Request $request)
    {
        $rules = [
            'title' => 'required',
            'description' => 'required',
            'salaries' => 'required',
            'roles' => 'required',
        ];

        $messages = [

            'description.required' => ' Deskripsi posisi kerja harus diisi!',
            'title.required' => 'Judul posisi kerja harus diisi!',
            'salaries.required' => 'Tarif gaji harus dipilih!',
            'roles.required' => 'Role harus dipilih!',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            Alert::error('Gagal menambahkan', $validator->errors()->all());
            return redirect()->back();
        }
        try {
            $data = [
                'title' => $request->title,
                'description' => $request->description,
                'salaries_id' => Crypt::decryptString($request->salaries),
                'role_id' => Crypt::decryptString($request->roles)
            ];
            JobPositions::create($data);
            Alert::success('Berhasil', 'Posisi Kerja berhasil ditambahkan');
            return redirect()->route('settings.position.index');
        } catch (\Exception $e) {
            Alert::error('Gagal', $e->getMessage());
            return redirect()->back();
        }
    }

    public function detail(string $id)
    {
        if (hasRole(['admin', 'superadmin'])) {
            $id = Crypt::decryptString($id);
            $position = JobPositions::where('id', $id)->firstOrFail();
            $salaries = Salaries::all();
            $roles = Roles::all();
            return view('settings.position.show', compact('position', 'salaries', 'roles'));
        } else {
            Abort('403');
        }
    }

    public function update(Request $request)
    {
        $rules = [
            'title' => 'required',
            'description' => 'required',
            'salaries' => 'required',
            'roles' => 'required',
        ];

        $messages = [

            'description.required' => ' Deskripsi posisi kerja harus diisi!',
            'title.required' => 'Judul posisi kerja harus diisi!',
            'salaries.required' => 'Tarif gaji harus dipilih!',
            'roles.required' => 'Role harus dipilih!',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            Alert::error('Gagal menambahkan', $validator->errors()->all());
            return redirect()->back();
        }
        try {
            $data = [
                'title' => $request->title,
                'description' => $request->description,
                'salaries_id' => Crypt::decryptString($request->salaries),
                'role_id' => Crypt::decryptString($request->roles)
            ];
            JobPositions::where('id', Crypt::decryptString($request->uuid))->firstOrFail()->update($data);
            Alert::success('Berhasil', 'Posisi Kerja berhasil diubah');
            return redirect()->route('settings.position.index');
        } catch (\Exception $e) {
            Alert::error('Gagal', $e->getMessage());
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
            $id = Crypt::decryptString($request->pid);
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
            JobPositions::where('id', $id)->firstOrFail()->delete();
            Alert::success('Berhasil', 'Data berhasil dihapus dari sistem');
            return redirect()->route('settings.position.index');
        } catch (\Exception $e) {
            Alert::error('Gagal', $e->getMessage());
            return redirect()->back();
        }
    }
}
