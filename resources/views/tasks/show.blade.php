@extends('layouts.app')

@section('title', $task->title)

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-start flex-wrap">
            <div>
                <h1 class="display-5 fw-bold text-success mb-2">
                    <i class="bi bi-check-square-fill me-2"></i>{{ $task->title }}
                </h1>
                <p class="text-muted">
                    プロジェクト: <a href="{{ route('projects.show', $task->project) }}" class="text-primary fw-bold text-decoration-none">{{ $task->project->name }}</a>
                </p>
            </div>
            <a href="{{ route('tasks.edit', $task) }}" class="btn btn-outline-secondary mt-2">
                <i class="bi bi-pencil me-2"></i>編集
            </a>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-success bg-gradient text-white">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="bi bi-file-text me-2"></i>詳細
                </h5>
            </div>
            <div class="card-body">
                @if($task->description)
                    <p class="mb-0" style="white-space: pre-wrap;">{{ $task->description }}</p>
                @else
                    <p class="text-muted mb-0">説明がありません</p>
                @endif
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-warning bg-gradient text-dark">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="bi bi-clock-history me-2"></i>工数記録
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#timeEntryModal" onclick="openNewTimeEntryModal()">
                        <i class="bi bi-plus-circle me-2"></i>工数を記録
                    </button>
                </div>
                @forelse($task->timeEntries as $entry)
                    <div class="card mb-2 border time-entry-card" 
                         data-entry-id="{{ $entry->id }}"
                         data-entry-date="{{ $entry->date->format('Y-m-d') }}"
                         data-entry-hours="{{ $entry->hours }}"
                         data-entry-description="{{ $entry->description ?? '' }}"
                         data-entry-task-id="{{ $entry->task_id ?? '' }}"
                         data-entry-user-id="{{ $entry->user_id }}"
                         style="cursor: pointer;">
                        <div class="card-body py-2">
                            <div class="d-flex align-items-center">
                                <div class="bg-warning bg-gradient rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="bi bi-clock-fill text-white"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold">
                                        {{ $entry->date->format('Y年m月d日') }} - <span class="text-warning">{{ number_format($entry->hours, 1) }}時間</span>
                                    </div>
                                    @if($entry->description)
                                        <div><small class="text-muted">{{ $entry->description }}</small></div>
                                    @endif
                                    <small class="text-muted">記録者: {{ $entry->user->name }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center py-3">工数記録がありません</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-secondary bg-gradient text-white">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="bi bi-info-circle me-2"></i>タスク情報
                </h5>
            </div>
            <div class="card-body">
                <dl class="mb-0">
                    <dt class="small text-muted mb-1">ステータス</dt>
                    <dd class="mb-3">
                        <span class="badge 
                            @if($task->status === 'done') bg-success
                            @elseif($task->status === 'in_progress') bg-primary
                            @elseif($task->status === 'review') bg-warning text-dark
                            @else bg-secondary
                            @endif">
                            {{ $task->status_label }}
                        </span>
                    </dd>
                    <dt class="small text-muted mb-1">優先度</dt>
                    <dd class="mb-3">
                        <span class="badge 
                            @if($task->priority === 'urgent') bg-danger
                            @elseif($task->priority === 'high') bg-warning text-dark
                            @elseif($task->priority === 'medium') bg-info text-dark
                            @else bg-secondary
                            @endif">
                            {{ $task->priority_label }}
                        </span>
                    </dd>
                    @if($task->due_date)
                        <dt class="small text-muted mb-1">期限</dt>
                        <dd class="mb-3 fw-bold">{{ $task->due_date->format('Y年m月d日') }}</dd>
                    @endif
                    @if($task->assignee)
                        <dt class="small text-muted mb-1">担当者</dt>
                        <dd class="mb-3 fw-bold">{{ $task->assignee->name }}</dd>
                    @endif
                    @if($task->estimated_hours)
                        <dt class="small text-muted mb-1">見積時間</dt>
                        <dd class="mb-3 fw-bold">{{ $task->estimated_hours }}時間</dd>
                    @endif
                    @if($task->total_hours > 0)
                        <dt class="small text-muted mb-1">実績時間</dt>
                        <dd class="mb-3">
                            <h5 class="text-primary fw-bold mb-0">{{ number_format($task->total_hours, 1) }}時間</h5>
                        </dd>
                    @endif
                    <dt class="small text-muted mb-1">作成者</dt>
                    <dd class="mb-0 fw-bold">{{ $task->creator->name }}</dd>
                </dl>
            </div>
        </div>
    </div>
</div>

<!-- データ属性として値を埋め込む（JavaScriptで使用） -->
<div id="timeEntryStoreUrl" data-url="{{ route('time-entries.store', $task->project) }}" style="display: none;"></div>
<div id="currentUserId" data-user-id="{{ auth()->id() }}" data-today="{{ date('Y-m-d') }}" style="display: none;"></div>
<div id="currentTaskId" data-task-id="{{ $task->id }}" style="display: none;"></div>

<!-- 工数記録編集・削除・新規登録モーダル -->
<div class="modal fade" id="timeEntryModal" tabindex="-1" aria-labelledby="timeEntryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning bg-gradient text-dark">
                <h5 class="modal-title fw-bold" id="timeEntryModalLabel">
                    <i class="bi bi-pencil me-2"></i><span id="modalTitleText">工数記録の編集</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="timeEntryForm" method="POST" action="{{ route('time-entries.store', $task->project) }}">
                @csrf
                <!-- _methodフィールドは編集時にJavaScriptで追加される -->
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="modal_task_id" class="form-label fw-bold">タスク（任意）</label>
                        <select class="form-select" name="task_id" id="modal_task_id">
                            <option value="">選択なし</option>
                            @foreach($task->project->tasks as $projectTask)
                                <option value="{{ $projectTask->id }}" {{ $projectTask->id == $task->id ? 'selected' : '' }}>{{ $projectTask->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="modal_date" class="form-label fw-bold">
                            日付 <span class="text-danger">*</span>
                        </label>
                        <input type="date" class="form-control" name="date" id="modal_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="modal_hours" class="form-label fw-bold">
                            時間 <span class="text-danger">*</span> <span class="text-muted small fw-normal">（0.25単位）</span>
                        </label>
                        <input type="number" class="form-control" name="hours" id="modal_hours" step="0.25" min="0.25" max="24" required placeholder="例：1、1.25、2.5">
                    </div>
                    <div class="mb-3">
                        <label for="modal_description" class="form-label fw-bold">説明</label>
                        <input type="text" class="form-control" name="description" id="modal_description">
                    </div>
                    <div id="editPermissionMessage" class="alert alert-warning d-none" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>他のユーザーの工数記録は編集・削除できません。
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
                    <button type="button" class="btn btn-danger" id="deleteTimeEntryBtn" style="display: none;">
                        <i class="bi bi-trash me-2"></i>削除
                    </button>
                    <button type="submit" class="btn btn-warning" id="submitTimeEntryBtn">
                        <i class="bi bi-check-circle me-2"></i><span id="submitButtonText">更新</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 削除確認モーダル -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger bg-gradient text-white">
                <h5 class="modal-title fw-bold" id="deleteConfirmModalLabel">
                    <i class="bi bi-exclamation-triangle me-2"></i>削除の確認
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>この工数記録を削除してもよろしいですか？この操作は取り消せません。</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
                <form id="deleteTimeEntryForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-2"></i>削除
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/tasks/show.js') }}"></script>
@endpush
@endsection
