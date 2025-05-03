<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class PurchaseController extends Controller
{
    //
    public function index($id)
    {
        $item = Item::find($id);
        $user = auth()->user();

        return view('item.purchase', compact('item', 'user'));
    }

    public function address($id)
    {
        $item = Item::find($id);
        $user = auth()->user();

        return view('item.address', compact('item', 'user'));
    }
}
