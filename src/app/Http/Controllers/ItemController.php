<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ExhibitionRequest;
use App\Models\Item;
use App\Models\User;
use App\Models\Deal;
use App\Models\Tag;
use App\Models\Order;
use App\Models\Evaluation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail; // Mailファサードをuse

class ItemController extends Controller
{
    public function index(Request $request)
    {

        $sort = $request->input('sort', 'desc');
        $searchInput = $request->input('search');
        $pageType = $request->query('page', 'all'); // デフォルトは 'all'

        if ($request->has('search')) {
            if ($searchInput === '') {
                session()->forget('search');
            } else {
                session(['search' => $searchInput]);
            }
        }

        $search = $searchInput ?? session('search');
        $itemIds = Order::pluck('item_id')->toArray();
        if ($pageType === 'mylist') {
            if (!Auth::check()) {
                return redirect()->route('login');
            }

            // マイリスト表示（いいねした商品）
            $user = Auth::user();
            $items = $user->likes()
                ->where('name', 'LIKE', "%{$search}%")
                ->with('tags', 'user')
                ->where('items.user_id', '!=', $user->id)
                ->withCount(['likes', 'comments'])
                ->orderBy('created_at', $sort)
                ->paginate(8);

            return view('index', compact('items', 'search', 'itemIds', 'sort', 'pageType'));
        }
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
    public function transaction($id)
    {

        $user = Auth::user();
        $item = Item::with(['tags', 'comments', 'user'])->withCount('likes')->withCount('comments')->find($id);
        // $itemIds = Order::pluck('item_id')->toArray();
        // item_idが一致する取引を取得する
        $deal = Deal::where('item_id', $id)->first();
        if ($deal && $deal->seller_id === $user->id) {
            // 自分が売り手の場合、自分が売り手となっている他の取引を取得
            $ongoingDeals = Deal::where('seller_id', $user->id)
                ->where('id', '!=', $deal->id) // 今見ている取引は除外
                ->with('item', 'buyer', 'seller')
                ->whereDoesntHave('evaluations', function ($query) use ($user) {
                    $query->where('evaluator_id', $user->id);
                })
                ->latest()
                ->get();
            $authority = false;
        } else {
            // それ以外の場合（自分が買い手、または取引が存在しない場合）は、
            // 自分が買い手になっている他の取引を取得します
            $ongoingDeals = Deal::where('buyer_id', $user->id)
                ->where('id', '!=', $deal->id) // 今見ている取引は除外
                ->with('item', 'buyer', 'seller')
                ->whereDoesntHave('evaluations', function ($query) use ($user) {
                    $query->where('evaluator_id', $user->id);
                })
                ->latest()
                ->get();
            $authority = true;
        }

        $deal->messages()
            ->where('sender_id', '!=', auth()->id()) // 送信者が自分ではない
            ->where('read_at', null) // 未読である
            ->update(['read_at' => now()]); // 現在時刻で更新

        $shouldShowEvaluationModal = false;
            if ($deal->completed_at && Auth::id() === $deal->seller_id) {
                if (!$deal->hasEvaluatedBy(Auth::id())) {
                    $shouldShowEvaluationModal = true;
                }
            }

        return view('item.transaction', compact("item", "user", "deal", "ongoingDeals", "authority", "shouldShowEvaluationModal"));
    }

    public function complete(Request $request, Deal $deal)
    {
        // 1. バリデーション
        $request->validate([
            'rating' => 'required|integer|between:1,5',
        ]);

        // 2. 評価対象のユーザーを特定
        $evaluatedUserId = (Auth::id() === $deal->buyer_id) ? $deal->seller_id : $deal->buyer_id;

        // 3. 既に評価済みかチェック（二重評価防止）
        $existingEvaluation = Evaluation::where('deal_id', $deal->id)
            ->where('evaluator_id', Auth::id())
            ->first();

        // 4. データベースに評価を保存
        Evaluation::create([
            'deal_id' => $deal->id,
            'evaluator_id' => Auth::id(),
            'evaluated_id' => $evaluatedUserId,
            'rating' => $request->rating,
        ]);

        // 5. 取引完了状態に更新
        $deal->update(['completed_at' => now()]);

        // 評価されたユーザー（出品者）のメールアドレスを取得
        $sellerEmail = $deal->seller->email;

        // メール本文の作成
        $mailBody = "購入者から評価が届きました。\n\n"
            . "評価: {$request->rating}点\n\n";

        // メールを送信
        Mail::raw(
            $mailBody,
            function ($message) use ($sellerEmail) {
                $message->to($sellerEmail)
                    ->subject('購入者から評価が届きました');
                });
        // 6. 完了後は商品一覧画面に遷移
        return redirect()->route('index');
    }
}



