@extends('layouts.app')


@section('css')
<style>
.address{
    margin-top: 5vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    height: 100vh;
}

.address__form__input{
    margin-top: 5vh;

}

.address__form__input > label{
    font-weight: bold;
    margin-bottom: 10px;
}

.address__form__input > input{
    display: block;
    width: 45vw;
    height: 5vh;
    border:1px solid black;
}

.address__button{
    display: block;
    margin-top: 10vh;
    width: 45vw;
    height: 5vh;
    background-color: #FF5555;
    color: white;
    border: none;
    cursor: pointer;
    font-size: 16px;
    transition: 0.2s;
}

.address__button:hover{
    background-color: #FF3333;
}

.address__form__link{
    margin-top: 3vh;
    font-size: 14px;
}
</style>

@endsection

@section('content')

<div class="address">
  <div class="address__title">
    <h1 style="font-weight: bold;">住所の変更</h1>
  </div>

  <form action="" method="POST" class="register__form">
    @csrf
    <div class="address__form__input">
      <label for="post_code">郵便番号</label>
      <input type="post_code" name="" id="post_code" required >
    </div>

    <div class="address__form__input">
      <label for="address">住所</label>
      <input type="address" name="address" id="address" required>
    </div>

     <div class="address__form__input">
      <label for="building">建物名</label>
      <input type="building" name="building" id="building" required>
    </div>


    <button type="submit" class="address__button" style="font-weight: bold;">更新する</button>
  </form>

  @if ($errors->any())
    <div class="error-messages">
      @foreach ($errors->all() as $error)
        <p>{{ $error }}</p>
      @endforeach
    </div>
  @endif

</div>
@endsection
