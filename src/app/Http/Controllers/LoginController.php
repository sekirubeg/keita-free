<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;



class LoginController extends Controller
{
    //
    public function index()
    {
        return view('auth.login');
    }

     public function store(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // セッション固定攻撃対策
            return redirect()->intended('/'); // ログイン成功後のリダイレクト先
        }

        return back()->withErrors([
            'email' => 'ログイン情報が登録されていません。',
        ])->withInput();

        }
}
