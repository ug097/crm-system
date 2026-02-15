@extends('layouts.app')

@section('title', 'プロジェクト一覧')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h1 class="display-5 fw-bold text-primary mb-2">プロジェクト一覧</h1>
                <p class="text-muted">すべてのプロジェクトを管理</p>
            </div>
            <a href="{{ route('projects.create') }}" class="btn btn-primary btn-lg mt-2">
                <i class="bi bi-plus-circle me-2"></i>新規プロジェクト
            </a>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        @forelse($projects as $project)
            <a href="{{ route('projects.show', $project) }}" class="text-decoration-none text-dark">
                <div class="card border-0 border-bottom rounded-0">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="bg-primary bg-gradient rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                        <i class="bi bi-folder-fill text-white fs-5"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="card-title mb-1 fw-bold">{{ $project->name }}</h5>
                                        @if($project->description)
                                            <p class="card-text text-muted small mb-2">
                                                {{ \Illuminate\Support\Str::limit($project->description, 100) }}
                                            </p>
                                        @endif
                                        <div class="d-flex gap-3 flex-wrap">
                                            <small class="text-muted">
                                                <i class="bi bi-person me-1"></i>{{ $project->creator->name }}
                                            </small>
                                            @if($project->start_date)
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar-event me-1"></i>開始: {{ $project->start_date->format('Y/m/d') }}
                                                </small>
                                            @endif
                                            @if($project->end_date)
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar-check me-1"></i>終了: {{ $project->end_date->format('Y/m/d') }}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-end">
                                <span class="badge 
                                    @if($project->status === 'in_progress') bg-primary
                                    @elseif($project->status === 'completed') bg-success
                                    @elseif($project->status === 'on_hold') bg-warning text-dark
                                    @else bg-secondary
                                    @endif fs-6 px-3 py-2">
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
                </div>
            </a>
        @empty
            <div class="text-center py-5">
                <i class="bi bi-folder-x fs-1 text-muted"></i>
                <h5 class="mt-3 text-muted">プロジェクトがありません</h5>
                <p class="text-muted">新しいプロジェクトを作成して始めましょう</p>
                <a href="{{ route('projects.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>新規プロジェクトを作成
                </a>
            </div>
        @endforelse
    </div>
</div>

<div class="mt-4">
    {{ $projects->links() }}
</div>
@endsection
