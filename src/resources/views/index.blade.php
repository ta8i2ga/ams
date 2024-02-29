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

    .container {
        width: 100%;
        background-color: rgb(240, 241, 243);
        height: 700px;
        padding: 0 150px 120px 150px;
    }

    .container_top {
        width: 100%;
        height: 93px;
        padding-top: 10px;
    }

    .character {
        text-align: center;
        font-size: 25px;
    }

    .greeting {
        position: absolute;
        top: 5%;
        left: 50%;
        transform: translate(-50%, 0);
        margin: 20px auto;
    }

    .button {
        display: inline-block;
        background-color: white;
        width: 450px;
        height: 190px;
        padding: 20px 40px;
        position: absolute;
        top: 50%;
        left: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 10px auto;
        border-style: none;
        font-size: 20px;
    }

    .start_working {
        transform: translate(-105%, 50%);
    }

    .end_working {
        transform: translate(5%, 50%);
    }

    .start_break {
        transform: translate(-105%, 170%);
    }

    .end_break {
        transform: translate(5%, 170%);
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
            <div class="container_top">
                <p class="character">{{$user->name}}さんお疲れ様です！</p>
            </div>
            <div class="greeting">
                <form action="attendance/start" method="get">
                    @csrf
                    <button class="button start_working" {{ $enableStartButton ? '' : 'disabled' }} name="start_working">
                        <p>勤務開始</p>
                    </button>
                </form>
                <form action="attendance/end" method="get">
                    @csrf
                    <button class="button end_working" {{ $enableEndButton ? '' : 'disabled' }} name="end_working">
                        <p>勤務終了</p>
                    </button>
                </form>
                <form action="break/start" method="get">
                    @csrf
                    <button class="button start_break" {{ $enableBreakStartButton ? '' : 'disabled' }} name="start_break">
                        <p>休憩開始</p>
                    </button>
                </form>
                <form action="break/end" method="get">
                    @csrf
                    <button class="button end_break" {{ $enableBreakEndButton ? '' : 'disabled' }} name="end_break">
                        <p>休憩終了</p>
                    </button>
                </form>
            </div>
        </div>
    </main>
    <footer class="footer">
        <div class="footer_text">
            <small>Atte,inc.</small>
        </div>
    </footer>
</body>

</html>