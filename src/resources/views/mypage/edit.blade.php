@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/edit.css') }}">
@endsection

@section('content')
    <div class="profile-container mt-3 " style="width:45%; margin: 0 auto; ">
        <div class="profile-card p-4 profile-edit-card">
            <h2 class="mb-4 text-center">プロフィール設定</h2>


            <form method="POST" action="{{ route('mypage.update') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-5">
                    {{-- このonchangeがプレビューを表示させる。 --}}
                    <input id="image_at" type="file" class="form-control" name="image_at" onchange="previewImage(this)">
                    @if ($user->image_at)
                        <div class="mt-3">
                            <img src="{{ asset('storage/' . $user->image_at) }}" alt="プロフィール画像" class="img-thumbnail"
                                style="max-width: 150px; " id="img">
                        </div>
                    @endif
                </div>

                <div class="mb-4">
                    <label for="name" class="form-label">ユーザー名</label>
                    <input id="name" type="text" class="form-control" name="name" value="{{ $user->name }}"
                        required>
                </div>

                <div class="mb-4">
                    <label for="post_code" class="form-label">郵便番号 <span style="color:red;">　※ハイフンは入力しないでください</span></label>
                    <input id="post_code" type="text" class="form-control" name="post_code"
                        value="{{ $user->post_code }}" >
                </div>
                <div class="mb-4">
                    <label for="address" class="form-label">住所</label>
                    <input id="address" type="text" class="form-control" name="address" value="{{ $user->address }}">
                </div>
                <div class="mb-5">
                    <label for="building" class="form-label">建物名</label>
                    <input id="building" type="text" class="form-control" name="building" value="{{ $user->building }}">
                </div>


                <div class="text-center">
                    <button type="submit" class="btn btn-primary px-5 form-control"
                        style="background: #ff5555; padding:8px 0; font-weight:600; border:none;">更新する</button>
                </div>
            </form>
        </div>
    </div>

    {{-- プレビューが表示される実装。onchange属性にpreviewImage(this)を追加 --}}
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
