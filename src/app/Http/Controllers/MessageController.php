<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\MessageRequest;
use App\Models\Item;
use App\Models\User;
use App\Models\Deal;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    //
    public function store(Request $request)
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

    public function update(Request $request, Message $message)
    {
        // 認可ポリシーなどで、メッセージの所有者かを確認する
        // $this->authorize('update', $message);

        $request->validate([
            'content' => 'required|string|max:255',
        ]);

        $message->content = $request->input('content');
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
