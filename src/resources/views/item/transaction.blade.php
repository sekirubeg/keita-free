@extends('layouts.app')
@section('title', '商品詳細')
@php
    use Illuminate\Support\Str;

    $imagePath = $item->image_at ?? '';
    $imageIsExternal = Str::startsWith($imagePath, 'http');
    $isValidImage =
        Str::endsWith(Str::lower($imagePath), '.jpg') ||
        Str::endsWith(Str::lower($imagePath), '.jpeg') ||
        Str::endsWith(Str::lower($imagePath), '.png');

    $imageSrc = '';

    if (!empty($imagePath) && $isValidImage) {
        $imageSrc = $imageIsExternal ? $imagePath : asset('storage/' . $imagePath);
    } else {
        $imageSrc = asset('images/blank_image.png'); // デフォルト画像
    }
@endphp
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v6.7.0/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/transaction.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        function previewImage(obj) {
            var fileReader = new FileReader();
            fileReader.onload = (function() {
                document.getElementById('img').src = fileReader.result;
            });
            fileReader.readAsDataURL(obj.files[0]);
        }
    </script>
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
                        <button type="button" class="btn-complete-deal" data-bs-toggle="modal"
                            data-bs-target="#completeModal">取引を完了する</button>
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

                                    <div class="message-body"
                                        style="display: flex; flex-direction: column; justify-content: flex-end; align-items: flex-end;">

                                        <p class="message-content">{{ $message->content }}</p>
                                        @if ($message->image_at)
                                            <img src="{{ asset('storage/' . $message->image_at) }}" class="message-image"
                                                alt="メッセージ画像">
                                        @endif
                                    </div>
                                </div>


                                {{-- メッセージ編集フォーム（初期状態は非表示） --}}
                                <div id="edit-form-{{ $message->id }}" style="display: none;">
                                    <form action="{{ route('transaction.message.update', $message->id) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('put')
                                        <input type="text" name="content" value="{{ $message->content }}"
                                            class="form-control mb-2" required>

                                        {{-- 新しい画像アップロードフィールド --}}
                                        <div class="input-group mb-2">
                                            <input type="file" class="form-control"
                                                id="editImageInput-{{ $message->id }}" name="image_at">
                                        </div>

                                        {{-- 既存の画像を表示（存在する場合） --}}
                                        @if ($message->image_at)
                                            <div class="mb-2">
                                                <p>現在の画像:</p>
                                                <img src="{{ asset('storage/' . $message->image_at) }}" alt="現在の画像"
                                                    class="img-thumbnail" style="max-width: 150px;">
                                            </div>
                                        @endif

                                        <div class="d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary btn-sm me-2">更新</button>
                                            <button type="button" onclick="cancelEdit({{ $message->id }})"
                                                class="btn btn-secondary btn-sm">キャンセル</button>
                                        </div>
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
                                <div style="display:flex; margin-top: 1vh; ">

                                    <div class="message-body"
                                        style="display: flex; flex-direction: column; justify-content: flex-end; align-items: flex-start;">
                                        {{-- メッセージ内容と画像 --}}

                                        <p class="message-content" style="display: inline-block;">{{ $message->content }}
                                        </p>
                                        @if ($message->image_at)
                                            <img src="{{ asset('storage/' . $message->image_at) }}" class="message-image"
                                                alt="メッセージ画像">
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                {{-- 取引完了モーダル --}}
                <div class="modal fade" id="completeModal" tabindex="-1" aria-labelledby="completeModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-md">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="completeModalLabel">取引が完了しました。</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            {{-- 評価を送信するフォーム --}}
                            <form action="{{ route('deal.complete', ['deal' => $deal->id]) }}" class="form"
                                method="post">
                                @csrf
                                <p class="form-title">今回の取引相手はどうでしたか？</p>
                                <div class="form-rating">
                                    <input class="form-rating__input" id="star5" name="rating" type="radio"
                                        value="5">
                                    <label class="form-rating__label" for="star5"><i
                                            class="fa-solid fa-star"></i></label>

                                    <input class="form-rating__input" id="star4" name="rating" type="radio"
                                        value="4">
                                    <label class="form-rating__label" for="star4"><i
                                            class="fa-solid fa-star"></i></label>

                                    <input class="form-rating__input" id="star3" name="rating" type="radio"
                                        value="3" checked>
                                    <label class="form-rating__label" for="star3"><i
                                            class="fa-solid fa-star"></i></label>

                                    <input class="form-rating__input" id="star2" name="rating" type="radio"
                                        value="2">
                                    <label class="form-rating__label" for="star2"><i
                                            class="fa-solid fa-star"></i></label>

                                    <input class="form-rating__input" id="star1" name="rating" type="radio"
                                        value="1">
                                    <label class="form-rating__label" for="star1"><i
                                            class="fa-solid fa-star"></i></label>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="modal-btn btn-primary">送信する</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>



                <div class="mt-3">
                    {{-- 3. メッセージ入力フォーム --}}
                    <img src="{{ asset('storage/' . 'images/blank_image.png') }}" class="img-thumbnail"
                        style="max-width: 150px;" id="img">
                </div>
                @error('content')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                @error('image_at')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                {{-- 4. メッセージ入力フォーム --}}
                <div class="message-form-container">
                    <form action="{{ route('transaction.message', ['deal_id' => $deal->id]) }}" method="POST"
                        class="message-form" enctype="multipart/form-data" novalidate>
                        @csrf
                        <input type="hidden" name="item_id" value="{{ $deal->item_id }}">
                        <input type="text" name="content" class="message-input" placeholder="取引メッセージを記入してください"
                            value="{{ old('content') }}" required>
                        <div class="image-upload-wrapper">
                            <input type="file" id="messageImageInput" name="image_at" accept="image/*"
                                style="display: none;" onchange="previewImage(this)">
                            <button type="button" onclick="document.getElementById('messageImageInput').click()"
                                class="image-upload-button">
                                画像を追加
                            </button>
                        </div>
                        <button type="submit" class="send-button">
                            <img src="{{ asset('storage/images/button.jpg') }}" alt="送信">
                        </button>
                    </form>
                </div>


            </div>
        </main>
    </div>
    {{-- JavaScriptで表示を切り替える --}}

