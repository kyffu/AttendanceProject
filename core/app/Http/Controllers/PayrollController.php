<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\User;
use App\Models\Attendances;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\AbsentMasters;
use App\Models\Reimbursments;
use App\Models\Overtimes;
use Google_Client;
use Google_Service_Calendar;
use App\Models\Allowances;
use App\Models\AllowancePayrolls;
use Validator;
use Alert;
use Illuminate\Support\Facades\DB;

class PayrollController extends Controller
{
    public function index()
    {
        if (hasRole(['admin', 'superadmin'])) {
            $users = User::orderBy('name')->get();
        }
        else if (hasRole(['spv'])) {
            $users = User::with('attendances')
            ->where('role', 'Karyawan')
            ->orWhere('id', auth()->id())
            ->orderBy('users.name')
            ->get();
        } 
        else if (hasRole(['mandor'])) {
            $users = User::with('attendances')
            ->where('role', 'Tukang')
            ->orWhere('id', auth()->id())
            ->orderBy('users.name')
            ->get();
        } 
        else{
            $users = User::where('id',auth()->user()->id)->firstOrFail();
        }
        $years = Attendances::distinct()
                ->selectRaw('YEAR(created_at) as year')
                ->get()
                ->pluck("year");
            return view('reports.payroll.index', compact('users', 'years'));
    }

    public function getData(Request $request)
    {
        $year = Crypt::decryptString($request->year);
        $user = Crypt::decryptString($request->staff);
        $months = Attendances::distinct()->selectRaw('MONTH(created_at) as month')
            ->where('user_id', $user)
            ->whereYear('created_at', $year)
            ->get();
        $username = User::where('id', $user)->firstOrFail()->value('name');
        $allowances = Allowances::orderBy('id')->get();
        $html = view('reports.payroll.month', compact('year', 'months', 'username', 'user', 'allowances'))->render();
        return response()->json(['html' => $html], 200);
    }
    public function show(Request $request)
    {
        $decrypted = Crypt::decryptString($request->payroll);
        $raw = explode('/', $decrypted);
        $times = explode('-', $raw[0]);
        $user_id = $raw[1];
        $year = $times[0];
        $month = $times[1];

        $title = $year . '-' . $month;
        $start = Carbon::create($year, $month, 1);
        $end = $start->copy()->endOfMonth();

        $period = CarbonPeriod::create($start, $end);
        $dates = [];

        foreach ($period as $date) {
            $dates[] = $date->format('Y-m-d');
        }
        $user = User::where('id', $user_id)->with('attendances', 'position.salaries')->orderBy('users.name')->firstOrFail();

        $masters = AbsentMasters::select('name')->get();
        $reimburses = Reimbursments::where('user_id', $user_id)->where('status', 'validated')
            ->whereRaw('YEAR(reimbursement_date) = ? AND MONTH(reimbursement_date) = ?', [$year, $month])->get();
        $overtimes = Overtimes::where('user_id', $user_id)->where('status', 'approved')
            ->whereRaw('YEAR(date) = ? AND MONTH(date) = ?', [$year, $month])->get();
        $allowance = AllowancePayrolls::where('period', $title)->where('employee_id', $user_id)->with('allowance')->first();
        // dd($reimburses);

        $client = new Google_Client();
        $client->setAuthConfig(storage_path('app/service-account.json')); // Path to service account JSON
        $client->setScopes(Google_Service_Calendar::CALENDAR_READONLY);
        $service = new Google_Service_Calendar($client);

        $calendarId = 'en.indonesian#holiday@group.v.calendar.google.com';
        $timeMin = Carbon::createFromDate($year, $month, 1)->startOfMonth()->toRfc3339String();
        $timeMax = Carbon::createFromDate($year, $month, 1)->endOfMonth()->toRfc3339String();

        $optParams = [
            'timeMin' => $timeMin,
            'timeMax' => $timeMax,
            'singleEvents' => true,
            'orderBy' => 'startTime',
        ];

        $events = $service->events->listEvents($calendarId, $optParams);
        $holidays = [];

        foreach ($events->getItems() as $event) {
            $holidayDate = Carbon::parse($event->getStart()->getDate())->format('Y-m-d');
            $holidays[] = $holidayDate;
        }
        $filename = 'Slip Upah ' . $user->name . ' - ' . $month . $year;
        // dd($holidays);
        if ($request->method === 'GET') {
            $html = view('reports.payroll.view', compact('dates', 'user', 'title', 'masters', 'reimburses', 'overtimes', 'holidays', 'filename', 'allowance'))->render();
            return response()->json(['html' => $html], 200);
        } else if ($request->method === 'PRINT') {
            $html = view('reports.payroll.print', compact('dates', 'user', 'title', 'masters', 'reimburses', 'overtimes', 'holidays', 'allowance'))->render();
            return response($html);
        }
    }
    public function allowances(Request $request)
    {
        $rules = [
            'allow' => 'required',
        ];

        $messages = [
            'allow.required' => ' Jenis tunjangan harus dipilih!',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            Alert::error('Gagal menambahkan', $validator->errors()->all());
            return redirect()->back();
        }
        DB::beginTransaction();
        try {
            $decrypted = Crypt::decryptString($request->payroll);
            $raw = explode('/', $decrypted);
            $times = explode('-', $raw[0]);
            $user_id = $raw[1];
            $year = $times[0];
            $month = $times[1];
            $allow = Crypt::decryptString($request->allow);
            if ($request->alid) {
                AllowancePayrolls::where('id', Crypt::decryptString($request->alid))->firstOrFail()->update([
                    'allow_id' => $allow,
                ]);
            } else {
                AllowancePayrolls::create([
                    'period' => $year . '-' . $month,
                    'allow_id' => $allow,
                    'employee_id' => $user_id
                ]);
            }
            DB::commit();
            Alert::success('Berhasil', 'Tunjangan berhasil diperbarui!');
            return redirect()->route('report.payroll.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::error('Gagal menambahkan', $e->getMessage());
            return redirect()->back();
        }
    }
    public function getAllow(Request $request)
    {
        $decrypted = Crypt::decryptString($request->payroll);
        $raw = explode('/', $decrypted);
        $times = explode('-', $raw[0]);
        $user_id = $raw[1];
        $year = $times[0];
        $month = $times[1];

        $allowance = AllowancePayrolls::where('period', $year . '-' . $month)->where('employee_id', $user_id)->first();
        if ($allowance) {
            return response()->json([
                'alwid' => $allowance->allow_id,
                'alid' => Crypt::encryptString($allowance->id),
            ]);
        }
    }
}
