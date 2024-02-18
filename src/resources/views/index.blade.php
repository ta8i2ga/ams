<style>

</style>
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
                    <li><a href="/">ホーム</a></li>
                    <li><a href="">日付一覧</a></li>
                    <li><a href="{{ route('logout') }}">ログアウト</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <p class="greeting character">{{$user->name}}さんお疲れ様です！</p>
            <form action="attendance/start" method="get">
                @csrf
                <button class="button start_working @if($user->isWorkingToday()) disabled @endif" name="start_working">
                    <p>勤務開始</p>
                </button>
            </form>
            <form action="attendance/end" method="get">
                @csrf
                <button class="button end_working @if(!$user->isWorkingToday() || $user->isWorkEndedToday()) disabled @endif" name="end_working">
                    <p>勤務終了</p>
                </button>
            </form>
            <form action="break/start" method="get">
                @csrf
                <button class="button start_break @if(!$user->isWorkingToday() || $user->isWorkEndedToday() || !$user->isBreakStarted()) disabled @endif" name="start_break">
                    <p>休憩開始</p>
                </button>
            </form>
            <form action="break/end" method="get">
                @csrf
                <button class="button end_break @if(!$user->isWorkingToday() || $user->isWorkEndedToday() || !$user->isBreakEnded()) disabled @endif" name="end_break">
                    <p>休憩終了</p>
                </button>
            </form>
        </div>
    </main>
    <footer class="footer character">
        <small>Atte,inc.</small>
    </footer>
</body>

</html>
<script>
    const startWorking = document.getElementsByClassName('start_working')[0];
    const endWorking = document.getElementsByClassName('end_working')[0];
    const startBreak = document.getElementsByClassName('start_break')[0];
    const endBreak = document.getElementsByClassName('end_break')[0];

    if (startWorking.className.includes('active')) {
        startWorking.addEventListener('click', () => {
            submitData(startWorking)
        });
    }
    if (endWorking.className.includes('active') || startBreak.className.includes('active')) {
        endWorking.addEventListener('click', () => {
            submitData(endWorking)
        });
        startBreak.addEventListener('click', () => {
            submitData(startBreak)
        });
    }
    if (endBreak.className.includes('active')) {
        endBreak.addEventListener('click', () => {
            submitData(endBreak)
        });
    }

    function submitData(buttonClicked) {
        buttonClicked.disabled = true;
        buttonClicked.classList.toggle('active');
        if (buttonClicked == startWorking || buttonClicked == endBreak) {
            endWorking.disabled = false;
            startBreak.disabled = false;
            endWorking.classList.toggle('active');
            startBreak.classList.toggle('active');
            //buttonSwitch(buttonClicked, endWorking, startBreak, '');
        } else if (buttonClicked == endWorking) {
            startBreak.disabled = true;
            startBreak.classList.toggle('active');
            console.log(buttonClicked);
            startWorking.disabled = false;
            startWorking.classList.toggle('active');
            //buttonSwitch(buttonClicked, startWorking, '', startBreak);
        } else if (buttonClicked == startBreak) {
            endWorking.disabled = true;
            endWorking.classList.toggle('active');
            console.log(buttonClicked);
            endBreak.disabled = false;
            endBreak.classList.toggle('active');
            //buttonSwitch(buttonClicked, endBreak, '', endWorking);
        } else if (buttonClicked == endBreak) {
            startWorking.disabled = true; // Disable start working button after break ends
            endWorking.disabled = false;
            startBreak.disabled = false;
            endBreak.classList.add('active');
            startBreak.classList.add('active');
            startWorking.classList.remove('active');
            // Disable start working button and activate other buttons after break ends
        }
    }
</script>