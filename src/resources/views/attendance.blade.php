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

    .header_logo {
        font-size: 32px;
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

    main {
        width: 100%;
        height: 700px;
        background-color: rgb(240, 241, 243);
    }

    .container {
        margin: 0 auto;

    }

    .switch-container {
        display: flex;
        height: auto;
        justify-content: center;
        font-size: 20px;
        padding: 40px 0 50px 0;
    }

    form {
        color: blue;
        margin: 0 20px 0 20px;
        background-color: white;
    }

    button {

        width: 35px;
        border: blue 1px solid;
    }

    table {
        margin: 0 auto;
        height: 450px;
        width: 1060px;
        font-size: large;
        text-align: center;
    }

    tr {
        border-top: gray solid 2px;
    }

    tr :first-child {
        font-weight: bold;
    }

    th :first-child {
        padding: 0;
    }

    .footer {
        width: 100%;
        height: 50px;
        text-align: center;
    }

    .footer_text {
        padding: 15 0;
    }
</style>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/sanitize.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
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
            <div class="switch-container">
                <form action="{{ route('atte') }}" method="GET">
                    <!-- Carbonライブラリを使用して1日後の日付を計算 -->
                    @php
                    $prevDay = \Carbon\Carbon::parse($date)->subDay()->toDateString();
                    @endphp
                    <!-- 隠しフィールドとして1日後の日付をフォームに含める -->
                    <input type="hidden" name="date" value="{{ $prevDay }}">
                    <!-- フォーム送信ボタン -->
                    <button type="submit">
                        < </button>
                </form>
                <p>{{ $date }}</p>
                <form action="{{ route('atte') }}" method="GET">
                    <!-- Carbonライブラリを使用して1日後の日付を計算 -->
                    @php
                    $nextDay = \Carbon\Carbon::parse($date)->addDay()->toDateString();
                    @endphp
                    <!-- 隠しフィールドとして1日後の日付をフォームに含める -->
                    <input type="hidden" name="date" value="{{ $nextDay }}">
                    <!-- フォーム送信ボタン -->
                    <button type="submit">></button>
                </form>
            </div>

            <table>
                <tr>
                    <th>
                        <p>名前</p>
                    </th>
                    <th>
                        <p>勤務開始</p>
                    </th>
                    <th>勤務終了</th>
                    <th>休憩時間</th>
                    <th>勤務時間</th>
                </tr>
                @foreach ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ substr($user->attendances->first()->start_working,-8) }}</td>
                    <td>{{ substr($user->attendances->last()->end_working,-8) }}</td>
                    <td>{{ sprintf("%02d", $user->break_time_hour ?? 0) }}:{{ sprintf("%02d", $user->break_time_min ?? 0) }}:{{ sprintf("%02d", $user->break_time_s ?? 0) }}</td>
                    <td>{{ sprintf("%02d", $user->work_time_hour ?? 0) }}:{{ sprintf("%02d", $user->work_time_min ?? 0) }}:{{ sprintf("%02d", $user->work_time_s ?? 0) }}</td>
                </tr>
                @endforeach
            </table>
        </div>
        <div class="link">{{ $users->links() }}</div>
    </main>
    <footer class="footer">
        <div class="footer_text">
            <small>Atte,inc.</small>
        </div>
    </footer>
</body>

</html>