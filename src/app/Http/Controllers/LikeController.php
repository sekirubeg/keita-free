<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\User;

class LikeController extends Controller
{
    //
    public function store(Request $request, $id)
    {
        Auth::user()->like($id);
        return back();
    }
    public function destroy(Request $request, $id)
    {
        Auth::user()->unlike($id);
        return response()->json(['message' => 'Deleted successfully'], 200);
    }
}
