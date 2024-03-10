<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
    public function rests()
    {
        return $this->hasMany(Rest::class);
    }

    /**
     * 今日の勤務が開始されているかどうかをチェックします。
     *
     * @return bool
     */
    public function isWorkingToday()
    {
        // 今日の日付
        $today = Carbon::today();

        // 今日の日付での勤務データを取得
        $attendance = $this->attendances()->whereDate('date', $today)->first();

        // 勤務データが存在すれば、勤務が開始されているとみなします
        return $attendance !== null;
    }

    /**
     * 今日の勤務が終了しているかどうかをチェックします。
     *
     * @return bool
     */
    public function isWorkEndedToday()
    {
        // 今日の日付
        $today = Carbon::today();

        // 今日の日付での勤務データを取得
        $attendance = $this->attendances()->whereDate('date', $today)->first();

        // 勤務データが存在し、終了時間が設定されていれば、勤務が終了しているとみなします
        return $attendance !== null && $attendance->end_working !== null;
    }

    public function isBreakStarted()
    {
        // 休憩を開始しているかどうかのロジックを実装する
        // 休憩を開始している場合は true を返し、それ以外の場合は false を返す
    }

    public function isBreakEnded()
    {
        // 休憩が終了しているかどうかのロジックを実装する
        // 休憩が終了している場合は true を返し、それ以外の場合は false を返す
    }
}
