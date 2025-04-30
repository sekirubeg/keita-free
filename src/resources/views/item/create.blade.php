@extends('layouts.app')

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/create.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .form-label {
            font-weight: bold;
            font-size: 1.2rem;
            margin-top: 20px;
        }

        .btn{
            font-size: 14px;
            padding-left: 1vw;
            padding-right: 1vw;
            margin-bottom: 1.5vh;
            border-radius: 20px;
            border: 2px solid #ff5555;
            color: #ff5555;
        }
        .btn-check:hover + .btn {
            background-color: #ff5555;
            color: white;
            border-color: #ff5555;
        }
        .btn-check:checked+.btn {
            background-color: #ff5555;
            color: white;
            border-color: #ff5555;
        }
    </style>
@endsection

@section('content')
    <div class="container" style="width:50vw;">
        <h1>商品の出品</h1>
        <form action="" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="image" class="form-label">商品画像</label>
                <input type="file" class="form-control" id="image" name="image[]" multiple required>
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
            <h2
                style="color:#5f5f5f; font-weight:700; border-bottom: 1px solid #5f5f5f; padding-bottom:10px; margin-top:50px">
                商品名と説明</h2>

            <div class="mb-3">
                <label for="name" class="form-label">商品名</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="brand" class="form-label">ブランド名</label>
                <input type="text" class="form-control" id="brand" name="brand">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">商品の説明</label>
                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">販売価格</label>
                <div class="input-group">
                    <span class="input-group-text">￥</span>
                    <input type="number" class="form-control" id="price" name="price" required>
                </div>
            </div>


            <div class="mb-3">
                <button type="submit" class="btn"
                    style="margin-top:50px; background-color:#ff5555; color:#fff; width:100%; ">出品する</button>
            </div>
        </form>
    </div>
@endsection
