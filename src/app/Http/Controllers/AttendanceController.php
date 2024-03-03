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

        // ユーザーごとの情報を取得
        $workAndBreakInfo = $this->getWorkAndBreakInfo($date);

        // ページネーションを適用して5件ずつ表示
        $workAndBreakInfoPaginated = collect($workAndBreakInfo)->paginate(5);

        return view('attendance', compact('workAndBreakInfoPaginated'));
    }

    public function getWorkAndBreakInfo($date)
    {
        // ユーザーごとの情報を格納する配列を初期化
        $workAndBreakInfo = [];

        $users = User::all(); // すべてのユーザー情報を取得

        // ユーザーごとに処理
        foreach ($users as $user) {

            $userAttendances = Attendance::where('user_id', $user->id)
                ->whereDate('date', $date)
                ->whereNotNull('start_working')
                ->whereNotNull('end_working')
                ->get('start_working', 'end_working');


            //dd();

            $userRests = Rest::where('user_id', $user->id)
                ->whereDate('date', $date)
                ->whereNotNull('start_break')
                ->whereNotNull('end_break')
                ->get(['start_break', 'end_break']);

            // フォーマットした休憩開始時刻と終了時刻を格納する配列
            $formattedRests = [];
            foreach ($userRests as $rest) {
                $formattedRests[] = [
                    'start_break' => Carbon::parse($rest->start_break)->format('H:i:s'),
                    'end_break' => Carbon::parse($rest->end_break)->format('H:i:s')
                ];
            }

            // フォーマットした休憩開始時刻と終了時刻を格納する配列
            $formattedRests = [];
            $totalBreakTime = 0;
            for ($i = 1; $i < count($userRests); $i += 2) {
                $startBreak = $userRests[$i - 1]->start_break;
                $endBreak = $userRests[$i]->end_break;

                // 休憩開始時刻と終了時刻の差を計算し、合計休憩時間に加算
                if ($startBreak && $endBreak) {
                    $start = Carbon::parse($startBreak);
                    $end = Carbon::parse($endBreak);
                    $breakDuration = $start->diffInSeconds($end);
                    $totalBreakTime += $breakDuration;
                }
            }

            // 休憩時間の合計を時:分:秒の形式にフォーマット
            $totalBreakTimeFormatted = gmdate('H:i:s', $totalBreakTime);

            // ユーザーごとの情報を配列に追加
            $workAndBreakInfo[] = [
                'name' => $user->name,
                'start_working' => $formattedStartWorking ?? '---',
                'end_working' => $formattedEndWorking ?? '---',
                'total_work_time' => $totalWorkTime ?? '---',
                'total_break_time' => $totalBreakTimeFormatted,
            ];
        }

        return $workAndBreakInfo;
    }
}
