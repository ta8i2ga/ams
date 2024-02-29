<style>
    * {
        font-weight: bold;
    }

    .header {
        width: 100%;
        display: flex;
        justify-content: space-between;
        text-align: center;
        padding: 0 40px;
        height: 70px;
        align-items: center;
    }

    .nav_list {
        display: flex;
        list-style: none;
    }

    a {
        text-decoration: none;
        color: black;
    }

    li {
        margin-right: 50px;
    }
</style>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/sanitize.css" rel="stylesheet" />
    <title>Atte</title>
</head>

<body>
    <header>
        <div class="header">
            <div class="header_logo">
                <h1>Atte</h1>
            </div>
            <nav class="header_nav">
                <ul class="nav_list">
                    <li><a href="/">ホーム</a></li>
                    <li><a href="{{ route('atte') }}">日付一覧</a></li>
                    <li><a href="{{ route('logout') }}">ログアウト</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main>
        <div class="container">
            <p class="date">{{"年月日"}}</p>
            <table>
                <tr>
                    <th>名前</th>
                    <th>勤務開始</th>
                    <th>勤務終了</th>
                    <th>休憩時間</th>
                    <th>勤務時間</th>
                </tr>
            </table>
            @php
            $date = request()->input('date', now()->toDateString());
            $workAndBreakInfo = getWorkAndBreakInfo($date); // デフォルトは今日の日付を使用
            $workAndBreakInfo = new Illuminate\Pagination\LengthAwarePaginator($workAndBreakInfo, count($workAndBreakInfo), 5);
            @endphp

            @if (!empty($workAndBreakInfo))
            <table>
                <tr>
                    <th>ユーザー名</th>
                    <th>勤務開始時間</th>
                    <th>勤務終了時間</th>
                    <th>合計勤務時間</th>
                    <th>合計休憩時間</th>
                </tr>
            </table>
            @endif

            @foreach ($workAndBreakInfo as $info)
            <p>ユーザー名: {{ $info['name'] }}</p>
            <p>勤務開始時間: {{ $info['start_working'] }}</p>
            <p>勤務終了時間: {{ $info['end_working'] }}</p>
            <p>勤務合計時間: {{ $info['total_work_time'] }}</p>
            <p>休憩合計時間: {{ $info['total_break_time'] }}</p>
            @endforeach

            {{ $workAndBreakInfo->links() }}
        </div>
    </main>
</body>

</html>