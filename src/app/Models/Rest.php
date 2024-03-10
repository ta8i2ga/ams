<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Rest extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
        'created_at',
        'update_at',
    ];

    protected $fillable = [
        'user_id',
        'date',
        'start_break',
        'end_break',
    ];

    public function attendance()
    {
        return $this->belongsTo(Attendance::class, 'attendance_id');
    }

    public function validateEndBreak($now): void
    {
        $lastStartBreak = self::where('date', $now->format('Y:m:d'))->where('user_id', Auth::id())->whereNotNull('start_break')->max('start_break');
        $lastEndBreak = self::where('date', $now->format('Y:m:d'))->where('user_id', Auth::id())->whereNotNull('end_break')->max('end_break');
        $result = $lastStartBreak > $lastEndBreak;
        if ($result) {
            throw ValidationException::withMessages(['start_working' => ['未だ休憩を終了していません。'],]);
        }
    }

    public function validateStartBreak($now): void
    {
        $lastStartBreak = self::where('date', $now->format('Y:m:d'))->where('user_id', Auth::id())->whereNotNull('start_break')->max('start_break');
        $lastEndBreak = self::where('date', $now->format('Y:m:d'))->where('user_id', Auth::id())->whereNotNull('end_break')->max('end_break');
        $result = $lastStartBreak <= $lastEndBreak;
        //ddd($result);
        if ($result) {
            throw ValidationException::withMessages(['start_working' => ['未だ休憩を開始していません。'],]);
        }
    }

    public function calculateTotalBreakTime()
    {
        $totalBreakTime = 0;

        // 各休憩記録に対してループ
        foreach ($this->rests as $rest) {
            // 開始時刻と終了時刻の取得
            $startTime = Carbon::parse($rest->start_break);
            $endTime = Carbon::parse($rest->end_break);

            // 時間差の計算（分単位）
            $breakDuration = $endTime->diffInMinutes($startTime);

            // 合計に加算
            $totalBreakTime += $breakDuration;
        }

        return $totalBreakTime; // 分単位での合計休憩時間を返す
    }
}
