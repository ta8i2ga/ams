<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

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
}
