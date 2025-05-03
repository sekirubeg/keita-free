@extends('layouts.app')

@section('css')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const buttons = document.querySelectorAll('.like-toggle');
            buttons.forEach(button => {
                button.addEventListener('click', async (e) => {
                    e.preventDefault();

                    const itemId = button.dataset.itemId;
                    const isliked = button.dataset.liked === 'true';
                    console.log(isliked);
                    const countSpan = button.nextElementSibling;
                    let currentCount = parseInt(countSpan.innerText);

                    // ✅ まずUIを即時変更
                    button.classList.toggle('btn-success');
                    button.classList.toggle('btn-outline-success');
                    button.innerHTML = isliked ?
                        '<i class="fa-regular fa-heart fa-xl"></i>' :
                        '<i class="fa-solid fa-heart fa-xl"></i>';
                    button.dataset.liked = isliked ? 'false' : 'true';

                    currentCount = isliked ? currentCount - 1 : currentCount + 1;
                    countSpan.innerText = currentCount;
                    countSpan.classList.toggle('is-liked');
                    // 🔄 そのあとサーバー通信
                    try {
                        const url = `/likes/${itemId}`;
                        const method = isliked ? 'DELETE' : 'POST';

                        const response = await fetch(url, {
                            method: method,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute(
                                    'content'),
                                'Content-Type': 'application/json',
                            },
                        });

                        if (!response.ok) {
                            alert('サーバーエラーが発生しました。');
                            // エラー時はUIを元に戻す
                            location.reload(); // または元の状態に戻す処理をここで入れる
                        }
                    } catch (error) {
                        alert('通信に失敗しました');
                        console.error(error);
                        location.reload(); // 通信失敗したらリロードで整える
                    }
                });
            });
        });
    </script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v6.7.0/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/show.css') }}">
@endsection

