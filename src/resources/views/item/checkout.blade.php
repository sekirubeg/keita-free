@extends('layouts.app')

@section('css')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const publicKey = '{{ $publicKey }}';
        const stripe = Stripe(publicKey);
        window.onload = function() {
            stripe.redirectToCheckout({
                sessionId: '{{ $session->id }}'
            }).then(function(result) {
                // エラー処理
                window.location.href = '{{ route('index') }}';
            });
        };
    </script>
@endsection

@section('content')
    <p style="margin-top:16px; text-align: center; font-size:36px; font-weight:bold;">決済ページへリダイレクトします。</p>
@endsection
