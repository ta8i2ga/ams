<style>
    .header {
        width: 100%;
        height: 80px;
    }

    .header_logo {
        padding-left: 5vw;
    }

    .container {
        display: flex;
        height: 600px;
        flex-direction: column;
        background-color: f2f2f2;
        padding-top: 3vw;
    }

    .heading {
        font-weight: bold;
        margin: 20px auto;
        font-size: 20px;
    }

    .form {
        display: flex;
        flex-direction: column;
        margin: 15px auto;
    }

    .form>* {
        width: 350px;
        height: 40px;
        padding: 5px;
        margin: 10px;
        border-radius: 5px;
    }

    .form>input {
        border: solid 1px gray;
        background-color: f2f2f2;
    }

    .login_wrap {
        text-align: center;
        font-weight: bold;
    }

    .login_wrap>p {
        margin: 0;
        color: gray;
    }

    .submit_btn {
        background: blue;
        color: white;
        font-size: 14px;
        border: none;
    }

    .link {
        color: blue;
    }

    .login_wrap a {
        text-decoration: none;
    }

    .footer {
        width: 100%;
        text-align: center;
        font-weight: bold;
    }
</style>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atte</title>
    <link href="https://unpkg.com/sanitize.css" rel="stylesheet" />
</head>

<body>
    <header>
        <div class="header">
            <h1 class="header_logo">Atte</h1>
        </div>
    </header>
    <div class="container">
        <h1 class="heading">会員登録</h1>
        <form action="/register" method="post" class="form">
            @csrf
            @error('name')
            <p class='error message'>{{$message}}</p>
            @enderror
            <input type="text" name="name" placeholder="名前">
            @error('email')
            <p class='error message'>{{$message}}</p>
            @enderror
            <input type="text" name="email" placeholder="メールアドレス">
            @error('password')
            <p class='error message'>{{$message}}</p>
            @enderror
            <input type="text" name="password" placeholder="パスワード">
            <input type="text" name="password_confirmation" placeholder="確認用パスワード">
            <button class="submit_btn">会員登録</button>
        </form>
        <div class="login_wrap">
            <p>アカウントをお持ちの方はこちらから</p>
            <a href="{{ route('login') }}" class="link">ログイン</a>
        </div>
    </div>
    <footer>
        <div class="footer">
            <p>Atte,inc.</p>
        </div>
    </footer>
</body>

</html>