@extends('layouts.app')
@section('title', '商品詳細')
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v6.7.0/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/transaction.css') }}">
@endsection

@section('content')
    <div class="page-wrapper">
        {{-- 1. サイドバーエリア --}}
        <aside class="sidebar">
            <p class="side-title">その他の取引</p>
            <nav class="sidebar-nav">
                <a href="{{ route('mypage') }}" class="nav-link">
                    <i class="fas fa-user-circle"></i>
                    <span>マイページ</span>
                </a>
                <a href="#" class="nav-link">
                    <i class="fas fa-question-circle"></i>
                    <span>よくある質問</span>
                </a>
                <a href="#" class="nav-link">
                    <i class="fas fa-envelope"></i>
                    <span>お問い合わせ</span>
                </a>
                <a href="{{ route('index') }}" class="nav-link">
                    <i class="fas fa-home"></i>
                    <span>トップに戻る</span>
                </a>
            </nav>
        </aside>

        {{-- 2. メインコンテンツエリア --}}
        <main class="main-content">
            <div class="transaction-container">
                {{-- 1. ヘッダーエリア --}}
                <div class="transaction-header">
                    <div style="display: flex; align-items: center; gap: 5px;">
                        <img src="{{ Str::startsWith($deal->item->user->image_at, 'http') ? $deal->item->image_at : asset('storage/' . $deal->item->user->image_at) }}"
                            class="item-thumbnail" alt="{{ $deal->item->name }}"
                            style="border-radius: 50%; overflow: hidden;">
                        <h2>「{{ $deal->partner()->name }}」さんとの取引画面</h2>
                    </div>
                    <form action=method="POST">
                        @csrf
                        <button type="submit" class="btn-complete-deal">取引を完了する</button>
                    </form>
                </div>

                {{-- 2. 商品情報エリア --}}
                <div class="item-info-bar">
                    <img src="{{ Str::startsWith($deal->item->image_at, 'http') ? $deal->item->image_at : asset('storage/' . $deal->item->image_at) }}"
                        class="item-thumbnail1" alt="{{ $deal->item->name }}">
                    <div class="item-details">
                        <h4>{{ $deal->item->name }}</h4>
                        <p>¥{{ number_format($deal->item->price) }}</p>
                    </div>
                </div>

                {{-- 3. チャット履歴エリア --}}
                <div class="chat-box">
                    @foreach ($deal->messages as $message)
                        {{-- メッセージが自分のものであるか相手のものであるかでクラスを切り替える --}}
                        @if ($message->sender_id == auth()->id())
                            {{-- 自分のメッセージ (右寄せ) --}}
                            <div class="message-bubble sent">
                                <p class="message-content">{{ $message->content }}</p>
                                <span class="message-time">{{ $message->created_at->format('H:i') }}</span>
                            </div>
                        @else
                            {{-- 相手のメッセージ (左寄せ) --}}
                            <div class="message-bubble received">
                                <img src="{{ asset('storage/' . $message->sender->image_at) }}" class="partner-avatar"
                                    alt="avatar">
                                <div class="message-body">
                                    <p class="message-content">{{ $message->content }}</p>
                                </div>
                                <span class="message-time">{{ $message->created_at->format('H:i') }}</span>
                            </div>
                        @endif
                    @endforeach
                </div>

                {{-- 4. メッセージ入力フォーム --}}
                <div class="message-form-container">
                    <form action="" method="POST" class="message-form">
                        @csrf
                        <input type="text" name="content" class="message-input" placeholder="取引メッセージを記入してください" required>
                        <button type="submit" class="send-button">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>

            </div>
        </main>
    </div>
@endsection
