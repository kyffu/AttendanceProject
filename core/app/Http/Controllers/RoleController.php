<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use App\Models\Shifts;
use Illuminate\Http\Request;
use Validator;
use Alert;
use App\Models\JobPositions;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;

class RoleController extends Controller
{
    public function index()
    {
        $query = Roles::query()
            ->select('roles.*')
            ->selectRaw('CASE WHEN COUNT(users.id) > 0 THEN false ELSE true END as can_delete')
            ->leftJoin('users', 'roles.id', '=', 'users.role_id')
            ->groupBy('roles.id');

        if (!hasRole(['admin', 'superadmin'])) {
            $query->where('roles.id', auth()->user()->role_id);
        }

        $roles = $query->orderBy('roles.id')->get();

        return view('role.index', compact('roles'));
    }

    public function create()
    {
        $shifts = Shifts::orderBy('id')->get();
        $positions = JobPositions::orderBy('id')->get();
        return view('role.create', compact('shifts', 'positions'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|min:3|max:35',
            'slug' => 'required|unique:roles,slug',
        ];

        $messages = [
            'name.required' => ' Name must be filled!',
            'name.min' => ' Name Minimum 3 Characters!',
            'name.max' => ' Name Maximum 50 Characters!',
            'slug.required' => ' Slug must be filled!',
            'slug.unique' => ' Slug already used!',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            Alert::error('Failed to create new Role', $validator->errors()->all());
            return redirect()->back();
        }
        try {
            $data = [
                'name' => $request->name,
                'slug' => $request->slug,
            ];
            Roles::create($data);
            Alert::success('New Role Created successfully', 'Role has been created for : ' . $request->name);
            return redirect()->route('role.index');
        } catch (\Exception $e) {
            Alert::error('Failed to create new Role', $e->getMessage());
            return redirect()->back();
        }
    }

    public function detail($id)
    {
        $id = Crypt::decryptString($id);
        $role = Roles::where('id', $id)->firstOrFail();
        return view('role.detail', compact('role'));
    }

    public function update(Request $request)
    {
        try {
            $id = Crypt::decryptString($request->uuid);
            $rules = [
                'name' => 'required|min:3|max:50',
                'slug' => 'required|unique:roles,slug,' . $id,
            ];
            $messages = [
                'name.required' => ' Name must be filled!',
                'name.min' => ' Name Minimum 3 Characters!',
                'name.max' => ' Name Maximum 50 Characters!',
                'slug.required' => ' Slug must be filled!',
                'slug.unique' => ' Slug already used!',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                Alert::error('Failed to update Role', $validator->errors()->all());
                return redirect()->back();
            }
            // Save to database
            Roles::where('id', $id)->firstOrFail()->update([
                'name' => $request->name,
                'slug' => $request->slug,
            ]);
            Alert::success('Role updated successfully', 'Role has been updated for : ' . $request->name);
            return redirect()->route('role.index');
        } catch (\Exception $e) {
            Alert::error('Failed to update Role', $e->getMessage());
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        $role = Roles::findOrFail($id);
        $role->delete();

        Alert::success('Role deleted successfully', 'Role has been deleted for : ' . $role->name);
        return redirect()->route('role.index');
    }
}
