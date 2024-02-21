<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\AttendanceController;
use Carbon\Carbon;
use App\Models\Attendance;
use App\Models\Rest;


class AuthController extends Controller
{
    /*public function index()
    {
        $user = Auth::user();
        return view('index')->with('user', $user);
    }*/

    public function index()
    {
        // 現在の日付を取得
        $currentDateFormatted = now()->format('Y-m-d');

        // ログインユーザーの情報を取得
        $user = Auth::user();

        // 今日の勤怠情報を取得
        $attendancesToday = Attendance::where('user_id', $user->id)
            ->whereDate('date', $currentDateFormatted)
            ->get();

        $restsToday = Rest::where('user_id', $user->id)
            ->whereDate('date', $currentDateFormatted)
            ->get();

        // 勤務開始ボタンの活性/非活性を制御
        $enableStartButton = $attendancesToday->isEmpty() || !$attendancesToday->first()->start_working;

        // 勤務終了ボタンの活性/非活性を制御
        $enableEndButton = !$attendancesToday->isEmpty() && $attendancesToday->last()->start_working && !$attendancesToday->last()->end_working;

        // 休憩開始ボタンの活性/非活性を制御
        $enableBreakStartButton = !$attendancesToday->isEmpty() &&  $attendancesToday->last()->start_working;

        // 休憩終了ボタンの活性/非活性を制御
        $enableBreakEndButton = !$attendancesToday->isEmpty() && !$restsToday->isEmpty() && $attendancesToday->last()->start_working && $restsToday->last()->start_break && !$restsToday->last()->end_break;
        //dd(!$restsToday->isEmpty());
        // その他の必要な情報をビューに渡す
        return view('index', compact('enableStartButton', 'enableEndButton', 'enableBreakStartButton', 'enableBreakEndButton', 'user'));
    }
}
