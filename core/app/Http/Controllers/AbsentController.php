<?php

namespace App\Http\Controllers;

use Alert;
use Validator;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Absents;
use Illuminate\Http\Request;
use App\Models\AbsentMasters;
use Illuminate\Support\Facades\Crypt;

class AbsentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (hasRole(['admin', 'superadmin'])) {
            $absences = Absents::orderBy('start_date')
            ->join('users', 'absents.created_by', '=', 'users.id')
            ->with('master')
            ->select('absents.*', 'users.name as created_by_name')
            ->get();
        }
        else if (hasRole(['spv', 'mandor'])) {
            $absences = Absents::orderBy('start_date')
            ->join('users', 'absents.created_by', '=', 'users.id')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->with('master')
            ->select('absents.*', 'users.name as created_by_name')
            ->where('users.id', auth()->user()->id)
            ->orWhere('users.parent_id', auth()->user()->id)
            ->get();
        } 
        else{
            $absences = Absents::orderBy('start_date')
            ->join('users', 'absents.created_by', '=', 'users.id')
            ->with('master')
            ->select('absents.*', 'users.name as created_by_name')
            ->orWhere('users.id', auth()->user()->id)
            ->get();
        }
        return view('attendance.absent.index', compact('absences'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $masters = AbsentMasters::orderBy('id')->get();
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $end_date = Absents::where('created_by', auth()->user()->id)
            ->where('status', 1)
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->max('end_date');
        $minimum = $end_date ? Carbon::parse($end_date)->addDay()->format('d-m-Y') : Carbon::now()->format('d-m-Y');
        return view('attendance.absent.create', compact('masters', 'minimum'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'absent_type' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ];

        $messages = [

            'absent_type.required' => ' Jenis ketidakhadiran harus dipilih!',
            'start_date.required' => ' Tanggal Mulai harus dipilih!',
            'end_date.required' => ' Tanggal Selesai harus dipilih!',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            Alert::error('Gagal mengajukan', $validator->errors()->all());
            return redirect()->back();
        }
        $type_id = Crypt::decryptString($request->absent_type);
        $type = AbsentMasters::where('id', $type_id)->firstOrFail();
        if ($type->evc === 1) {
            $rules = [
                'evidence' => 'required|mimes:jpeg,jpg|max:2048',
            ];
            $messages = [
                'evidence.required' => ' Bukti ketidakhadiran harus diunggah!',
                'evidence.mimes' => ' Bukti ketidakhadiran harus bertipe JPEG/JPG!',
                'evidence.max' => ' Bukti ketidakhadiran maksimal berukuran 2MB!',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                Alert::error('Gagal mengajukan', $validator->errors()->all());
                return redirect()->back();
            }
        }
        try {
            if ($request->evidence) {
                $filename = auth()->user()->id . '' . $type_id . '-' . now()->timestamp . '.' . $request->evidence->extension();
                $request->evidence->move('assets/absent_photos', $filename);
            }
            $data = [
                'absent_id' => $type_id,
                'start_date' => Carbon::createFromFormat('d-m-Y', $request->start_date)->format('Y-m-d'),
                'end_date' => Carbon::createFromFormat('d-m-Y', $request->end_date)->format('Y-m-d'),
                'evidence_file' => $request->evidence ? 'assets/absent_photos/' . $filename : null,
                'status' => FALSE,
                'created_by' => auth()->user()->id
            ];
            Absents::create($data);
            Alert::success('Berhasil', 'Pengajuan tidak hadir berhasil dilakukan, tunggu verifikasi!');
            return redirect()->route('attendance.absent.index');
        } catch (\Exception $e) {
            Alert::error('Gagal mengajukan', $e->getMessage());
            return redirect()->back();
        }
        // dd($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function detail(string $id)
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $id = Crypt::decryptString($id);
        $absent = Absents::where('id', $id)->with('master', 'user_created', 'user_validated')->firstOrFail();
        $quota = Absents::where('created_by', auth()->user()->id)
            ->where('status', 1)
            ->where('absent_id', $absent->absent_id)
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();
        return view('attendance.absent.show', compact('absent', 'quota'));
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
            $id = Crypt::decryptString($request->absence);
            Absents::where('id', $id)->firstOrFail()->update([
                'notes' => $request->notes,
                'status' => Crypt::decryptString($request->validation),
                'validated_by' => auth()->user()->id,
            ]);
            Alert::success('Berhasil', 'Pengajuan berhasil divalidasi');
            return redirect()->route('attendance.absent.index');
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
