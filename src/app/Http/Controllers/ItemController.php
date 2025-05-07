<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ExhibitionRequest;
use App\Models\Item;
use App\Models\User;
use App\Models\Tag;
use App\Models\Order;
use Illuminate\support\Facades\Auth;
use Illuminate\support\Facades\Storage;
use Illuminate\Support\Facades\DB;

use function PHPSTORM_META\map;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        // $sort = $request->input('sort', 'desc');
        // $searchInput = $request->input('search');
        // if ($request->has('search')) {
        //     $search = $request->input('search');

        //     if ($search === '') {
        //         session()->forget('search');
        //     } else {
        //         session(['search' => $search]);
        //     }
        // }

        // $itemIds = Order::pluck('item_id')->toArray();

        // $search = session('search');
        // if ($search) {
        //     $items = Item::where('name', 'LIKE', "%{$search}%")->when(Auth::check(), function ($query) {
        //         return $query->where('user_id', '!=', Auth::id());
        //     })->orderBy('created_at', $sort)->paginate(8);
        // } else {
        //     $items = Item::with(['tags', 'user'])->withCount('likes')->when(Auth::check(), function ($query) {
        // return $query->where('user_id', '!=', Auth::id());})->withCount('comments')->orderBy('created_at', $sort)->paginate(8);
        // }
        // return view('index', compact("items", "search", "itemIds", "sort"));
        $sort = $request->input('sort', 'desc');
        $searchInput = $request->input('search');

        if ($request->has('search')) {
            if ($searchInput === '') {
                session()->forget('search');
            } else {
                session(['search' => $searchInput]);
            }
        }

        $search = $searchInput ?? session('search');
        $itemIds = Order::pluck('item_id')->toArray();

        $itemsQuery = Item::with(['tags', 'user'])
            ->withCount(['likes', 'comments'])
            ->when($search, function ($query) use ($search) {
                return $query->where('name', 'LIKE', "%{$search}%");
            })
            ->when(Auth::check(), function ($query) {
                return $query->where('user_id', '!=', Auth::id());
            })
            ->orderBy('created_at', $sort);

        $items = $itemsQuery->paginate(8);

        return view('index', compact('items', 'search', 'itemIds', 'sort'));
    }
    public function mylist(Request $request)
    {
        $sort = $request->input('sort', 'desc');
        $search = session('search');
        $id = Auth::id();
        $user = User::with(['items', 'likes' => function ($query) {
            $query->withCount('likes');
        }])->find($id);
        $items = $user->likes()->where('name', 'LIKE', "%{$search}%")
            ->withCount('likes')->orderBy('created_at', $sort)->paginate(8);
        $itemIds = Order::pluck('item_id')->toArray();
        return view('item.mylist', compact("items", "search", "itemIds", "sort"));
    }

    public function create(Item $item)
    {
        $tags = Tag::get();
        return view('item.create', compact('tags', 'item'));
    }
    public function store(ExhibitionRequest $request)
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
        $user = Auth::user();
        $item = Item::with(['tags', 'comments', 'user'])->withCount('likes')->withCount('comments')->find($id);
        $itemIds = Order::pluck('item_id')->toArray();
        return view('item.show', compact("item", "user", "itemIds"));
    }
}
