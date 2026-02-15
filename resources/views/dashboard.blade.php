@extends('layouts.app')

@section('title', 'ダッシュボード')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="display-5 fw-bold text-primary mb-2">ダッシュボード</h1>
        <p class="text-muted">プロジェクトとタスクの概要を確認</p>
    </div>
</div>

<!-- 統計カード -->
<div class="row g-4 mb-5">
    <div class="col-md-6 col-lg-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <p class="text-muted small mb-2">プロジェクト数</p>
                        <h2 class="fw-bold text-primary mb-0">{{ $stats['total_projects'] }}</h2>
                    </div>
                    <div class="bg-primary bg-gradient rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-folder-fill text-white fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-lg-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <p class="text-muted small mb-2">進行中プロジェクト</p>
                        <h2 class="fw-bold text-success mb-0">{{ $stats['active_projects'] }}</h2>
                    </div>
                    <div class="bg-success bg-gradient rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-check-circle-fill text-white fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-lg-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <p class="text-muted small mb-2">未完了タスク</p>
                        <h2 class="fw-bold text-warning mb-0">{{ $stats['my_tasks'] }}</h2>
                    </div>
                    <div class="bg-warning bg-gradient rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-list-check text-white fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-lg-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <p class="text-muted small mb-2">今月の工数（時間）</p>
                        <h2 class="fw-bold text-info mb-0">{{ number_format($stats['total_hours_this_month'], 1) }}</h2>
                    </div>
                    <div class="bg-info bg-gradient rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-clock-fill text-white fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- 最近のプロジェクト -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-primary bg-gradient text-white border-0">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="bi bi-folder me-2"></i>最近のプロジェクト
                </h5>
            </div>
            <div class="card-body">
                @forelse($recentProjects as $project)
                    <div class="card mb-3 border">
                        <div class="card-body p-3 position-relative">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="card-title mb-1">
                                        <a href="{{ route('projects.show', $project) }}" class="text-decoration-none text-dark fw-bold stretched-link">
                                            {{ $project->name }}
                                        </a>
                                    </h6>
                                    @if($project->description)
                                        <p class="card-text text-muted small mb-2">
                                            {{ \Illuminate\Support\Str::limit($project->description, 60) }}
                                        </p>
                                    @endif
                                </div>
                                <span class="badge 
                                    @if($project->status === 'in_progress') bg-primary
                                    @elseif($project->status === 'completed') bg-success
                                    @elseif($project->status === 'on_hold') bg-warning text-dark
                                    @else bg-secondary
                                    @endif">
                                    @if($project->status === 'planning') 計画中
                                    @elseif($project->status === 'in_progress') 進行中
                                    @elseif($project->status === 'on_hold') 保留
                                    @elseif($project->status === 'completed') 完了
                                    @else キャンセル
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="bi bi-folder-x fs-1 text-muted"></i>
                        <p class="text-muted mt-3">プロジェクトがありません</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- 最近のタスク -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-success bg-gradient text-white border-0">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="bi bi-check-square me-2"></i>最近のタスク
                </h5>
            </div>
            <div class="card-body">
                @forelse($recentTasks as $task)
                    <div class="card mb-3 border">
                        <div class="card-body p-3 position-relative">
                            <h6 class="card-title mb-1">
                                <a href="{{ route('tasks.show', $task) }}" class="text-decoration-none text-dark fw-bold stretched-link">
                                    {{ $task->title }}
                                </a>
                            </h6>
                            <p class="card-text text-muted small mb-2">
                                <i class="bi bi-folder me-1"></i>{{ $task->project->name }}
                            </p>
                            <div class="d-flex gap-2 flex-wrap">
                                <span class="badge 
                                    @if($task->priority === 'urgent') bg-danger
                                    @elseif($task->priority === 'high') bg-warning text-dark
                                    @elseif($task->priority === 'medium') bg-info text-dark
                                    @else bg-secondary
                                    @endif">
                                    @if($task->priority === 'urgent') 緊急
                                    @elseif($task->priority === 'high') 高
                                    @elseif($task->priority === 'medium') 中
                                    @else 低
                                    @endif
                                </span>
                                @if($task->due_date)
                                    <span class="badge bg-light text-dark">
                                        <i class="bi bi-calendar me-1"></i>{{ $task->due_date->format('Y/m/d') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="bi bi-check-square fs-1 text-muted"></i>
                        <p class="text-muted mt-3">タスクがありません</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
