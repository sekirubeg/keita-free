<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;
use App\Models\Item;
use App\Models\Order;
use App\Models\Deal;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    //
    public function index(Request $request, $id)
    {

        $item = Item::find($id);
        $user = auth()->user();
        session([
            'purchase_payment_id' => $request->query('payment'),
        ]);

        return view('item.purchase', compact('item', 'user'));
    }

    public function address(Request $request, $id)
    {
        if ($request->has('payment')) {
            session(['purchase_payment_id' => $request->query('payment')]);
        }

        $item = Item::find($id);
        $user = auth()->user();


        return view('item.address', compact('item', 'user'));
    }
    public function change(AddressRequest $request, $id)
    {
        $item = Item::find($id);
        $user = auth()->user();

        session([
            'purchase_item_id' => $item,
            'purchase_payment_id' => $request->input('payment'),
            'purchase_post_code' => $request->input('post_code'),
            'purchase_address' => $request->input('address'),
            'purchase_building' => $request->input('building'),
        ]);

        return redirect()->route('item.purchase', $item->id);
    }
    public function checkout(PurchaseRequest $request, $id)
    {
        $item = Item::find($id);
        $user = auth()->user();

        // バリデーション済み
        $paymentId = $request->validated()['payment'];
        session(['purchase_payment_id' => $paymentId]);


        if ($paymentId == 1) {
            $paymentMethod = 'card';
        } elseif ($paymentId == 2) {
            $paymentMethod = 'konbini';
        }

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => [$paymentMethod],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->name,
                    ],
                    'unit_amount' => $item->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('item.purchase.success') . '?id=' . $item->id,
            'cancel_url' => route('item.purchase', $item->id),
        ]);
        $publicKey = env('STRIPE_PUBLIC_KEY');

        return view('item.checkout', compact('session', 'publicKey'));
    }

    public function success(Request $request)
    {
        $item = Item::find($request->query('id'));
        $user = auth()->user();

        $order = new Order();
        $order->user_id = $user->id;
        $order->item_id = $item->id;
        $order->price = $item->price;
        $order->payment_id = session('purchase_payment_id');
        $order->post_code = session('purchase_post_code') ?? $user->post_code;
        $order->address   = session('purchase_address') ?? $user->address;
        $order->building  = session('purchase_building') ?? $user->building;
        $order->save();

        $deal = new Deal();
        $deal->item_id = $item->id;
        $deal->buyer_id = $user->id;
        $deal->seller_id = $item->user_id;
        $deal->save();

        return redirect()->route('index');
    }
}
