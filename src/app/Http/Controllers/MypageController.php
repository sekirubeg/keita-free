<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AddressRequest;


class MypageController extends Controller
{
    //
    public function index(Request $request)
{
    $user = Auth::user();
    $page = $request->query('page', 'sell'); // デフォルトは "sell"

    if ($page === 'buy') {
            // 購入した商品（orders 経由で item を取得）
            $items = Item::whereIn('id', Order::where('user_id', $user->id)->pluck('item_id'))
                ->withCount('likes')
                ->paginate(8);

            return view('mypage.purchased', compact('user', 'items'));
    } else {
        // 出品した商品
        $items = $user->items()->withCount('likes')->paginate(8);
        return view('mypage.profile', compact('user', 'items'));
    }
}

    public function edit(Item $item)
    {
        $user = Auth::user();
        return view('mypage.edit', compact('user', 'item'));
    }

    public function update(AddressRequest $request)
    {
        $user = Auth::user();
        $user->name = $request->name;
        $user->post_code = $request->post_code;
        $user->address = $request->address;
        $user->building = $request->building;
        //画像が選択されている場合
        if ($request->hasFile('image_at')) {
            // 画像を /storage/app/public/images ディレクトリに保存し、パスを取得
            $path = $request->file('image_at')->store('images', 'public');

            // 実際にDBへ保存するのは $user->image_at カラムに対して行う
            $user->image_at = $path;
        };
        $user->save();
        return redirect()->route('index');
    }

}
