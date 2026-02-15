<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * 管理者専用: 新規ユーザー登録フォームを表示
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * 管理者専用: 新規ユーザーを登録
     */
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        return redirect()->route('users.create')
            ->with('success', "ユーザー「{$user->name}」を登録しました。");
    }
}
