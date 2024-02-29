<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Rest;

class Attendance extends Model
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
        'start_working',
        'end_working',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rests()
    {
        return $this->hasMany(Rest::class);
    }

    public static $rules = array(
        'date' => 'required',
        'user_id' => 'required',
    );

    public function validateBeforeWork($now): void
    {
        $result = self::query()->where('date', $now->format('Y:m:d'))->whereNotNull('start_working')->where('user_id', Auth::id())->exists();
        if ($result) {
            throw ValidationException::withMessages(['start_working' => ['既に出勤登録しています。'],]);
        }
    }

    public function validateAtWork($now): void
    {
        $result = self::query()->where('date', $now->format('Y:m:d'))->whereNotNull('start_working')->where('user_id', Auth::id())->doesntExist();
        if ($result) {
            throw ValidationException::withMessages(['start_working' => ['未だ出勤登録していません。'],]);
        }
    }

    public function validateNotEndWork($now): void
    {
        $result = self::query()->where('date', $now->format('Y:m:d'))->whereNotNull('end_working')->where('user_id', Auth::id())->exists();
        if ($result) {
            throw ValidationException::withMessages(['start_working' => ['既に退勤登録しています。'],]);
        }
    }

    public function calculateTotalWorkingTime()
    {
        // 勤務時間合計を初期化
        $totalWorkingTime = 0;

        // 勤務開始時間と勤務終了時間が設定されている場合のみ計算を行う
        if ($this->start_working && $this->end_working) {
            // 勤務開始時刻と勤務終了時刻をCarbonオブジェクトに変換
            $startWorking = Carbon::parse($this->start_working);
            $endWorking = Carbon::parse($this->end_working);

            // 勤務時間を計算（休憩時間は考慮されない）
            $totalWorkingTime = $endWorking->diffInMinutes($startWorking);
        }

        return $totalWorkingTime;
    }
}
