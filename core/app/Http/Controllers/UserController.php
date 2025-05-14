<?php

namespace App\Http\Controllers;

use Alert;
use Validator;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Roles;
use App\Models\Shifts;
use App\Models\JobPositions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class UserController extends Controller
{
    public function index()
    {
        if (hasRole(['admin', 'superadmin'])) {
            $users = User::orderBy('id')->get();            
        }else{
            $users = User::where('id', auth()->user()->id)->get();
        }
        return view('user.index', compact('users'));
    }

    public function create()
    {
        $shifts = Shifts::orderBy('id')->get();
        $roles = Roles::orderBy('id')->get();
        $positions = DB::table('job_positions')
        ->leftJoin('roles', 'job_positions.role_id', '=', 'roles.id')
        ->select(
            'job_positions.*',
            'roles.id as role_id',
            'roles.name'
        )
        ->get();

        $parents = User::orderBy('id')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->select('users.*', 'roles.slug')
            ->where('roles.slug', 'mandor')
            ->orWhere('roles.slug', 'spv')
            ->get();

        return view('user.create', compact('shifts', 'positions', 'roles', 'parents'));
    }

    public function store(Request $request)
    {
        try {

            // dd($request->all());

            $rules = [
                'name' => 'required|min:3|max:35',
                'email' => 'required|unique:users,email',
                'passworda' => 'required',
                'passwordb' => 'required|same:passworda',
                'shift' => 'required',
                'position' => 'required',
                'roles' => 'required',
                'parent' => 'nullable'
            ];

            $messages = [

                'name.required' => ' Full Name must be filled!',
                'name.min' => ' Full Name Minimum 3 Characters!',
                'name.max' => ' Full Name Maximum 35 Characters!',
                'email.required' => ' Email must be filled!',
                'email.unique' => ' Email already registered!',
                'passworda.required' => ' Password must be filled!',
                'passwordb.required' => ' Confirmation Password must be filled!',
                'passwordb.same' => ' Confirmation Password must be same with Password!',
                'shift.required' => 'Shift harus diisi!',
                'position.required' => 'Posisi Kerja harus diisi!',
                'roles.required' => 'Role harus diisi!',
            ];

            $request->merge([
                    'roles' => Crypt::encryptString($request->roles)
                ]);

            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                Alert::error('Failed to create new User', $validator->errors()->all());
                return redirect()->back();
            }

            $role_id = Crypt::decryptString($request->roles);        
            $role = Roles::where('id', $role_id)->firstOrFail();

            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->passworda,
                'shift_id' => Crypt::decryptString($request->shift),
                'position_id' => Crypt::decryptString($request->position),
                'role' => $role->name,
                'role_id' => $role->id,
            ];

            if ($request->parent) {
                $data['parent_id'] = Crypt::decryptString($request->parent);
            }

            User::create($data);
            Alert::success('New User Created successfully', 'User has been created for : ' . $request->name);
            return redirect()->route('user.index');
        } catch (\Exception $e) {
            Alert::error('Failed to create new User', $e->getMessage());
            return redirect()->back();
        }
    }

    public function detail($id)
    {
        $id = Crypt::decryptString($id);
        $user = User::where('id', $id)->firstOrFail();
        $shifts = Shifts::orderBy('id')->get();
        $roles = Roles::orderBy('id')->get();
        $positions = DB::table('job_positions')
        ->leftJoin('roles', 'job_positions.role_id', '=', 'roles.id')
        ->select(
            'job_positions.*',
            'roles.id as role_id',
            'roles.name'
        )
        ->get();
        
        $parents = User::orderBy('id')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->select('users.*', 'roles.slug')
            ->where('roles.slug', 'mandor')
            ->orWhere('roles.slug', 'spv')
            ->get();
        return view('user.detail', compact('user', 'shifts', 'positions', 'roles', 'parents'));
    }

    public function update(Request $request)
    {
        try {
            $id = Crypt::decryptString($request->uuid);
            $rules = [
                'name' => 'required|min:3|max:50',
                'email' => 'required|unique:users,email,' . $id,
                'status' => 'required',
                'shift' => 'required',
                'position' => 'required',
                'roles' => 'required',
                'parent' => 'nullable'
            ];
            $messages = [
                'name.required' => ' Full Name must be filled!',
                'name.min' => ' Full Name Minimum 3 Characters!',
                'name.max' => ' Full Name Maximum 50 Characters!',
                'email.required' => ' Email must be filled!',
                'email.unique' => ' Email already registered!',
                'status.required' => ' Status must be selected!',
                'shift.required' => 'Shift harus diisi!',
                'position.required' => 'Posisi Kerja harus diisi!',
                'roles.required' => 'Role harus diisi!',
            ];
            
            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                Alert::error('Failed to update User', $validator->errors()->all());
                return redirect()->back();
            }

            $role = Roles::where('id', $request->roles)->firstOrFail();

            $position_id = Crypt::decryptString($request->position);        
            $position = JobPositions::where('id', $position_id)->firstOrFail();

            // Save to database
            User::where('id', $id)->firstOrFail()->update([
                'name' => $request->name,
                'email' => $request->email,
                'status' => $request->status,
                'shift_id' => Crypt::decryptString($request->shift),
                'position_id' => Crypt::decryptString($request->position),
                'role' => $role->name,
                'role_id' => $role->id,
                'parent_id' => $request->parent
            ]);
            Alert::success('User updated successfully', 'User has been updated for : ' . $request->name);
            return redirect()->route('user.index');
        } catch (\Exception $e) {
            Alert::error('Failed to update User', $e->getMessage());
            return redirect()->back();
        }
    }

    public function updatePass(Request $request, $id)
    {
        try {
            $id = Crypt::decryptString($id);
            $rules = [
                'passworda' => 'required',
                'passwordb' => 'required|same:passworda',
            ];

            $messages = [
                'passworda.required' => ' New Password must be filled!',
                'passwordb.required' => ' New Confirmation Password must be filled!',
                'passwordb.same' => ' New Confirmation Password must be same with New Password!',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                Alert::error('Failed to update password', $validator->errors()->all());
                return redirect()->back();
            }
            User::where('id', $id)->firstOrFail()->update([
                'password' => $request->passworda
            ]);
            Alert::success('Password updated successfully', 'New password has been saved');
            return redirect()->route('user.index');
        } catch (\Exception $e) {
            Alert::error('Error Occurred', $e->getMessage());
            return redirect()->back();
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            if ($request->_method !== 'DELETE' || !isset($request->_token)) {
                Alert::error('Error Occured', 'Invalid Credentials');
                return redirect()->back();
            }
            $id = Crypt::decryptString($id);
            $rules = [
                'confirmation' => 'required',
            ];

            $messages = [
                'confirmation.required' => ' You must check the confirmation of account deactivation first!',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                Alert::error('Failed to deactivate account', $validator->errors()->all());
                return back()->with('autofocus', true);
            }
            User::where('id', $id)->firstOrFail()->delete();
            Alert::success('User Deleted Successfully', 'The user has been deleted from the system');
            return redirect()->route('user.index');
        } catch (\Exception $e) {
            Alert::error('Error Occurred', $e->getMessage());
            return redirect()->back();
        }

    }
}
