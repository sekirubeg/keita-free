<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ヘッダー</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">

    @yield('css')
</head>

<body>
    <header class="header">
        <div class="header__logo" style="margin-left: 2vw; margin-right: 2vw;">
            <a href="{{ route('index') }}">
                <img src="{{ asset('images/logo.svg') }}" alt="Attendance Management Logo">
            </a>
        </div>

        <nav class="header__nav">
            <ul>
                <form action="{{ route('index') }}" method="get">
                    @csrf
                    <input type="text" name="search" placeholder="　　　なにをお探しですか？" value="{{ request('search') }}"
                        style="width: 35vw; height: 35px; border-radius: 3px; border: 1px solid #ccc; margin-right: 1vw; padding-left: 10px;">
                </form>
                @auth
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            style="background:transparent; color:#fff; border:none; cursor: pointer; font-size:16px;">ログアウト</button>
                    </form>
                    <li><a href="">マイページ</a></li>
                    <li class="selling"><a href="" style="color: black;">出品</a></li>
                @else
                    <li><a href="{{ route('login') }}">ログイン</a></li>
                    <li><a href="{{ route('register') }}">会員登録</a></li>
                    <li class="selling"><a href="" style="color: black;">出品</a></li>
                @endauth
            </ul>
        </nav>
    </header>


    <main>
        @yield('content')
    </main>
</body>

</html>
