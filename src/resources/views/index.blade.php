@extends('layouts.app')

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
        .text-muted{
            display: none;
        }
    </style>
@endsection

@section('content')
    <div class="index__title">
        <p class="caption recommend">おすすめ</p>
        <p class="caption">マイリスト</p>
    </div>

    <div class="row unity">
        @foreach ($items as $item)
            <div class="col-md-3 mb-4" style="cursor: pointer;">
                <div class="card task-card h-150">
                    <img src="{{ $item->image_at }}" class="card-img-top"
                        style="height: 35vh; object-fit: cover; border-bottom: 1px solid #dee2e6;">
                    <div class="card-body">
                        <h5 class="card-title">{{ $item->name }}</h5>
                        <p class="card-text">{{ $item->price }}</p>

                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center">
        {{ $items->links('pagination::bootstrap-5') }}
    </div>
@endsection
