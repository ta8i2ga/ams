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

use Illuminate\Database\Eloquent\Model;

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

        $users = User::where(function ($query) use ($date) {
            $query->whereHas('attendances', function ($query) use ($date) {
                $query->whereDate('start_working', $date);
            })->orWhereHas('attendances', function ($query) use ($date) {
                $query->whereDate('end_working', $date);
            });
        })->orWhere(function ($query) use ($date) {
            $query->whereHas('rests', function ($query) use ($date) {
                $query->whereDate('start_break', $date);
            })->orWhereHas('rests', function ($query) use ($date) {
                $query->whereDate('end_break', $date);
            });
        })->with([
            'attendances' => function ($query) use ($date) {
                $query->whereDate('start_working', $date)->orWhereDate('end_working', $date);
            },
            'rests' => function ($query) use ($date) {
                $query->whereDate('start_break', $date)->orWhereDate('end_break', $date);
            }
        ])->paginate(5);

        foreach ($users as $user) {

            // 合計勤務時間を初期化
            $totalWorkTime = 0;

            foreach ($user->attendances as $attendance) {
                // 各出勤情報の勤務時間を計算して合計に加算する

                $totalWorkTime += strtotime($attendance->end_working) - strtotime($attendance->start_working);
                $user->work_time_hour = floor($totalWorkTime / 3600);     //勤務時間(秒)を3600で割ると、時間を求め、小数点を切り捨てる
                $user->work_time_min  = floor(($totalWorkTime - ($user->work_time_hour * 3600)) / 60);    //勤務時間(秒)から時間を引いた余りを60で割ると、分を求め、小数点を切り捨てる
                $user->work_time_s = $totalWorkTime - ($user->work_time_hour * 3600 + $user->work_time_min * 60);
            }
        }

        foreach ($users as $user) {
            $totalBreakTime = 0;

            foreach ($user->rests as $rest) {
                $user->totalBreakTime = 0;
                for ($i = 1; $i < count($user->rests); $i += 2) {
                    $startBreak = $user->rests[$i - 1]->start_break;
                    $endBreak = $user->rests[$i]->end_break;
                    //dd($user->rests[1]->end_break);
                    // 休憩開始時刻と終了時刻の差を計算し、合計休憩時間に加算
                    if ($startBreak && $endBreak) {
                        $breakDuration = strtotime($endBreak) - strtotime($startBreak);
                        $user->totalBreakTime += $breakDuration;
                    }
                }
            }
        }


        //dd(strtotime($attendance->start_working));
        //$workTotalTime = strtotime();

        /*
        // 各ユーザーごとにAttendanceテーブルからレコードを取得（start_workingがあるもの）
        $attendancesWithStartWorking = [];
        foreach ($users as $user) {
            $attendance = Attendance::where('user_id', $user->id)
                ->whereDate('date', $date)
                ->whereNotNull('start_working')
                ->pluck('start_working') // 'start_working' カラムの値のみを取得
                ->toArray(); // コレクションを連想配列に変換
            $attendancesWithStartWorking[$user->id] = $attendance;
        }

        // 各ユーザーごとにAttendanceテーブルからレコードを取得（end_workingがあるもの）
        $attendancesWithEndWorking = [];
        foreach ($users as $user) {
            $attendance = Attendance::where('user_id', $user->id)
                ->whereDate('date', $date)
                ->whereNotNull('end_working')
                ->get(['end_working']);
            $attendancesWithEndWorking[$user->id] = $attendance;
        }

        // 各ユーザーごとにRestテーブルからレコードを取得（start_breakがあるもの）
        $restsWithStartBreak = [];
        foreach ($users as $user) {
            $rest = Rest::where('user_id', $user->id)
                ->whereDate('date', $date)
                ->whereNotNull('start_break')
                ->whereNull('end_break')
                ->get(['start_break', 'end_break']);
            $restsWithStartBreak[$user->id] = $rest;
        }

        // 各ユーザーごとにRestテーブルからレコードを取得（end_breakがあるもの）
        $restsWithEndBreak = [];
        foreach ($users as $user) {
            $rest = Rest::where('user_id', $user->id)
                ->whereDate('date', $date)
                ->whereNotNull('end_break')
                ->whereNull('start_break')
                ->get(['start_break', 'end_break']);
            $restsWithEndBreak[$user->id] = $rest;
        }

        // 必要に応じてビューにデータを渡す
        return view('attendance', [
            'date' => $date,
            'users' => $users,
            'attendancesWithStartWorking' => $attendancesWithStartWorking,
            'attendancesWithEndWorking' => $attendancesWithEndWorking,
            'restsWithStartBreak' => $restsWithStartBreak,
            'restsWithEndBreak' => $restsWithEndBreak
        ]);*/
        return view('attendance', compact('users', 'date'));
    }
}
