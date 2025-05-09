@extends('layouts.app')
@section('title', 'マイリスト')
@section('css')
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">

    <style>
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
    </style>
@endsection

@section('content')
    <div class="index__title" style="margin-bottom: 5vh;">
        <a class="caption" href="{{ route('index') }}">おすすめ</a>
        <a class="caption recommend">マイリスト</a>
    </div>
    <div class="mb-4 d-flex justify-content-end" style="padding-right: 5vw;">
        <form method="GET" action="{{ route('mylist') }}" class="d-flex align-items-center">
            <input type="hidden" name="search" value="{{ request('search') }}">
            <label for="sort" class="me-2 fw-bold mb-0">並び順:</label>
            <select name="sort" onchange="this.form.submit()" class="form-select form-select-sm" style="width: auto;">
                <option value="desc" {{ $sort === 'desc' ? 'selected' : '' }}>新しい順</option>
                <option value="asc" {{ $sort === 'asc' ? 'selected' : '' }}>古い順</option>
            </select>
        </form>
    </div>
    <div class="row unity">
        @foreach ($items as $item)
            @php
                $isSold = in_array($item->id, $itemIds);
            @endphp
            <div class="col-md-3 mb-4" style="cursor: pointer;">
                <a href="{{ route('item.show', $item->id) }}" class="card task-card h-150" style="display:block;">
                    <img src="{{ Str::startsWith($item->image_at, 'http') ? $item->image_at : asset('storage/' . $item->image_at) }}"
                        class="card-img-top" style="height: 35vh; object-fit: cover; border-bottom: 1px solid #dee2e6;">
                    @if ($isSold)
                        <div
                            style="position: absolute; top: 10px; left: 10px; background-color: red; color: white; padding: 5px 10px; font-weight: bold; border-radius: 5px;">
                            Sold
                        </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $item->name }}</h5>
                        <p class="card-text">¥{{ number_format($item->price) }}</p>

                    </div>
                </a>
            </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center">
        {{ $items->links('pagination::bootstrap-5') }}
    </div>
@endsection
