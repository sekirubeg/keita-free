@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/edit.css') }}">
    <style>
        .form-label {
            font-weight: bold;
            font-size: 1.2rem;
            margin-top: 20px;
        }

        .btn {
            font-size: 14px;
            padding-left: 1vw;
            padding-right: 1vw;
            margin-bottom: 1.5vh;
            border-radius: 5px;
            border: 2px solid #ff5555;
            color: white;
        }

        .btn-check:hover+.btn {
            background-color: #ff5555;
            color: white;
            border-color: #ff5555;
        }

        .btn-check:checked+.btn {
            background-color: #ff5555;
            color: white;
            border-color: #ff5555;
        }

        .image-upload-button {
            border: 2px solid #ff5555;
            color: #ff5555;
            background-color: transparent;
            padding: 7px 20px;
            border-radius: 10px;
            font-weight: bold;
            font-size: 16px;
            transition: 0.2s;
        }

        .image-upload-button:hover {
            background-color: #ff5555;
            color: white;
        }

        .image-upload-area input[type="file"] {
            display: none;
        }

        #imagePreview img {
            max-width: 100%;
            height: auto;
            margin-top: 10px;
        }

        .error-message {
            color: red;
            font-size: 14px;

        }
    </style>
@endsection

@section('content')
    <div class="profile-container mt-3 " style="width:45%; margin: 0 auto; ">
        <div class="profile-card p-4 profile-edit-card">
            <h2 class="mb-4 text-center">プロフィール設定</h2>


            <form method="POST" action="{{ route('mypage.update') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-5" style="display: flex; align-items: center;">
                    {{-- プロフィール画像 --}}
                    @if ($user->image_at)
                        <div class="mt-3" style=" height:150px ; width: 150px; overflow: hidden;">
                            <img src="{{ asset('storage/' . $user->image_at) }}" alt="プロフィール画像" class="img-thumbnail"
                                style="max-width: 150px; border-radius:50%; width:100%; height:100%; object-fit:cover;"
                                id="img">
                        </div>
                    @endif
                    {{-- このonchangeがプレビューを表示させる。 --}}
                    <div class="image-upload-area ms-5">
                        <input type="file" id="imageInput" name="image_at" onchange="previewImage(this)">
                        <button type="button" name="image_at" onclick="document.getElementById('imageInput').click()"
                            class="image-upload-button">画像を選択する</button>
                    </div>

                </div>
                @error('image_at')
                    <div class="error-message">
                        {{ $message }}
                    </div>
                @enderror

                <div class="mb-1">
                    <label for="name" class="form-label">ユーザー名</label>
                    <input id="name" type="text" class="form-control" name="name" value="{{ $user->name }}"
                        required>
                </div>
                @error('name')
                    <div class="error-message">
                        {{ $message }}
                    </div>
                @enderror

                <div class="mb-1">
                    <label for="post_code" class="form-label">郵便番号</label>
                    <input id="post_code" type="text" class="form-control" name="post_code"
                        value="{{ old('post_code', $user->post_code) }}">
                </div>
                @error('post_code')
                    <div class="error-message">
                        {{ $message }}
                    </div>
                @enderror
                <div class="mb-1">
                    <label for="address" class="form-label">住所</label>
                    <input id="address" type="text" class="form-control" name="address"
                        value="{{ old('address', $user->address) }}">
                </div>
                @error('address')
                    <div class="error-message">
                        {{ $message }}
                    </div>
                @enderror
                <div class="mb-1">
                    <label for="building" class="form-label">建物名</label>
                    <input id="building" type="text" class="form-control" name="building"
                        value="{{ old('building', $user->building) }}">
                </div>

                @error('building')
                    <div class="error-message">
                        {{ $message }}
                    </div>
                @enderror

                <div class="text-center mt-5">
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
