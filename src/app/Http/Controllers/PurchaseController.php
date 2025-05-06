<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AddressRequest;
use App\Models\Item;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    //
    public function index($id)
    {
        $item = Item::find($id);
        $user = auth()->user();
        $order = Order::where('user_id', $user->id)
                  ->where('item_id', $item->id)
                  ->latest()
                  ->first();

        return view('item.purchase', compact('item', 'user', 'order'));
    }

    public function address($id)
    {
        $item = Item::find($id);
        $user = auth()->user();

        return view('item.address', compact('item', 'user'));
    }
    public function change(AddressRequest $request, $id)
    {
        $item = Item::find($id);
        $user = auth()->user();

        Order::create([
            'user_id' => Auth::id(),
            'item_id' => $item->id,
            'post_code' => $request->input('post_code'),
            'address' => $request->input('address'),
            'building' => $request->input('building'),
            // 商品や金額など他の注文情報もここで保存
            'price' => $item->price,
        ]);
        // 購入処理をここに追加
        // 例: Order::create([...]);

        return redirect()->route('item.purchase', $item->id);
    }
}
