@extends('layouts.app')
@php
    $selectedPayment = session('purchase_payment_id');
@endphp
@section('css')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            updatePaymentMethod();
        });

        function updatePaymentMethod() {
            const paymentSelect = document.getElementById("payment_method");
            const selectedText = paymentSelect.options[paymentSelect.selectedIndex].text;
            document.getElementById("selected_payment_method").textContent = selectedText;
        }

        function goToAddressPage() {
            event.preventDefault();
            const paymentValue = document.getElementById("payment_method").value;
            const itemId = {{ $item->id }};
            const url = `/purchase/address/${itemId}?payment=${paymentValue}`;
            window.location.href = url;
        }

        function goToCheckoutPage() {
            event.preventDefault();
            const paymentValue = document.getElementById("payment_method").value;
            const itemId = {{ $item->id }};
            const url = `/purchase/checkout/${itemId}?payment=${paymentValue}`;
            window.location.href = url;
        }
    </script>
    <style>
        .address__button {
            display: block;
            width: 35vw;
            height: 5vh;
            background-color: #FF5555;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            transition: 0.2s;
        }

        .address__button:hover {
            background-color: #FF3333;
        }
    </style>
@endsection

@section('content')
    <div style="margin:0; display: flex; width:100vw;">
        <div class="row" style=" width:60vw;">
            <div style="padding: 7vh 5vw 3vh 5vw;">
                <div style="display: flex; gap: 4vw;  border-bottom: 1px solid #000; padding-bottom: 5vh;">
                    <img src="{{ Str::startsWith($item->image_at, 'http') ? $item->image_at : asset('storage/' . $item->image_at) }}"
                        alt="" style="width: 25%;">
                    <div>
                        <h3 style="font-size: 1.8rem;">{{ $item->name }}</h3>
                        <p style="font-size:1.8rem; font-weight:300;">¥{{ number_format($item->price) }}</p>
                    </div>
                </div>
            </div>
            <div style="padding: 0 5vw 3vh 5vw;   ">
                <div style="border-bottom: 1px solid #000; display:flex; flex-direction:column; padding-bottom: 5vh;">
                    <label for="payment_method" class="form-label"
                        style="font-size: 1rem; font-weight:bold; padding:0 0 2vh 2vw;">支払い方法</label>
                    <select name="payment" id="payment_method" onchange="updatePaymentMethod()"
                        style="width:15vw; margin-left:5vw;" required>

                        <option value="" disabled {{ $selectedPayment ? '' : 'selected' }}>選択してください</option>
                        <option value="1" {{ $selectedPayment == 1 ? 'selected' : '' }}>クレジットカード</option>
                        <option value="2" {{ $selectedPayment == 2 ? 'selected' : '' }}>コンビニ払い</option>
                    </select>
                    @if (session('error'))
                        <div style="color: red; font-weight: bold; margin-left:5vw; margin-top: 1vh;">
                            {{ session('error') }}
                        </div>
                    @endif
                </div>

            </div>

            <div style="padding: 0 5vw 3vh 5vw;   ">
                <div style="border-bottom: 1px solid #000; padding-bottom: 5vh;">
                    <div style="display: flex; justify-content:space-between;">
                        <p style="font-weight:bold; padding:0 0 2vh 2vw;">配送先</p>
                        <a href="#" onclick="goToAddressPage()"
                            style="text-decoration: none; margin-right:2vw;">変更する</a>
                    </div>
                    <div style="margin-left:5vw;">
                        @if (session('purchase_post_code'))
                            <p>〒 {{ session('purchase_post_code') }}</p>
                            <p>{{ session('purchase_address') }}{{ session('purchase_building') }}</p>
                        @else
                            <p>〒 {{ $user->post_code }}</p>
                            <p>{{ $user->address }}{{ $user->building }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="row" style="height:60vh; width:40vw;">
            <div style="margin-top:8vh;  width:35vw; padding:0;">
                <div width="100%">
                    <div
                        style=" display: flex; justify-content:space-between; align-items:center;  padding:35px; border:1px solid #000; border-bottom: none;">
                        <p style="margin:auto 0;">商品代金</p>
                        <p style="margin:auto 0;">¥{{ number_format($item->price) }}</p>
                    </div>
                    <div style="display: flex; justify-content:space-between; padding:35px; border:1px solid #000;">
                        <p style="margin: auto 0">支払い方法</p>
                        <p id="selected_payment_method" style="margin:auto 0;">選択してください</p>
                    </div>
                </div>
            </div>
            <button onclick="goToCheckoutPage()" type="submit" class="address__button"
                style=" padding:3vh 0; font-weight: bold; display: flex; justify-content: center; align-items: center;">購入する</button>
        </div>
    </div>

    </div>
@endsection
