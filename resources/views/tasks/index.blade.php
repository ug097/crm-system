@extends('layouts.app')

@section('title', 'タスク一覧')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="display-5 fw-bold text-success mb-2">タスク一覧</h1>
        <p class="text-muted">すべてのタスクを管理</p>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        @forelse($tasks as $task)
            <a href="{{ route('tasks.show', $task) }}" class="text-decoration-none text-dark">
                <div class="card border-0 border-bottom rounded-0">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="bg-success bg-gradient rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                        <i class="bi bi-check-square-fill text-white fs-5"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="card-title mb-1 fw-bold">{{ $task->title }}</h5>
                                        <div class="d-flex gap-3 flex-wrap">
                                            <small class="text-muted">
                                                <i class="bi bi-folder me-1"></i>プロジェクト: {{ $task->project->name }}
                                            </small>
                                            @if($task->due_date)
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar-event me-1"></i>期限: {{ $task->due_date->format('Y/m/d') }}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-end">
                                <div class="d-flex gap-2 justify-content-end">
                                    <span class="badge 
                                        @if($task->status === 'done') bg-success
                                        @elseif($task->status === 'in_progress') bg-primary
                                        @elseif($task->status === 'review') bg-warning text-dark
                                        @else bg-secondary
                                        @endif fs-6 px-3 py-2">
                                        {{ $task->status_label }}
                                    </span>
                                    <span class="badge 
                                        @if($task->priority === 'urgent') bg-danger
                                        @elseif($task->priority === 'high') bg-warning text-dark
                                        @elseif($task->priority === 'medium') bg-info text-dark
                                        @else bg-secondary
                                        @endif fs-6 px-3 py-2">
                                        {{ $task->priority_label }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div class="text-center py-5">
                <i class="bi bi-check-square fs-1 text-muted"></i>
                <h5 class="mt-3 text-muted">タスクがありません</h5>
                <p class="text-muted">新しいタスクを作成して始めましょう</p>
            </div>
        @endforelse
    </div>
</div>

<div class="mt-4">
    {{ $tasks->links() }}
</div>
@endsection
