<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Rest;

class AttendanceController extends Controller
{
    public function work_start()
    {
        $startAttendanceTime = Carbon::now();
        $attendance = new Attendance;
        $attendance->validateBeforeWork($startAttendanceTime);
        Attendance::create([
            'date' => $startAttendanceTime->format('Y-m-d'),
            'start_working' => $startAttendanceTime->format('Y-m-d H:i:s'),
            'user_id' => Auth::id()
        ]);
        return redirect('/');
    }

    public function work_end()
    {
        $endAttendanceTime = Carbon::now();
        $attendance = new Attendance;
        $attendance->validateAtWork($endAttendanceTime);
        $attendance->validateNotEndWork($endAttendanceTime);
        $rest = new Rest;
        $rest->validateEndBreak($endAttendanceTime);
        Attendance::create([
            'date' => $endAttendanceTime->format('Y-m-d'),
            'end_working' => $endAttendanceTime->format('Y-m-d H:i:s'),
            'user_id' => Auth::id()
        ]);
        return redirect('/');
    }
}
