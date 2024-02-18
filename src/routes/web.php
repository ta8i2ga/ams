<?php

/*use Illuminate\Support\Facades\Route;*/

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\RestController;
use Faker\Guesser\Name;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


/*
Route::get('/register', [AuthController::class, 'getRegister']);
Route::post('/register', [AuthController::class, 'postRegister']);
Route::get('/login', [AuthController::class, 'getlogin'])->name('login');*/

Route::get('/', [AuthController::class, 'index'])->name('index')->middleware('auth');
/*Route::get('/attendance/start', [AttendanceController::class, 'startAttendance']);
Route::get('/attendance/end', [AttendanceController::class, 'endAttendance']);*/
Route::get('/attendance/start', [AttendanceController::class, 'work_start'])->middleware('auth');
Route::get('/attendance/end', [AttendanceController::class, 'work_end'])->name('work_end')->middleware('auth');
Route::get('/break/start', [RestController::class, 'break_start'])->name('break_start')->middleware('auth');
Route::get('/break/end', [RestController::class, 'break_end'])->name('break_end')->middleware('auth');
/*Route::get('/break/start', [RestController::class, 'startBreak']);
Route::get('/break/end', [RestController::class, 'endBreak']);*/

/*Route::get('/', function () {
    return view('welcome');
});*/

/*Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');*/

require __DIR__ . '/auth.php';
