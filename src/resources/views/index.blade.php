<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atte</title>
</head>

<body>
    <header>
        <div class="header">
            <div class="header_logo">
                <h1>Atte</h1>
            </div>
            <nav class="header_nav">
                <ul>
                    <li><a href="">ホーム</a></li>
                    <li><a href="">日付一覧</a></li>
                    <li><a href="">ログアウト</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <div class="head">
                <h2>さんお疲れ様です！</h2>
            </div>
        </div>
    </main>



    <h1>こんにちは</h1>
    <!--ログアウトボタン-->
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" onclick="event.preventDefault(); this.closest('form').submit();">
            ログアウト
        </button>
    </form>

</body>

</html>