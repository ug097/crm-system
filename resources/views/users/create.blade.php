@extends('layouts.app')

@section('title', 'ユーザー登録')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="text-center mb-4">
            <div class="rounded-circle bg-primary bg-gradient d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                <i class="bi bi-person-plus-fill text-white fs-1"></i>
            </div>
            <h2 class="fw-bold text-primary mb-2">新規ユーザー登録</h2>
            <p class="text-muted">新しいユーザーをシステムに追加します</p>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-5">
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">名前</label>
                        <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" 
                               id="name" name="name" required value="{{ old('name') }}" 
                               placeholder="山田 太郎">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold">メールアドレス</label>
                        <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" 
                               id="email" name="email" required value="{{ old('email') }}" 
                               placeholder="user@example.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-bold">パスワード</label>
                        <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" 
                               id="password" name="password" required 
                               placeholder="••••••••">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">8文字以上のパスワードを設定してください</small>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label fw-bold">パスワード（確認）</label>
                        <input type="password" class="form-control form-control-lg" 
                               id="password_confirmation" name="password_confirmation" required 
                               placeholder="••••••••">
                    </div>

                    <div class="mb-4">
                        <label for="role" class="form-label fw-bold">権限</label>
                        <select class="form-select form-select-lg @error('role') is-invalid @enderror" 
                                id="role" name="role" required>
                            <option value="">選択してください</option>
                            <option value="member" {{ old('role') === 'member' ? 'selected' : '' }}>一般メンバー</option>
                            <option value="manager" {{ old('role') === 'manager' ? 'selected' : '' }}>マネージャー</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>管理者</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">ユーザーの権限レベルを選択してください</small>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">
                        <i class="bi bi-person-check me-2"></i>登録
                    </button>
                    <div class="text-center pt-3 border-top">
                        <a href="{{ route('dashboard') }}" class="text-primary fw-bold text-decoration-none">
                            <i class="bi bi-arrow-left me-1"></i>ダッシュボードに戻る
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
