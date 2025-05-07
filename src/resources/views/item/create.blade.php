@extends('layouts.app')
@section('title', '商品出品')
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
    <link rel="stylesheet" href="{{ asset('css/create.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function previewImage(obj) {
            var fileReader = new FileReader();
            fileReader.onload = (function() {
                document.getElementById('img').src = fileReader.result;
            });
            fileReader.readAsDataURL(obj.files[0]);
        }
    </script>

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
            border-radius: 20px;
            border: 2px solid #ff5555;
            color: #ff5555;
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

        .image-upload-area {
            border: 2px dashed #aaa;
            border-radius: 8px;
            padding: 40px;
            text-align: center;
            position: relative;
            cursor: pointer;
        }

        .image-upload-button {
            border: 2px solid #ff5555;
            color: #ff5555;
            background-color: transparent;
            padding: 10px 20px;
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
    <form action="{{ route('item.store') }}" method="POST" enctype="multipart/form-data" novalidate>
        <div class="container" style="width:50vw;">
            <h1 style="text-align:center; font-size:36px; font-weight:700; padding-top:50px; padding-bottom:20px;">商品を出品
            </h1>
            @csrf


            <div class="mb-3">
                <label class="form-label fw-bold">商品画像</label>
                <div class="image-upload-area">
                    <input type="file" id="imageInput" name="image_at" accept="image/*" onchange="previewImage(this)">
                    <button type="button" name="image_at" onclick="document.getElementById('imageInput').click()"
                        class="image-upload-button">画像を選択する</button>
                </div>
            </div>
            @error('image_at')
                <div class="error-message">{{ $message }}</div>
            @enderror
            {{-- このonchangeがプレビューを表示させる。 --}}


            <div class="mt-3">
                <img src="{{ asset('storage/' . 'images/blank_image.png') }}" class="img-thumbnail"
                    style="max-width: 150px;" id="img">
            </div>


            <h2
                style="color:#5f5f5f; font-weight:700; border-bottom: 1px solid #5f5f5f; padding-bottom:10px; margin-top:50px;">
                商品の詳細</h2>
            <div class="mb-3">
                <label for="category" class="form-label">カテゴリー</label>
                <div class="d-flex flex-wrap" style="gap: 1.5vw; margin-top: 20px; "">
                    @foreach ($tags as $tag)
                        <div>
                            <input type="checkbox" class="btn-check" id="btn-check-{{ $tag->id }}" autocomplete="off"
                                name="tags[]" value="{{ $tag->id }}">
                            <label class="btn" for="btn-check-{{ $tag->id }}">{{ $tag->name }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
            @error('tags')
                <div class="error-message">{{ $message }}</div>
            @enderror
            <div class="mb-3">
                <label for="condition" class="form-label">商品の状態</label>
                <select class="form-select" id="condition" name="status" required>
                    <option value="" disabled selected>選択してください</option>
                    <option value="1">良好</option>
                    <option value="2">目立った傷や汚れなし</option>
                    <option value="3">やや傷や汚れあり</option>
                    <option value="4">状態が悪い</option>
                </select>
            </div>
            @error('status')
                <div class="error-message">{{ $message }}</div>
            @enderror
            <h2
                style="color:#5f5f5f; font-weight:700; border-bottom: 1px solid #5f5f5f; padding-bottom:10px; margin-top:50px">
                商品名と説明</h2>

            <div class="mb-3">
                <label for="name" class="form-label">商品名</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}"
                    required>
            </div>
            @error('name')
                <div class="error-message">{{ $message }}</div>
            @enderror
            <div class="mb-3">
                <label for="brand" class="form-label">ブランド名</label>
                <input type="text" class="form-control" id="brand" name="brand" value="{{ old('brand') }}">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">商品の説明</label>
                <textarea class="form-control" id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
            </div>
            @error('description')
                <div class="error-message">{{ $message }}</div>
            @enderror
            <div class="mb-3">
                <label for="price" class="form-label">販売価格</label>
                <div class="input-group">
                    <span class="input-group-text">￥</span>
                    <input type="number" class="form-control" id="price" name="price" value="{{ old('price') }}"
                        required>
                </div>
            </div>
            @error('price')
                <div class="error-message">{{ $message }}</div>
            @enderror


            <div class="mb-3">
                <button type="submit" class="btn"
                    style="margin-top:50px; background-color:#ff5555; color:#fff; width:100%; ">出品する</button>
            </div>
    </form>
    </div>
@endsection
