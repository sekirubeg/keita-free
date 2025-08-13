<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageRequest;
use App\Models\Item;
use App\Models\Deal;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    //
    public function store(MessageRequest $request)
    {
        $item = Item::findOrFail($request->item_id);
        $deal = Deal::where('item_id', $item->id)
            ->where(function ($query) {
                $query->where('buyer_id', Auth::id())
                    ->orWhere('seller_id', Auth::id());
            })
            ->firstOrFail();

        $message = new Message();
        $message->content = $request->content;
        $message->deal_id = $deal->id;
        $message->sender_id = Auth::id();
        if ($request->hasFile('image_at')) {
            $path = $request->file('image_at')->store('images', 'public');
            $message->image_at = $path;
        }
        $message->save();

        return redirect()->route('item.transaction', ['id' => $item->id]);
    }

    public function update(MessageRequest $request, Message $message)
    {
        // 認可ポリシーなどで、メッセージの所有者かを確認する
        // $this->authorize('update', $message);

        $message->content = $request->input('content');
        // 新しい画像がアップロードされた場合
        if ($request->hasFile('image_at')) {
            // 古い画像があれば削除
            if ($message->image_at) {
                Storage::disk('public')->delete($message->image_at);
            }

            // 新しい画像を保存してパスを更新
            $path = $request->file('image_at')->store('images', 'public');
            $message->image_at = $path;
        }
        $message->save();

        // 適切なリダイレクト先を指定
        return redirect()->back();
    }

    public function destroy(Message $message)
    {
        $message->delete();
        return redirect()->route('item.transaction', ['id' => $message->deal->item_id]);
    }
}
