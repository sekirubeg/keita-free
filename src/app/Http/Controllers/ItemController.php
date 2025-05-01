<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\User;
use App\Models\Tag;
use Illuminate\support\Facades\Auth;
use Illuminate\support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    //
    public function index()
    {
        $items = Item::paginate(8);
        return view('index', compact("items"));
    }
    public function create(Item $item){
        $tags = Tag::get();
        return view('item.create', compact('tags', 'item'));
    }
    public function store(Request $request)
    {

        $item = new Item();
        $item->name = $request->name;
        $item->description = $request->description;
        $item->user_id = Auth::id();
        $item->price = $request->price;
        $item->brand = $request->brand;
        if ($request->hasFile('image_at')) {
            $path = $request->file('image_at')->store('images', 'public');
            $item->image_at = $path;
        }
        $item->save();
        $item->tags()->sync($request->tags);


        return redirect()->route('index');
    }

    public function show($id)
    {
        $item = Item::with(['tags','comments', 'user'])->withCount('likes')->withCount('comments')->find($id);
        return view('item.show', compact("item"));
    }
}
