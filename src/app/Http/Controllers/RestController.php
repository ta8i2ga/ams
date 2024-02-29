<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\support\Facades\Auth;
use App\Models\Rest;
use Carbon\Carbon;
use App\Models\Attendance;


class RestController extends Controller
{
    public function break_start()
    {
        $user = Auth::user();
        $startBreakTime = Carbon::now();
        $rest = new Rest;
        $attendance = new Attendance;
        $attendance->validateAtWork($startBreakTime);
        $rest->validateEndBreak($startBreakTime);
        $attendance->validateNotEndWork($startBreakTime);
        Rest::create([
            'date' => $startBreakTime->format('Y-m-d'),
            'start_break' => $startBreakTime->format('Y-m-d H:i:s'),
            'user_id' => $user->id,
        ]);
        return redirect('/');
    }

    public function break_end()
    {
        $user = Auth::user();
        $endBreakTime = Carbon::now();
        $rest = new Rest;
        $attendance = new Attendance;
        $rest->validateStartBreak($endBreakTime);
        $attendance->validateAtWork($endBreakTime);
        $attendance->validateNotEndWork($endBreakTime);
        Rest::create([
            'date' => $endBreakTime->format('Y-m-d'),
            'end_break' => $endBreakTime->format('Y-m-d H:i:s'),
            'user_id' => $user->id,
        ]);
        return redirect('/');
    }
}
