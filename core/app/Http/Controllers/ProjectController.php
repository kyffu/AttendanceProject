<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Projects;
use App\Models\ProjectWorkers;
use App\Models\ProjectEvidences;
use Validator;
use Alert;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (hasRole(['superadmin', 'pm'])) {
            $projects = Projects::with('foreman')->get();
            return view('project.index', compact('projects'));
        } else {
            Abort('403');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (hasRole(['superadmin', 'pm'])) {
            $tukangs = DB::table('users as tukang')
                ->join('roles', 'tukang.role_id', '=', 'roles.id')
                ->leftJoin('users as mandor', 'tukang.parent_id', '=', 'mandor.id')
                ->where('roles.name', 'tukang')
                ->select('tukang.id', 'tukang.name', 'mandor.name as mandor')
                ->get();

            $mandors = DB::table('users')
                ->join('roles', 'users.role_id', '=', 'roles.id')
                ->where('roles.name', 'mandor')
                ->select('users.id', 'users.name')
                ->get();

            return view('project.create', compact('tukangs', 'mandors'));
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
            'project_name' => 'required',
            'project_desc' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'worker_name' => 'required',
            'worker_name.*' => 'required',
            'salary_day' => 'required',
            'salary_day.*' => 'required|numeric',
            'mandor_name' => 'required',
            'mandor_name.*' => 'required',
            'mandor_salary_day' => 'required',
            'mandor_salary_day.*' => 'required|numeric'
        ];

        $messages = [
            'project_name.required' => ' Nama proyek harus diisi!',
            'project_desc.required' => ' Deskripsi Proyek harus diisi!',
            'start_date.required' => ' Tanggal Mulai harus dipilih!',
            'end_date.required' => ' Tanggal Selesai harus dipilih!',
            'worker_name.required' => ' Nama tukang harus diisi!',
            'worker_name.*.required' => ' Nama tukang harus diisi!',
            'salary_day.required' => ' Tarif upah tukang harus diisi!',
            'salary_day.*.required' => ' Tarif upah tukang harus diisi!',
            'worker_name.required' => ' Nama mandor harus diisi!',
            'worker_name.*.required' => ' Nama mandor harus diisi!',
            'mandor_salary_day.required' => ' Tarif upah mandor harus diisi!',
            'mandor_salary_day.*.required' => ' Tarif upah mandor harus diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            Alert::error('Gagal menambahkan', $validator->errors()->all());
            return redirect()->back();
        }
        DB::beginTransaction();
        try {
            $project = Projects::create([
                'name' => $request->project_name,
                'desc' => $request->project_desc,
                'start_date' => Carbon::createFromFormat('d-m-Y', $request->start_date)->format('Y-m-d'),
                'end_date' => Carbon::createFromFormat('d-m-Y', $request->end_date)->format('Y-m-d'),
                'foreman_id' => auth()->user()->id
            ]);
            for ($i = 0; $i < count($request->mandor_name); $i++) {
                ProjectWorkers::create([
                    'project_id' => $project->id,
                    'worker_name' => $request->mandor_name[$i],
                    'working_days' => 0,
                    'salary_day' => $request->mandor_salary_day[$i],
                    'total_salary' => 0,
                    'is_mandor' => 1
                ]);
            }
            for ($i = 0; $i < count($request->worker_name); $i++) {
                ProjectWorkers::create([
                    'project_id' => $project->id,
                    'worker_name' => $request->worker_name[$i],
                    'working_days' => 0,
                    'salary_day' => $request->salary_day[$i],
                    'total_salary' => 0,
                    'is_mandor' => 0
                ]);
            }
            DB::commit();
            Alert::success('Berhasil', 'Proyek : ' . $request->project_name . ' berhasil didaftarkan!');
            return redirect()->route('project.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::error('Gagal menambahkan', $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function detail(string $id)
    {
        if (hasRole(['superadmin', 'pm'])) {
            $id = Crypt::decryptString($id);
            $project = Projects::where('id', $id)->with('foreman', 'validator')->firstOrFail();
            $data_tukangs = ProjectWorkers::where('project_id', $id)->where('is_mandor', false)->get();
            $tukangs = DB::table('users as tukang')
                ->join('roles', 'tukang.role_id', '=', 'roles.id')
                ->leftJoin('users as mandor', 'tukang.parent_id', '=', 'mandor.id')
                ->where('roles.name', 'tukang')
                ->select('tukang.id', 'tukang.name', 'mandor.name as mandor')
                ->get();
            
            $data_mandors = ProjectWorkers::where('project_id', $id)->where('is_mandor', true)->get();
            $mandors = DB::table('users')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->where('roles.name', 'mandor')
            ->select('users.id', 'users.name')
            ->get();

            $workers = ProjectWorkers::where('project_id', $id)->get();
            if ($project->status == 0) {
                return view('project.detail', compact('project', 'data_tukangs', 'data_mandors' ,'tukangs', 'mandors'));
            } elseif ($project->status == 1) {
                return view('project.locked', compact('project', 'data_tukangs', 'data_mandors' ,'tukangs', 'mandors', 'workers'));
            } else {
                $attendances = ProjectEvidences::where('project_id', $id)->get();
                return view('project.validate', compact('project', 'data_tukangs', 'data_mandors' ,'tukangs', 'mandors', 'attendances', 'workers'));
            }
        } else {
            Abort('403');
        }
    }


    public function validateProject(Request $request)
    {
        DB::beginTransaction();
        try {
            $id = Crypt::decryptString($request->prid);
            Projects::where('id', $id)->firstOrFail()->update([
                'status' => 4,
                'validated_by' => auth()->user()->id,
                'validated_at' => now()
            ]);
            DB::commit();
            Alert::success('Berhasil', 'Proyek berhasil divalidasi!');
            return redirect()->route('project.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::error('Gagal Validasi', $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $rules = [
            'project_name' => 'required',
            'project_desc' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'worker_name' => 'required',
            'worker_name.*' => 'required',
            'salary_day' => 'required',
            'salary_day.*' => 'required|numeric',
            'mandor_name' => 'required',
            'mandor_name.*' => 'required',
            'mandor_salary_day' => 'required',
            'mandor_salary_day.*' => 'required|numeric'
        ];

        $messages = [
            'project_name.required' => ' Nama proyek harus diisi!',
            'project_desc.required' => ' Deskripsi Proyek harus diisi!',
            'start_date.required' => ' Tanggal Mulai harus dipilih!',
            'end_date.required' => ' Tanggal Selesai harus dipilih!',
            'worker_name.required' => ' Nama tukang harus diisi!',
            'worker_name.*.required' => ' Nama tukang harus diisi!',
            'salary_day.required' => ' Tarif upah tukang harus diisi!',
            'salary_day.*.required' => ' Tarif upah tukang harus diisi!',
            'worker_name.required' => ' Nama mandor harus diisi!',
            'worker_name.*.required' => ' Nama mandor harus diisi!',
            'mandor_salary_day.required' => ' Tarif upah mandor harus diisi!',
            'mandor_salary_day.*.required' => ' Tarif upah mandor harus diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            Alert::error('Gagal menambahkan', $validator->errors()->all());
            return redirect()->back();
        }
        DB::beginTransaction();
        try {
            $id = Crypt::decryptString($request->prid);
            Projects::where('id', $id)->firstOrFail()->update([
                'name' => $request->project_name,
                'desc' => $request->project_desc,
                'start_date' => Carbon::createFromFormat('d-m-Y', $request->start_date)->format('Y-m-d'),
                'end_date' => Carbon::createFromFormat('d-m-Y', $request->end_date)->format('Y-m-d'),
                'foreman_id' => auth()->user()->id
            ]);
            $deleteW = ProjectWorkers::where('project_id', $id)->delete();
            if ($deleteW) {
                for ($i = 0; $i < count($request->mandor_name); $i++) {
                    ProjectWorkers::create([
                        'project_id' => $id,
                        'worker_name' => $request->mandor_name[$i],
                        'working_days' => 0,
                        'salary_day' => $request->mandor_salary_day[$i],
                        'total_salary' => 0,
                        'is_mandor' => 1
                    ]);
                }
                for ($i = 0; $i < count($request->worker_name); $i++) {
                    ProjectWorkers::create([
                        'project_id' => $id,
                        'worker_name' => $request->worker_name[$i],
                        'working_days' => 0,
                        'salary_day' => $request->salary_day[$i],
                        'total_salary' => 0,
                        'is_mandor' => 0
                    ]);
                }
            }
            DB::commit();
            Alert::success('Berhasil', 'Proyek : ' . $request->project_name . ' berhasil diperbarui!');
            return redirect()->route('project.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::error('Gagal melakukan pembaruan', $e->getMessage());
            return redirect()->back();
        }
    }

    public function lockData(Request $request)
    {
        $rules = [
            'project_name' => 'required',
            'project_desc' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'worker_name' => 'required',
            'worker_name.*' => 'required',
            'salary_day' => 'required',
            'salary_day.*' => 'required|numeric',
            'mandor_name' => 'required',
            'mandor_name.*' => 'required',
            'mandor_salary_day' => 'required',
            'mandor_salary_day.*' => 'required|numeric'
        ];

        $messages = [
            'project_name.required' => ' Nama proyek harus diisi!',
            'project_desc.required' => ' Deskripsi Proyek harus diisi!',
            'start_date.required' => ' Tanggal Mulai harus dipilih!',
            'end_date.required' => ' Tanggal Selesai harus dipilih!',
            'worker_name.required' => ' Nama tukang harus diisi!',
            'worker_name.*.required' => ' Nama tukang harus diisi!',
            'salary_day.required' => ' Tarif upah tukang harus diisi!',
            'salary_day.*.required' => ' Tarif upah tukang harus diisi!',
            'worker_name.required' => ' Nama mandor harus diisi!',
            'worker_name.*.required' => ' Nama mandor harus diisi!',
            'mandor_salary_day.required' => ' Tarif upah mandor harus diisi!',
            'mandor_salary_day.*.required' => ' Tarif upah mandor harus diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            Alert::error('Gagal mengunci data', $validator->errors()->all());
            return redirect()->back();
        }
        DB::beginTransaction();
        try {
            $id = Crypt::decryptString($request->prid);
            Projects::where('id', $id)->firstOrFail()->update([
                'name' => $request->project_name,
                'desc' => $request->project_desc,
                'start_date' => Carbon::createFromFormat('d-m-Y', $request->start_date)->format('Y-m-d'),
                'end_date' => Carbon::createFromFormat('d-m-Y', $request->end_date)->format('Y-m-d'),
                'foreman_id' => auth()->user()->id,
                'status' => 1
            ]);
            $deleteW = ProjectWorkers::where('project_id', $id)->delete();
            if ($deleteW) {
                for ($i = 0; $i < count($request->mandor_name); $i++) {
                    ProjectWorkers::create([
                        'project_id' => $id,
                        'worker_name' => $request->mandor_name[$i],
                        'working_days' => 0,
                        'salary_day' => $request->mandor_salary_day[$i],
                        'total_salary' => 0,
                        'is_mandor' => 1
                    ]);
                }
                for ($i = 0; $i < count($request->worker_name); $i++) {
                    ProjectWorkers::create([
                        'project_id' => $id,
                        'worker_name' => $request->worker_name[$i],
                        'working_days' => 0,
                        'salary_day' => $request->salary_day[$i],
                        'total_salary' => 0,
                        'is_mandor' => 0
                    ]);
                }
            }
            DB::commit();
            Alert::success('Berhasil', 'Proyek : ' . $request->project_name . ' berhasil dikunci Permanen!');
            return redirect()->route('project.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::error('Gagal mengunci data', $e->getMessage());
            return redirect()->back();
        }
    }

    public function finishProject(Request $request)
    {
        // dd($request->all());
        $rules = [
            'salary_day' => 'required',
            'salary_day.*' => 'required|numeric',
            'working_days' => 'required',
            'working_days.*' => 'required|numeric',
            'mandor_salary_day' => 'required',
            'mandor_salary_day.*' => 'required|numeric',
            'mandor_working_days' => 'required',
            'mandor_working_days.*' => 'required|numeric',
            'attendance_photos.*' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'description.*' => 'required|string|max:255',
        ];

        $messages = [
            'salary_day.required' => ' Tarif upah tukang harus diisi!',
            'salary_day.*.required' => ' Tarif upah tukang harus diisi!',
            'salary_day.*.numeric' => ' Tarif upah tukang harus berupa angka!',
            'working_days.required' => ' Jumlah hari kerja harus diisi!',
            'working_days.*.required' => ' Jumlah hari kerja harus diisi!',
            'working_days.*.numeric' => ' Jumlah hari kerja harus berupa angka!',
            'mandor_salary_day.required' => ' Tarif upah tukang harus diisi!',
            'mandor_salary_day.*.required' => ' Tarif upah tukang harus diisi!',
            'mandor_salary_day.*.numeric' => ' Tarif upah tukang harus berupa angka!',
            'mandor_working_days.required' => ' Jumlah hari kerja harus diisi!',
            'mandor_working_days.*.required' => ' Jumlah hari kerja harus diisi!',
            'mandor_working_days.*.numeric' => ' Jumlah hari kerja harus berupa angka!',
            'attendance_photos.*.required' => ' Setiap foto diperlukan!.',
            'attendance_photos.*.image' => ' Setiap foto harus berupa gambar!.',
            'attendance_photos.*.mimes' => 'Hanya tipe JPEG dan PNG yang diperbolehkan!.',
            'attendance_photos.*.max' => ' Setiap gambar harus berukuran kurang dari 2MB!.',
            'description.*.required' => ' Setiap foto harus memiliki deskripsi!.',
            'description.*.string' => ' Deskripsi harus berupa string yang valid!.',
            'description.*.max' => ' Deskripsi maksimum ditulis sebanyak 255 karakter!.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            Alert::error('Gagal memproses data', $validator->errors()->all());
            return redirect()->back();
        }
        DB::beginTransaction();
        try {
            $id = Crypt::decryptString($request->prid);
            Projects::where('id', $id)->firstOrFail()->update([
                'status' => 2
            ]);
            for ($i = 0; $i < count($request->wrid); $i++) {
                ProjectWorkers::where('id', Crypt::decryptString($request->wrid[$i]))->firstOrFail()->update([
                    'working_days' => $request->working_days[$i],
                    'salary_day' => $request->salary_day[$i],
                    'total_salary' => $request->working_days[$i] * $request->salary_day[$i]
                ]);
            }
            for ($i = 0; $i < count($request->mandor_wrid); $i++) {
                ProjectWorkers::where('id', Crypt::decryptString($request->mandor_wrid[$i]))->firstOrFail()->update([
                    'working_days' => $request->mandor_working_days[$i],
                    'salary_day' => $request->mandor_salary_day[$i],
                    'total_salary' => $request->mandor_working_days[$i] * $request->mandor_salary_day[$i]
                ]);
            }
            $photos = $request->file('attendance_photos');
            $description = $request->input('description');

            foreach ($photos as $index => $photo) {
                $filename = $id . '-VLBT-' . $description[$index] . '.' . $photo->extension();
                $photo->move('assets/projects', $filename);

                ProjectEvidences::create([
                    'project_id' => $id,
                    'photo_path' => 'assets/projects/' . $filename,
                    'description' => $description[$index],
                ]);
            }
            DB::commit();
            Alert::success('Berhasil', 'Proyek : berhasil diselesaikan!');
            return redirect()->route('project.index');
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e);
            Alert::error('Gagal menyelesaikan proyek!', $e->getMessage());
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
