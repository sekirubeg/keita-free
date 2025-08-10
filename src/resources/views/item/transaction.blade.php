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
                @foreach ($ongoingDeals as $ongoingDeal)
                    <a href="{{ route('item.transaction', ['id' => $ongoingDeal->item->id]) }}" class="nav-link">
                        <span>{{ $ongoingDeal->item->name }}</span>
                    </a>
                @endforeach
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
                    @if ($authority)
                        <form action=method="POST">
                            @csrf
                            <button type="submit" class="btn-complete-deal">取引を完了する</button>
                        </form>
                    @endif
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
                                <div style="display: flex; align-items: center; gap: 5px; justify-content: flex-end;">
                                    {{-- 自分のプロフィール画像と名前 --}}

                                    <img src="{{ asset('storage/' . $message->sender->image_at) }}" class="partner-avatar"
                                        alt="avatar">
                                    <p style="margin: 0;">{{ $message->sender->name }}</p>
                                </div>
                                <div id="message-content-{{ $message->id }}"
                                    style="display:flex; flex-direction:row-reverse; margin-top: 1vh; align-items: flex-end;">

                                    <div class="message-body">
                                        <p class="message-content">{{ $message->content }}</p>
                                    </div>
                                    <span class="message-time">{{ $message->created_at->format('H:i') }}</span>
                                </div>
                                {{-- メッセージ編集フォーム（初期状態は非表示） --}}
                                <div id="edit-form-{{ $message->id }}" style="display: none;">
                                    <form action="{{ route('transaction.message.update', $message->id) }}" method="POST">
                                        @csrf
                                        @method('put')
                                        <input type="text" name="content" value="{{ $message->content }}"
                                            class="form-control" required>
                                        <button type="submit" class="btn btn-primary btn-sm">更新</button>
                                        <button type="button" onclick="cancelEdit({{ $message->id }})"
                                            class="btn btn-secondary btn-sm">キャンセル</button>
                                    </form>
                                </div>

                                <div style="display: flex; justify-content: flex-end; margin-top: 5px;">
                                    {{-- メッセージ編集ボタン --}}
                                    <button type="button" onclick="showEditForm({{ $message->id }})" class="btn btn-sm">
                                        <small>編集する</small>
                                    </button>
                                    {{-- メッセージ削除フォーム --}}
                                    <form action="{{ route('transaction.message.destroy', $message->id) }}" method="POST">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-sm"><small>削除する</small></button>
                                    </form>
                                </div>
                            </div>
                        @else
                            {{-- 相手のメッセージ (左寄せ) --}}
                            <div class="message-bubble received">
                                <div style="display: flex; align-items: center; gap: 5px; ">
                                    {{-- 相手のプロフィール画像と名前 --}}
                                    <img src="{{ asset('storage/' . $message->sender->image_at) }}" class="partner-avatar"
                                        alt="avatar">
                                    <p style="margin: 0;">{{ $message->sender->name }}</p>
                                </div>
                                <div style="display:flex; margin-top: 1vh; align-items: flex-end;">
                                    {{-- 相手のメッセージ送信時間 --}}
                                    {{-- メッセージ内容 --}}
                                    <div class="message-body">

                                        <p class="message-content">{{ $message->content }}</p>
                                    </div>
                                    <span class="message-time">{{ $message->created_at->format('H:i') }}</span>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                {{-- 4. メッセージ入力フォーム --}}
                <div class="message-form-container">
                    <form action="{{ route('transaction.message', ['deal_id' => $deal->id]) }}" method="POST"
                        class="message-form">
                        @csrf
                        <input type="hidden" name="item_id" value="{{ $deal->item_id }}">
                        <input type="text" name="content" class="message-input" placeholder="取引メッセージを記入してください" required>
                        <button type="submit" class="send-button">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>

            </div>
        </main>
    </div>
    {{-- JavaScriptで表示を切り替える --}}
    <script>
        function showEditForm(messageId) {
            document.getElementById('message-content-' + messageId).style.display = 'none';
            document.getElementById('edit-form-' + messageId).style.display = 'flex';
        }

        function cancelEdit(messageId) {
            document.getElementById('message-content-' + messageId).style.display = 'flex';
            document.getElementById('edit-form-' + messageId).style.display = 'none';
        }
    </script>
@endsection
