<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Item;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    //
   
    public function store(CommentRequest $request)
    {
        $item = Item::with('user')->findOrFail($request->item_id);

        $comment = new Comment();
        $comment->body = $request->body;
        $comment->item_id = $item->id;
        $comment->user_id = Auth::id();
        $comment->save();

        return redirect()->route('item.show', ['id' => $item->id]);
    }

    public function destroy(Comment $comment){
        $comment->delete();
        return redirect()->route('item.show', ['id' => $comment->item_id]);
    }
}
