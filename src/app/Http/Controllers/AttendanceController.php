<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Rest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Helpers\helper;
use Illuminate\Pagination\Paginator;

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
            'end_working' => $endAttendanceTime->format('Y-m-d H:i:s'), // 日時形式で保存する
            'user_id' => Auth::id()
        ]);
        return redirect('/');
    }

    public function atte(Request $request)
    {
        // 指定された日付を取得（指定されていない場合は今日の日付）
        $date = $request->input('date', Carbon::today()->toDateString());

        // ヘルパー関数を使って勤務と休憩情報を取得
        $workAndBreakInfo = getWorkAndBreakInfo($date);

        // ページネーションを適用して5件ずつ表示
        $workAndBreakInfoPaginated = collect($workAndBreakInfo)->paginate(5);

        return view('attendance', compact('workAndBreakInfoPaginated'));
    }
}