@endsection
@section('scripts')
    @parent
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showEditForm(messageId) {
            document.getElementById('message-content-' + messageId).style.display = 'none';
            document.getElementById('edit-form-' + messageId).style.display = 'flex';
        }

        function cancelEdit(messageId) {
            document.getElementById('message-content-' + messageId).style.display = 'flex';
            document.getElementById('edit-form-' + messageId).style.display = 'none';
        }

        const messageInput = document.querySelector('.message-input');
        const dealId = "{{ $deal->id }}";
        const storageKey = `chat_message_${dealId}`;

        // ページ読み込み時にlocalStorageからデータを復元
        document.addEventListener('DOMContentLoaded', () => {
            const savedMessage = localStorage.getItem(storageKey);
            if (savedMessage) {
                messageInput.value = savedMessage;
            }
        });

        // 入力内容が変わるたびにlocalStorageに保存
        messageInput.addEventListener('input', () => {
            localStorage.setItem(storageKey, messageInput.value);
        });

        // フォーム送信後にlocalStorageのデータを削除
        const messageForm = document.querySelector('.message-form');
        messageForm.addEventListener('submit', () => {
            localStorage.removeItem(storageKey);
        });
        // ======================================

        // 出品者側で、まだ評価を送信していない場合にモーダルを自動表示
        @if (!$authority && $deal->completed_at && !$deal->hasEvaluatedBy(Auth::id()))
            document.addEventListener('DOMContentLoaded', function() {
                var myModal = new bootstrap.Modal(document.getElementById('completeModal'));
                myModal.show();
            });
        @endif
    </script>
@endsection
