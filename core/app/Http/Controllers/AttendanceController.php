<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Alert;
use App\Models\Attendances;
use App\Models\Absents;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Intervention\Image\ImageManagerStatic as Image;
use Google_Client;
use Google_Service_Calendar;

class AttendanceController extends Controller
{
    public function index()
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
        $shift = User::with('shift')->where('id', Auth::user()->id)->first();
        $attend = Attendances::where('user_id', Auth::user()->id)->whereDate('created_at', Carbon::today())->first();

        $client = new Google_Client();
        $client->setAuthConfig(storage_path('app/service-account.json')); // Path to service account JSON
        $client->setScopes(Google_Service_Calendar::CALENDAR_READONLY);
        $service = new Google_Service_Calendar($client);

        $calendarId = 'id.indonesian#holiday@group.v.calendar.google.com';
        $today =  Carbon::now()->toRfc3339String();
        $tomorrow = Carbon::now()->addDay()->toRfc3339String();

        $optParams = [
            'timeMin' => $today,
            'timeMax' => $tomorrow,
            'singleEvents' => true,
            'orderBy' => 'startTime',
        ];

        $events = $service->events->listEvents($calendarId, $optParams);
        $isHoliday = false;
        $hTitle = '';

        foreach ($events->getItems() as $event) {
            $holidayDate = Carbon::parse($event->getStart()->getDate())->format('Y-m-d');
            if ($holidayDate === Carbon::now()->format('Y-m-d')) {
                $isHoliday = true;
                $hTitle = $event->getSummary();
                break; // If today is a holiday, no need to check further
            }
        }
        return view('attendance.index', compact('shift', 'attend', 'months', 'years', 'isHoliday', 'hTitle'));
        // dd($isHoliday,$hTitle,$optParams,$events);
    }

    public function getReport(Request $request)
    {
        $year = Crypt::decryptString($request->year);
        $month = Crypt::decryptString($request->month);

        $start = Carbon::create($year, $month, 1);
        $end = $start->copy()->endOfMonth();

        $period = CarbonPeriod::create($start, $end);
        $dates = [];

        foreach ($period as $date) {
            $dates[] = $date->format('Y-m-d');
        }
        $user = Auth::user();
        $attendances = Attendances::where('user_id', $user->id)->whereBetween('created_at', [$start, $end])->get()->map(function ($attendance) {
            $attendance->formatted_date = Carbon::parse($attendance->created_at)->format('Y-m-d');
            return $attendance;
        });

        $attByDate = $attendances->keyBy('formatted_date');

        $lists = [];
        foreach ($dates as $date) {
            $formattedDate = Carbon::parse($date)->format('Y-m-d');

            // Check if the date falls within an absence range
            $absence = $user->absents->first(function ($abs) use ($formattedDate) {
                return Carbon::parse($abs->start_date)->format('Y-m-d') <= $formattedDate
                    && Carbon::parse($abs->end_date)->format('Y-m-d') >= $formattedDate;
            });

            if ($absence) {
                $absentTypeSymbol = strtoupper(substr($absence->master->name, 0, 1));
                $lists[] = [
                    'date' => $date,
                    'status' => $absence->master->name
                ];
            } else if (isset($attByDate[$date])) {
                $attendance = $attByDate[$date];
                if (isset($attendance->time_in) && isset($attendance->time_out)) {
                    if ($attendance->approved == 2 && $attendance->approved_out == 2) {
                        $status = "Hadir";
                    } else {
                        $status = "Menunggu Persetujuan";
                    }

                } else if (!isset($attendance->time_out)) {
                    $status = 'Belum Presensi Keluar';
                }
                $lists[] = [
                    'date' => $date,
                    'time_in' => $attendance->time_in,
                    'time_out' => $attendance->time_out,
                    'status' => $status
                ];
            } else {
                $lists[] = [
                    'date' => $date,
                    'status' => 'Tidak Hadir'
                ];
            }
        }

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
        $html = view('attendance.report', compact('lists','holidays'))->render();
        return response()->json($html, 200);
        // dd($lists);
    }

    public function attendance()
    {
        $check = Attendances::where('user_id', Auth::user()->id)->whereDate('time_in', Carbon::today())->exists();
        if ($check) {
            Alert::info('Anda sudah presensi masuk', 'Presensi berikutnya akan direkam sebagai presensi keluar!');
        }
        return view('attendance.record', compact('check'));
    }
    public function store(Request $request)
    {
        $rules = [
            'photo' => 'required'
        ];

        $messages = [
            'photo.required' => 'Mohon mengambil gambar selfie'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            Alert::error('Gagal Presensi', $validator->errors()->all());
            return redirect()->back();
        }
        try {
            $user = Auth::user();
            $shift = $user->shift;

            if (!$shift) {
                Alert::error('Gagal Presensi', 'Karyawan ini belum memiliki Shift');
                return redirect()->back();
            }

            $curTime = Carbon::now();

            $photo = $request->photo;
            $data = explode(',', $photo);
            if (count($data) != 2 || strpos($data[0], 'data:image/jpeg;base64') === false) {
                Alert::error('Gagal Presensi', 'Format gambar tidak sesuai');
                return redirect()->back();
            }

            $decoded = base64_decode($data[1]);
            $image = Image::make($decoded);

            if ($image->mime() !== 'image/jpeg') {
                Alert::error('Gagal Presensi', 'Jenis gambar tidak sesuai');
                return redirect()->back();
            }

            $image->resize(854, 854);
            $f_name = 'attendance_photos/' . $user->id . '-' . now()->timestamp . '.jpeg';
            Storage::disk('assets')->put($f_name, (string) $image->encode('jpeg'));
            // $location = asset('assets/attendance_photos/'.$f_name);
            // (string) $image->encode('jpeg')->save($location);
            // Storage::disk('assets')->put($f_name, (string) $image->encode('jpeg'));
            $check = Attendances::where('user_id', $user->id)->whereDate('time_in', Carbon::today())->exists();
            if (!$check) {
                Attendances::create([
                    'user_id' => $user->id,
                    'time_in' => $curTime,
                    'time_out' => null,
                    'photo_path' => $f_name,
                ]);
            } else {
                Attendances::where('user_id', $user->id)->whereDate('time_in', Carbon::today())->update([
                    'time_out' => $curTime,
                    'approved_out' => 1,
                    'photo_path_out' => $f_name
                ]);
            }
            Alert::success('Berhasil', 'Presensi Berhasil');
            return redirect()->route('attendance.index');
        } catch (\Exception $e) {
            Alert::error('Gagal', $e->getMessage());
            return redirect()->back();
        }
    }

    public function validateIndex()
    {
        $users = User::orderBy('name')->get();
        return view('attendance.validate.index', compact('users'));
    }

    public function detailValidate($id)
    {
        $id = Crypt::decryptString($id);
        $attendances = Attendances::where('user_id', $id)->get();
        $name = User::where('id', $id)->firstOrFail()->value('name');
        return view('attendance.validate.view', compact('attendances', 'name'));
    }

    public function doValidate(Request $request)
    {
        if ($request->_method == 'POST' && isset($request->_token)) {
            try {
                $data = Attendances::where('id', Crypt::decryptString($request->attid))->firstOrFail();
                if (isset($request->attendance)) {
                    foreach ($request->attendance as $att) {
                        $type = Crypt::decryptString($att);
                        if ($type == 'in') {
                            $data->approved = 2;
                        } elseif ($type == 'out') {
                            $data->approved_out = 2;
                        }
                        $data->save();
                    }
                } else {
                    Alert::error('Gagal', 'Checklist Validasi yang diinginkan!');
                    return redirect()->back();
                }
                Alert::success('Berhasil', 'Validasi Berhasil');
                return redirect()->back();
            } catch (\Exception $e) {
                Alert::error('Gagal', $e->getMessage());
                return redirect()->back();
            }

        } else {
            Abort('404');
        }
    }
}
