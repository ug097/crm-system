@extends('layouts.app')

@section('title', 'タスク編集')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="display-5 fw-bold text-success mb-2">タスク編集</h1>
        <p class="text-muted">プロジェクト: <span class="fw-bold text-primary">{{ $task->project->name }}</span></p>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-success bg-gradient text-white">
        <h5 class="card-title mb-0 fw-bold">
            <i class="bi bi-info-circle me-2"></i>タスク情報
        </h5>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('tasks.update', $task) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="title" class="form-label fw-bold">
                    タイトル <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control form-control-lg" id="title" name="title" required value="{{ old('title', $task->title) }}" placeholder="タスクのタイトルを入力">
            </div>

            <div class="mb-3">
                <label for="description" class="form-label fw-bold">説明</label>
                <textarea class="form-control" id="description" name="description" rows="4" placeholder="タスクの説明を入力">{{ old('description', $task->description) }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="status" class="form-label fw-bold">
                        ステータス <span class="text-danger">*</span>
                    </label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="todo" {{ old('status', $task->status) === 'todo' ? 'selected' : '' }}>未着手</option>
                        <option value="in_progress" {{ old('status', $task->status) === 'in_progress' ? 'selected' : '' }}>進行中</option>
                        <option value="review" {{ old('status', $task->status) === 'review' ? 'selected' : '' }}>レビュー中</option>
                        <option value="done" {{ old('status', $task->status) === 'done' ? 'selected' : '' }}>完了</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="priority" class="form-label fw-bold">
                        優先度 <span class="text-danger">*</span>
                    </label>
                    <select class="form-select" id="priority" name="priority" required>
                        <option value="low" {{ old('priority', $task->priority) === 'low' ? 'selected' : '' }}>低</option>
                        <option value="medium" {{ old('priority', $task->priority) === 'medium' ? 'selected' : '' }}>中</option>
                        <option value="high" {{ old('priority', $task->priority) === 'high' ? 'selected' : '' }}>高</option>
                        <option value="urgent" {{ old('priority', $task->priority) === 'urgent' ? 'selected' : '' }}>緊急</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="due_date" class="form-label fw-bold">期限</label>
                    <input type="date" class="form-control" id="due_date" name="due_date" value="{{ old('due_date', $task->due_date?->format('Y-m-d')) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="assigned_to" class="form-label fw-bold">担当者</label>
                    <select class="form-select" id="assigned_to" name="assigned_to">
                        <option value="">未割り当て</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('assigned_to', $task->assigned_to) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <label for="estimated_hours" class="form-label fw-bold">見積時間（時間）</label>
                <input type="number" class="form-control" id="estimated_hours" name="estimated_hours" min="0" value="{{ old('estimated_hours', $task->estimated_hours) }}" placeholder="0.0">
            </div>

            <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                <a href="{{ route('tasks.show', $task) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle me-2"></i>キャンセル
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle me-2"></i>更新
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
