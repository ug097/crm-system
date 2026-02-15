@extends('layouts.app')

@section('title', 'プロジェクト作成')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="display-5 fw-bold text-primary mb-2">新規プロジェクト作成</h1>
        <p class="text-muted">新しいプロジェクトを開始します</p>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-primary bg-gradient text-white">
        <h5 class="card-title mb-0 fw-bold">
            <i class="bi bi-info-circle me-2"></i>プロジェクト情報
        </h5>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('projects.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label fw-bold">
                    プロジェクト名 <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control form-control-lg" id="name" name="name" required value="{{ old('name') }}" placeholder="プロジェクト名を入力">
            </div>

            <div class="mb-3">
                <label for="description" class="form-label fw-bold">説明</label>
                <textarea class="form-control" id="description" name="description" rows="4" placeholder="プロジェクトの説明を入力">{{ old('description') }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="start_date" class="form-label fw-bold">開始日</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ old('start_date') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="end_date" class="form-label fw-bold">終了日</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ old('end_date') }}">
                </div>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label fw-bold">
                    ステータス <span class="text-danger">*</span>
                </label>
                <select class="form-select" id="status" name="status" required>
                    <option value="planning" {{ old('status') === 'planning' ? 'selected' : '' }}>計画中</option>
                    <option value="in_progress" {{ old('status') === 'in_progress' ? 'selected' : '' }}>進行中</option>
                    <option value="on_hold" {{ old('status') === 'on_hold' ? 'selected' : '' }}>保留</option>
                    <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>完了</option>
                    <option value="cancelled" {{ old('status') === 'cancelled' ? 'selected' : '' }}>キャンセル</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="managers" class="form-label fw-bold">プロジェクトマネージャー</label>
                <select class="form-select" id="managers" name="managers[]" multiple size="4">
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ in_array($user->id, old('managers', [auth()->id()])) ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
                <small class="form-text text-muted">Ctrl（Macの場合はCmd）キーを押しながらクリックで複数選択。未選択の場合は作成者がマネージャーになります。</small>
            </div>

            <div class="mb-4">
                <label for="members" class="form-label fw-bold">メンバー</label>
                <select class="form-select" id="members" name="members[]" multiple size="5">
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ in_array($user->id, old('members', [])) ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
                <small class="form-text text-muted">Ctrl（Macの場合はCmd）キーを押しながらクリックで複数選択</small>
            </div>

            <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle me-2"></i>キャンセル
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>作成
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
