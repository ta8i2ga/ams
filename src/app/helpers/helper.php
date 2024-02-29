<?php

use App\Models\Attendance;
use App\Models\Rest;
use Carbon\Carbon;

/**
 * 指定した日の勤務情報と休憩情報を取得し、ビューで表示するためのメソッド
 *
 * @param string|null $date
 * @return array
 */
function getWorkAndBreakInfo($date)
{
    // 指定された日の勤務と休憩情報を取得
    $attendances = Attendance::whereDate('date', $date)->get();
    $rests = Rest::whereDate('date', $date)->get();

    $workAndBreakInfo = [];

    // 各ユーザーの情報を取得
    foreach ($attendances as $attendance) {
        $userId = $attendance->user_id;
        $name = $attendance->user->name;

        // 勤務開始時間と終了時間を取得
        $startWorking = $attendance->start_working;
        $endWorking = $attendance->end_working;

        // 勤務合計時間を計算
        $totalWorkTime = null;
        if ($startWorking && $endWorking) {
            $start = Carbon::parse($startWorking);
            $end = Carbon::parse($endWorking);
            $totalWorkTime = $start->diff($end)->format('%H:%I:%S');
        }

        // 休憩合計時間を計算
        $totalBreakTime = 0;
        foreach ($rests as $rest) {
            if ($rest->user_id === $userId) {
                $startBreak = Carbon::parse($rest->start_break);
                $endBreak = Carbon::parse($rest->end_break);
                $totalBreakTime += $startBreak->diffInSeconds($endBreak);
            }
        }
        $totalBreakTime = gmdate('H:i:s', $totalBreakTime);

        // ユーザーごとの情報を配列に追加
        $workAndBreakInfo[] = [
            'name' => $name,
            'start_working' => $startWorking ?? '---',
            'end_working' => $endWorking ?? '---',
            'total_work_time' => $totalWorkTime ?? '---',
            'total_break_time' => $totalBreakTime,
        ];
    }

    return $workAndBreakInfo;
}
