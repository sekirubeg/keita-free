@extends('layouts.app')
@section('title', 'プロフィール')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .index__title {
            display: flex;
            gap: 7vw;
            border-bottom: 1.5px solid #000;
            padding: 1vh 13vw;
            margin-bottom: 6vh;
            margin-top: 4vh;
        }

        .caption {
            font-size: 18px;
            font-weight: bold;
        }

        .recommend {
            color: #ff0000;
        }

        .container {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .profile-card {
            display: flex;
            gap: 5vw;
        }

        .profile-info {
            display: flex;
            gap: 20vw;
        }

        .unity {
            width: 90vw;
            margin: auto;
        }

        .task-card {
            border-radius: 14px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
            background: #fff;
            border: none;
        }

        .task-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
        }

        .card-title {
            font-weight: 600;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 1rem;
            margin-bottom: 3rem
        }

        .pagination .page-item .page-link {
            color: #fff;
            background-color: #333;
            /* ダークグレー */
            border: 1px solid #555;
            margin: 0 4px;
            border-radius: 8px;
            transition: background-color 0.3s;
        }

        .pagination .page-item .page-link:hover {
            background-color: #555;
            /* ホバー時ちょっと明るく */
            color: #fff;
        }


        .pagination .page-item.active .page-link {
            background-color: #000;
            /* 現在ページは完全な黒 */
            border-color: #000;
        }

        .pagination .page-item.disabled .page-link {
            background-color: #222;
            color: #777;
            cursor: not-allowed;
        }

        .text-muted {
            display: none;
        }
         .profile-buttons .profile {
            color: #ff5555;
            background-color: transparent;
            border: 1px solid #ff5555;
            font-weight: bold;
            padding: 7px 25px;
            transition: 0.3s;
        }

        .profile-buttons .profile:hover {
            background-color: #ff5555;
            color: white;
        }
        .stars-outer {
        position: relative;
        display: inline-block;
        }

        .stars-inner {
        position: absolute;
        top: 0;
        left: 0;
        white-space: nowrap;
        overflow: hidden;
        width: 0; /* JavaScript or server-side code will set this */
        }

        /* Both star containers use the Font Awesome font and content */
        .stars-outer::before,
        .stars-inner::before {
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        content: "\f005 \f005 \f005 \f005 \f005"; /* 5 stars */
        font-size: 2rem; /* この行を追加または変更 */
        letter-spacing: 0.3rem; /* この行を追加 */
        }

        /* Gray stars for the background */
        .stars-outer::before {
        color: #ccc;
        }

        /* Gold stars for the foreground */
        .stars-inner::before {
        color: #f8ce0b;
        }
    </style>
@endsection

@section('content')
    <div class="profile-wrapper" style="padding-top:0; ">
        <div class="container " style="margin-top:8vh; ">

            <div class="profile-card mb-4">
                <div class="profile-image"
                    style="width:10vw; height:10vw; margin:0 auto; border-radius:50%; overflow:hidden;">
                    <img src="{{ asset('storage/' . $user->image_at) }}" alt="プロフィール画像"
                        style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div class="profile-info" style="display: flex; align-items:center;">

                    <div>
                        <p style="font-size:2rem; font-weight:bold; margin-bottom:0; ">{{ $user->name }}</p>
                        {{-- 星評価の表示エリア --}}
                        <div class="stars-outer">
                          <div class="stars-inner"></div>
                        </div>
                    </div>


                    <div class="profile-buttons">
                        <a href="{{ route('mypage.edit') }}" class="btn btn-primary profile">プロフィールを編集</a>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="index__title">
        <a class="caption" href="{{ route('mypage') }}">出品した商品</a>
        <a class="caption" href="{{ route('mypage', ['page' => 'buy']) }}">購入した商品</a>
        <a class="caption recommend">取引中の商品</a>
    </div>

    <div class="row unity">
        @foreach ($deals as $deal)
            <div class="col-md-3 mb-4" style="cursor: pointer;">
                <a href="{{ route('item.transaction', $deal->item->id) }}" class="card task-card h-150" style="display:block;">
                    <img src="{{ Str::startsWith($deal->item->image_at, 'http') ? $deal->item->image_at : asset('storage/' . $deal->item->image_at) }}"
                        class="card-img-top" style="height: 35vh; object-fit: cover; border-bottom: 1px solid #dee2e6;">
                    <div class="card-body" style="display: flex; justify-content: space-between; ">
                        <div>
                            <h5 class="card-title">{{ $deal->item->name }}</h5>
                            <p class="card-text">{{ $deal->item->price }}</p>
                        </div>
                        <p>いいね数：{{ $deal->item->likes_count }}</p>
                    </div>

                </a>
            </div>
        @endforeach
    </div>
@endsection
