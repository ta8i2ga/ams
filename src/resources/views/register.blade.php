<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>register</title>
</head>

<body>
    <header>
        <div class="header__logo">
            <a href="/">Atte</a>
        </div>
    </header>
    <main>
        <div class="content">
            <h2 class="content_head">会員登録</h2>
            <form method="post" action="/register">
                @csrf
                <div>
                    <input type="text" name="name" value="名前">
                </div>
                <div>
                    <input type="text" name="email" value="メールアドレス">
                </div>
                <div>
                    <input type="text" name="password" value="パスワード">
                </div>
                <div>
                    <input type="text" name="password_confirmation" value="確認用パスワード">
                </div>
                <div>
                    <button class="ml-4">会員登録</button>
                </div>

            </form>
            <div class="login_word">
                <p>アカウントをお持ちの方はこちらから</p>
            </div>
            <a href="{{ route('login') }}">ログイン</a>
        </div>
    </main>
</body>

</html>