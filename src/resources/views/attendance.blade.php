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

            @foreach ($workAndBreakInfoPaginated as $info)
            <tr>
                <td>{{ $info['name'] }}</td>
                <td>{{ $info['start_working'] }}</td>
                <td>{{ $info['end_working'] }}</td>
                <td>{{ $info['total_work_time'] }}</td>
                <td>{{ $info['total_break_time'] }}<br></br></td>
            </tr>
            @endforeach

            {{ $workAndBreakInfoPaginated->links() }}
        </div>
    </main>
</body>

</html>