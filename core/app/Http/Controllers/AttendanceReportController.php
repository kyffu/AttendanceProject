<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendances;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use App\Exports\ViewExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\AbsentMasters;
use Google_Client;
use Google_Service_Calendar;

class AttendanceReportController extends Controller
{
    public function dateindex()
    {
        $months = [];
        setlocale(LC_TIME, 'IND');
        for ($month = 1; $month <= 12; $month++) {
            $date = Carbon::create(null, $month, 1);
            $formattedMonth = $date->formatLocalized('%B');
            $months[$month] = $formattedMonth;
        }
        $years = Attendances::distinct()
            ->selectRaw('YEAR(created_at) as year')
            ->get()
            ->pluck("year");

        return view('attendance.datereport.index', compact('months', 'years'));
    }

    public function dategetReport(Request $request)
    {
        $year = Crypt::decryptString($request->year);
        $month = Crypt::decryptString($request->month);
        $title = $year . '-' . $month;
        $start = Carbon::create($year, $month, 1);
        $end = $start->copy()->endOfMonth();

        $period = CarbonPeriod::create($start, $end);
        $dates = [];

        foreach ($period as $date) {
            $dates[] = $date->format('Y-m-d');
        }
        if (hasRole(['admin', 'superadmin'])) {
            $users = User::with('attendances')->orderBy('users.name')->get();
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
        
        else {
            $users = User::with('attendances')->where('id', auth()->user()->id)->get();
        }

        $masters = AbsentMasters::select('name')->get();

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
        if ($request->method === 'GET') {
            $html = view('attendance.datereport.view', compact('dates', 'users', 'title', 'masters', 'holidays'))->render();
            return response()->json(['html' => $html, 'dates' => $dates], 200);
        } elseif ($request->method === 'EXCEL') {
            $data = [
                'dates' => $dates,
                'users' => $users,
                'title' => $title,
                'masters' => $masters,
                'holidays' => $holidays,
            ];
            $filename = 'Lap Presensi Per Bulan ' . $title . '.xlsx';
            return Excel::download(new ViewExport('attendance.datereport.excel', $data), $filename);
        }
    }

    public function staffindex()
    {
        $months = [];
        setlocale(LC_TIME, 'IND');
        for ($month = 1; $month <= 12; $month++) {
            $date = Carbon::create(null, $month, 1);
            $formattedMonth = $date->formatLocalized('%B');
            $months[$month] = $formattedMonth;
        }
        $years = Attendances::distinct()
            ->selectRaw('YEAR(created_at) as year')
            ->get()
            ->pluck("year");
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
        
        else {
            $users = User::where('id', auth()->user()->id)->firstOrFail();
        }
        
        return view('attendance.staffreport.index', compact('months', 'years', 'users'));
    }

    public function staffgetReport(Request $request)
    {
        $year = Crypt::decryptString($request->year);
        $month = Crypt::decryptString($request->month);
        $user = Crypt::decryptString($request->staff);
        $title = User::where('id', $user)->firstOrFail()->value('name');
        $start = Carbon::create($year, $month, 1);
        $end = $start->copy()->endOfMonth();

        $period = CarbonPeriod::create($start, $end);
        $dates = [];

        foreach ($period as $date) {
            $dates[] = $date->format('Y-m-d');
        }
        $users = User::with('attendances')->where('id', $user)->firstOrFail();
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
        if ($request->method === 'GET') {
            $html = view('attendance.staffreport.view', compact('dates', 'users', 'title', 'holidays'))->render();
            return response()->json(['html' => $html], 200);
        } elseif ($request->method === 'EXCEL') {
            $data = [
                'dates' => $dates,
                'users' => $users,
                'title' => $title,
                'holidays' => $holidays,
            ];
            $filename = 'Lap Presensi Pegawai - ' . $title . '.xlsx';
            return Excel::download(new ViewExport('attendance.staffreport.excel', $data), $filename);
        }
    }
}
