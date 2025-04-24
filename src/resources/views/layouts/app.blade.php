<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ヘッダー</title>
  <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
  <link rel="stylesheet" href="{{ asset('css/common.css') }}">
  @yield('css')
</head>

<body>
  <header class="header">
    <div class="header__logo" style="margin-left: 2vw;">
      <a href="{{ route('index') }}">
        <img src="{{ asset('images/logo.svg') }}" alt="Attendance Management Logo">
      </a>
    </div>

    <nav class="header__nav">
      <ul>
        <form action="{{ route('index') }}" method="get">
            @csrf
            <input type="text" name="search" placeholder="　　　なにをお探しですか？" value="{{ request('search') }}" style="width: 35vw; height: 35px; border-radius: 3px; border: 1px solid #ccc; margin-right: 50px; padding-left: 10px;">
        </form>
        @auth
          <li><a href="{{ route('logout') }}">ログアウト</a></li>
        @else
          <li><a href="{{ route('login') }}">ログイン</a></li>
          <li><a href="{{ route('register') }}">登録</a></li>
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
