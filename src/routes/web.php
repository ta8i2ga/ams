<?php

/*use Illuminate\Support\Facades\Route;*/

use App\Http\Controllers\AuthController;
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


Route::get('/register', [AuthController::class, 'getRegister']);
Route::post('/register', [AuthController::class, 'postRegister']);
Route::get('/login', [AuthController::class, 'getlogin'])->name('login');
Route::get('/index', [AuthController::class, 'index'])->name('index')->middleware('auth');


Route::get('/', function () {
    return view('welcome');
});

/*Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');*/

require __DIR__ . '/auth.php';
