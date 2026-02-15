@extends('layouts.app')

@section('title', 'タスク作成')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="display-5 fw-bold text-success mb-2">新規タスク作成</h1>
        <p class="text-muted">プロジェクト: <span class="fw-bold text-primary">{{ $project->name }}</span></p>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-success bg-gradient text-white">
        <h5 class="card-title mb-0 fw-bold">
            <i class="bi bi-info-circle me-2"></i>タスク情報
        </h5>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('tasks.store', $project) }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="title" class="form-label fw-bold">
                    タイトル <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control form-control-lg" id="title" name="title" required value="{{ old('title') }}" placeholder="タスクのタイトルを入力">
            </div>

            <div class="mb-3">
                <label for="description" class="form-label fw-bold">説明</label>
                <textarea class="form-control" id="description" name="description" rows="4" placeholder="タスクの説明を入力">{{ old('description') }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="status" class="form-label fw-bold">
                        ステータス <span class="text-danger">*</span>
                    </label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="todo" {{ old('status', 'todo') === 'todo' ? 'selected' : '' }}>未着手</option>
                        <option value="in_progress" {{ old('status') === 'in_progress' ? 'selected' : '' }}>進行中</option>
                        <option value="review" {{ old('status') === 'review' ? 'selected' : '' }}>レビュー中</option>
                        <option value="done" {{ old('status') === 'done' ? 'selected' : '' }}>完了</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="priority" class="form-label fw-bold">
                        優先度 <span class="text-danger">*</span>
                    </label>
                    <select class="form-select" id="priority" name="priority" required>
                        <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>低</option>
                        <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>中</option>
                        <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>高</option>
                        <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>緊急</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="due_date" class="form-label fw-bold">期限</label>
                    <input type="date" class="form-control" id="due_date" name="due_date" value="{{ old('due_date') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="assigned_to" class="form-label fw-bold">担当者</label>
                    <select class="form-select" id="assigned_to" name="assigned_to">
                        <option value="">未割り当て</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <label for="estimated_hours" class="form-label fw-bold">見積時間（時間）</label>
                <input type="number" class="form-control" id="estimated_hours" name="estimated_hours" min="0" value="{{ old('estimated_hours') }}" placeholder="0.0">
            </div>

            <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                <a href="{{ route('projects.show', $project) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle me-2"></i>キャンセル
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-plus-circle me-2"></i>作成
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
