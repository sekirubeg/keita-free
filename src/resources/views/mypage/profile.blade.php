@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="profile-wrapper" style="padding-top:0; ">
        <div class="container" style="margin-top: 12vh;">

            <div class="profile-card mb-4">
                <div class="profile-image">
                    <img src="{{ $user->image_at }}" alt="プロフィール画像">
                </div>
                <div class="profile-info">

                    <p style="font-size:22px; font-weight:bold;">{{ $user->name }}</p>


                    <div class="profile-buttons">
                        <a href="{{ route('mypage.edit') }}" class="btn btn-primary profile" style="color: #ff5555 ; background-color:transparent; border:1px solid #ff5555; font-weight:bold; padding:7px 25px">プロフィールを編集</a>

                    </div>
                </div>
            </div>
        </div>
</div>
@endsection
