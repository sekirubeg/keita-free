<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\User;
use App\Models\Tag;
use Illuminate\support\Facades\Auth;
use Illuminate\support\Facades\Storage;


class ItemController extends Controller
{
    //
    public function index()
    {
        $items = Item::paginate(8);
        return view('index', compact("items"));
    }
    public function create(){
        $tags = Tag::get();
        return view('item.create', compact('tags'));
    }

    public function show($id)
    {
        $item = Item::with(['comments', 'user'])->withCount('likes')->find($id);
        return view('item.show', compact("item"));
    }
}
