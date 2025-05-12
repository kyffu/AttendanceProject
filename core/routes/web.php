<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    RoleController,
    UserController,
    ShiftController,
    AttendanceController,
    AuthController,
    AttendanceReportController,
    JobPositionController,
    SalaryController,
    AbsentMasterController,
    AbsentController,
    PayrollController,
    ReimburseController,
    OvertimeController,
    GeneralSettingController,
    ProjectController,
    AllowanceController
};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::group(['middleware' => 'auth'], function () {
    Route::controller(RoleController::class)->prefix('role')->name('role.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::get('{id}', 'detail')->name('detail');
        Route::put('update', 'update')->name('update');
        Route::put('{id}/update', 'updatePass')->name('updatepass');
        Route::delete('{id}/delete', 'destroy')->name('destroy');
    });
    //Start User Route
    Route::controller(UserController::class)->prefix('user')->name('user.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::get('{id}', 'detail')->name('detail');
        Route::put('update', 'update')->name('update');
        Route::put('{id}/update', 'updatePass')->name('updatepass');
        Route::delete('{id}/delete', 'destroy')->name('destroy');
    });
    //End User Route

    Route::controller(AttendanceController::class)->prefix('attendances/attendance')->name('attendance.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'getReport')->name('report');
        Route::get('record','attendance')->name('record');
        Route::post('present', 'store')->name('store');
    });
    Route::controller(AttendanceController::class)->prefix('attendances/validate')->name('attendance.validate.')->group(function () {
        Route::get('/', 'validateIndex')->name('index');
        Route::get('{id}','detailValidate')->name('detail');
        Route::post('/','doValidate')->name('do');
    });
    Route::controller(AbsentController::class)->prefix('attendances/absent')->name('attendance.absent.')->group(function(){
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::get('{id}', 'detail')->name('detail');
        Route::put('validate', 'validation')->name('validate');
        Route::delete('delete', 'destroy')->name('destroy');
    });
    Route::controller(ProjectController::class)->prefix('project')->name('project.')->group(function(){
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::get('{id}', 'detail')->name('detail');
        Route::put('update', 'update')->name('update');
        Route::put('lock', 'lockData')->name('lock');
        Route::post('finish', 'finishProject')->name('finish');
        Route::post('accept', 'validateProject')->name('accept');
        // Route::delete('delete', 'destroy')->name('destroy');
    });
    Route::controller(JobPositionController::class)->prefix('settings/position')->name('settings.position.')->group(function(){
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::get('{id}', 'detail')->name('detail');
        Route::put('update', 'update')->name('update');
        Route::delete('delete', 'destroy')->name('destroy');
    });
    Route::controller(SalaryController::class)->prefix('settings/salary')->name('settings.salary.')->group(function(){
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::get('{id}', 'detail')->name('detail');
        Route::put('update', 'update')->name('update');
        Route::delete('delete', 'destroy')->name('destroy');
    });
    Route::controller(AllowanceController::class)->prefix('settings/allowance')->name('settings.allowance.')->group(function(){
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::get('{id}', 'detail')->name('detail');
        Route::put('update', 'update')->name('update');
        Route::delete('delete', 'destroy')->name('destroy');
    });
    Route::controller(ShiftController::class)->prefix('settings/shift')->name('shift.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::get('{id}', 'detail')->name('detail');
        Route::put('update', 'update')->name('update');
        Route::delete('delete', 'destroy')->name('destroy');
    });
    Route::controller(AbsentMasterController::class)->prefix('settings/absent')->name('settings.absent.')->group(function(){
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::get('{id}', 'detail')->name('detail');
        Route::put('update', 'update')->name('update');
        Route::delete('delete', 'destroy')->name('destroy');
    });
    Route::controller(GeneralSettingController::class)->prefix('settings/general')->name('settings.general.')->group(function(){
        Route::get('/', 'index')->name('index');
        Route::put('update', 'update')->name('update');
        // Route::get('create', 'create')->name('create');
        // Route::post('store', 'store')->name('store');
        // Route::get('{id}', 'detail')->name('detail');
        // Route::delete('delete', 'destroy')->name('destroy');
    });
    Route::controller(AttendanceReportController::class)->prefix('reports/attendance')->name('report.attendance.')->group(function () {
        Route::get('/date', 'dateindex')->name('bydate.index');
        Route::post('/date', 'dategetReport')->name('bydate.get');
        Route::get('/staff', 'staffindex')->name('bystaff.index');
        Route::post('/staff', 'staffgetReport')->name('bystaff.get');
    });
    Route::controller(PayrollController::class)->prefix('reports/payroll')->name('report.payroll.')->group(function(){
        Route::get('/', 'index')->name('index');
        Route::post('/', 'getData')->name('get');
        Route::post('payroll', 'show')->name('show');
        Route::post('allow', 'allowances')->name('allow');
        Route::post('allowment', 'getAllow')->name('allow.get');
    });
    Route::controller(ReimburseController::class)->prefix('attendances/reimburse')->name('attendance.reimburse.')->group(function(){
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::get('{id}', 'detail')->name('detail');
        Route::put('validate', 'validation')->name('validate');
    });
    Route::controller(OvertimeController::class)->prefix('attendances/overtime')->name('attendance.overtime.')->group(function(){
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::get('{id}', 'detail')->name('detail');
        Route::put('validate', 'validation')->name('validate');
    });
    
});

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');