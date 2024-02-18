<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\AttendanceController;
use Carbon\Carbon;
use App\Models\Attendance;


class AuthController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('index')->with('user', $user);
    }
}
