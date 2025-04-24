@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')


<div class="login">
  <div class="login__title">
    <h1>ログイン</h1>
  </div>

  <form action="{{ route('login') }}" method="POST" class="register__form">
    @csrf
    <div class="login__form__input">
      <label for="email">メールアドレス</label>
      <input type="email" name="email" id="email" required >
    </div>

    <div class="login__form__input">
      <label for="password">パスワード</label>
      <input type="password" name="password" id="password" required>
    </div>


    <button type="submit" class="login__button">ログインする</button>
  </form>
    <div class="login__form__link">
        <a href="{{ route('register') }}" style="text-decoration: none; color:#0073CC;">会員登録はこちら</a>
    </div>
  @if ($errors->any())
    <div class="error-messages">
      @foreach ($errors->all() as $error)
        <p>{{ $error }}</p>
      @endforeach
    </div>
  @endif

</div>

@endsection
