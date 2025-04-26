<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MypageController extends Controller
{
    //
    public function index()
    {
        $user = Auth::user();
        return view('mypage.profile' , compact('user'));
    }
    public function edit()
    {
        $user = Auth::user();
        return view('mypage.edit' , compact('user'));
    }

    public function update(Request $request)
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

        return redirect()->route('mypage');
    }
}