@section('content')
    <div class="container py-5">
        <div class="row">

            {{-- 左側：商品画像 --}}
            <div class="col-md-6 text-center">
                <img src="{{ Str::startsWith($item->image_at, 'http') ? $item->image_at : asset('storage/' . $item->image_at) }}"
                    class="img-fluid" style="max-height: 40vh; object-fit: contain;">
            </div>

            {{-- 右側：商品情報 --}}
            <div class="col-md-5">
                <h2 class="mb-2" style="font-size:2.5rem; font-weight:bold;">{{ $item->name }}</h2>
                <p class="text-muted">{{ $item->brand }}</p>

                <div class="d-flex align-items-center mb-3">
                    <h3 class="text-danger mb-0">¥{{ number_format($item->price) }}<small class="text-muted"> (税込)</small>
                    </h3>
                </div>

                {{-- いいね/ブックマーク（仮） --}}
                @if (Auth::id() !== $item->user_id)
                    <div style="display:flex; align-items: center; padding-bottom: 20px;">
                        @guest
                            <a href="{{ route('login') }}" class="btn btn-outline-success"
                                style="border: none; background: none;">
                                <i class="fa-regular fa-heart fa-xl"></i>
                            </a>
                            <span class="like-count" style="font-size:15px;">{{ $item->likes_count }}</span>
                            <a href="{{ route('login') }}" class="btn btn-outline-success"
                                style="border: none; background: none;">
                                <i class="fa-regular fa-comment fa-xl"></i>
                            </a>
                            <span class="like-count" style="font-size:15px;">{{ $item->comments_count }}</span>
                        @else
                            <div style="justify-content: center; display: flex; align-items: center; margin-right: 1vw;">
                                <button
                                    class="btn {{ Auth::user()->is_liked($item->id) ? 'btn-success' : 'btn-outline-success' }} like-toggle"
                                    data-item-id="{{ $item->id }}"
                                    data-liked="{{ Auth::user()->is_liked($item->id) ? 'true' : 'false' }}"
                                    style="border: none; background-color: transparent; width: 40px; height:40px; padding: 0; ">
                                    {!! Auth::user()->is_liked($item->id)
                                        ? '<i class="fa-solid fa-heart fa-xl"></i>'
                                        : '<i class="fa-regular fa-heart fa-xl"></i>' !!}
                                </button>
                                <span class="like-count {{ Auth::user()->is_liked($item->id) ? 'is-liked' : '' }}"
                                    style="font-size:15px;">
                                    {{ $item->likes_count }}
                                </span>
                            </div>

                            <div>
                                <button class="btn"
                                    style="border: none; background-color: transparent; width: 40px; height:40px; padding: 0; ">
                                    <i class="fa-regular fa-comment fa-xl"></i>
                                </button>
                                <span style="font-size:15px;">
                                    {{ $item->comments_count }}
                                </span>
                            </div>
                        @endguest
                    </div>
                @endif


                @if (Auth::user()->id !== $item->user_id)
                    <form action="{{ route('item.purchase', $item->id) }}" method="get">
                        @csrf
                        <button type="submit" class="btn btn-danger w-100 mb-4"
                            style="font-weight:600; background-color:#ff5555; border:none;">購入手続きへ</button>
                    </form>
                @endif


                {{-- 商品説明 --}}
                <h4 class="fw-bold mt-4 mb-4">商品説明</h4>
                <p>{{ $item->description }}</p>

                {{-- 商品情報 --}}
                <h4 class="fw-bold " style="margin-top:6vh; margin-bottom:4vh;">商品の情報</h4>
                <div style="display:flex; align-items:center;">
                    <p>
                        <strong style="padding-right:4vw; ">カテゴリー</strong>
                    </p>

                    @foreach ($item->tags as $tag)
                        <p class="btn"
                            style="background: #D9D9D9; margin-right: 2vw; font-size:12px; border-radius:30px; padding-top:2px; padding-bottom:2px;">
                            {{ $tag->name }}</p>
                    @endforeach

                </div>
                <p><strong style="padding-right:5vw; ">商品の状態</strong>{{ $item->status_label }}</p>
                <h4 class="fw-bold" style="margin-top:6vh; color:#5f5f5f;">コメント ({{ $item->comments->count() }})</h4>
                <div class="mt-4">
                    @forelse ($item->comments as $comment)
                        <div class="card mb-2" style="padding:0;">
                            <div class="card-body" style="padding: 15px 20px;">
                                <div
                                    style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                                    <!-- 左側：アイコン＋名前 -->
                                    <div style="display: flex; align-items: center;">
                                        <img src="{{ asset('storage/' . $comment->user->image_at) }}" alt="プロフィール画像"
                                            class="profile-icon-small"
                                            style="margin-right: 15px; height: 40px; width: 40px; border-radius: 50%; overflow: hidden;">
                                        <p style="margin-bottom: 0;">{{ $comment->user->name }}</p>
                                    </div>

                                    <!-- 右側：日付 -->
                                    <p style="margin-bottom: 0; font-size: 0.9em; color: gray;">
                                        {{ $comment->created_at->format('Y / n / j') }}</p>
                                </div>
                                <p style="font-size:18px; margin-left:1vw; margin-top:5px; margin-bottom:5px;">
                                    {{ $comment->body }}</p>
                                <div style="display: flex; justify-content: space-between; align-items: center;">

                                    @if (Auth::user()->id === $comment->user_id)
                                        <form action="{{ route('comment.destroy', $comment->id) }}" method="POST">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-sm"><small>削除する</small></button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div style="padding: 0 20px 20px 20px;  display:flex; align-items:center;">
                            <img src="{{ asset('storage/' . $user->image_at) }}" class="profile-icon-small"
                                style=" height:50px; width:50px; border-radius:50%; overflow:hidden; margin-right: 2vw;">
                            <p style="margin:0;">{{ $user->name }}</p>
                        </div>
                        <p style="background-color:#e5e5e5; padding:15px;">こちらにコメントが入ります。</p>
                    @endforelse
                </div>
                <form action="{{ route('comment.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="item_id" value="{{ $item->id }}">
                    <div class="mb-3">
                        <textarea name="body" class="form-control mt-5" rows="7" placeholder="商品へのコメント" required></textarea>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-danger w-100 mt-4"
                            style="font-weight:600; background-color:#ff5555; border:none;">コメントを送信する</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
