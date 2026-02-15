@extends('layouts.app')

@section('title', $project->name)

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-start flex-wrap">
            <div>
                <h1 class="display-5 fw-bold text-primary mb-2">
                    <i class="bi bi-folder-fill me-2"></i>{{ $project->name }}
                </h1>
                @if($project->description)
                    <p class="text-muted">{{ $project->description }}</p>
                @endif
            </div>
            <div class="d-flex gap-2 mt-2">
                <a href="{{ route('projects.edit', $project) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-pencil me-2"></i>編集
                </a>
                <a href="{{ route('tasks.create', $project) }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>タスク追加
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <!-- タスク一覧 -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-primary bg-gradient text-white">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="bi bi-list-check me-2"></i>タスク一覧
                </h5>
            </div>
            <div class="card-body">
                @forelse($tasks as $task)
                    <div class="card mb-3 border">
                        <div class="card-body position-relative">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="card-title mb-1">
                                        <a href="{{ route('tasks.show', $task) }}" class="text-decoration-none text-dark fw-bold stretched-link">
                                            {{ $task->title }}
                                        </a>
                                    </h6>
                                    @if($task->description)
                                        <p class="card-text text-muted small mb-2">
                                            {{ \Illuminate\Support\Str::limit($task->description, 100) }}
                                        </p>
                                    @endif
                                    <div class="d-flex gap-2 flex-wrap">
                                        <span class="badge 
                                            @if($task->status === 'done') bg-success
                                            @elseif($task->status === 'in_progress') bg-primary
                                            @elseif($task->status === 'review') bg-warning text-dark
                                            @else bg-secondary
                                            @endif">
                                            {{ $task->status_label }}
                                        </span>
                                        <span class="badge 
                                            @if($task->priority === 'urgent') bg-danger
                                            @elseif($task->priority === 'high') bg-warning text-dark
                                            @elseif($task->priority === 'medium') bg-info text-dark
                                            @else bg-secondary
                                            @endif">
                                            {{ $task->priority_label }}
                                        </span>
                                        @if($task->assignee)
                                            <small class="text-muted">
                                                <i class="bi bi-person me-1"></i>担当: {{ $task->assignee->name }}
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="bi bi-list-check fs-1 text-muted"></i>
                        <p class="text-muted mt-3">タスクがありません</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- 工数記録 -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-warning bg-gradient text-dark">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="bi bi-clock-history me-2"></i>工数記録
                </h5>
            </div>
            <div class="card-body">
                <!-- 新規登録ボタン -->
                <div class="mb-3">
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#timeEntryModal" onclick="openNewTimeEntryModal()">
                        <i class="bi bi-plus-circle me-2"></i>工数記録を追加
                    </button>
                </div>

                <!-- 工数記録一覧 -->
                @forelse($timeEntries as $entry)
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
                                    @if($entry->task)
                                        <small class="text-muted">タスク: {{ $entry->task->title }}</small>
                                    @endif
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

    <!-- サイドバー -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-secondary bg-gradient text-white">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="bi bi-info-circle me-2"></i>プロジェクト情報
                </h5>
            </div>
            <div class="card-body">
                <dl class="mb-0">
                    <dt class="small text-muted mb-1">ステータス</dt>
                    <dd class="mb-3">
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
                    </dd>
                    @if($project->start_date)
                        <dt class="small text-muted mb-1">開始日</dt>
                        <dd class="mb-3 fw-bold">{{ $project->start_date->format('Y年m月d日') }}</dd>
                    @endif
                    @if($project->end_date)
                        <dt class="small text-muted mb-1">終了日</dt>
                        <dd class="mb-3 fw-bold">{{ $project->end_date->format('Y年m月d日') }}</dd>
                    @endif
                    <dt class="small text-muted mb-1">作成者</dt>
                    <dd class="mb-3 fw-bold">{{ $project->creator->name }}</dd>
                    <dt class="small text-muted mb-1">総工数</dt>
                    <dd class="mb-0">
                        <h4 class="text-primary fw-bold mb-0">{{ number_format($totalHours, 1) }}時間</h4>
                    </dd>
                </dl>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-info bg-gradient text-white">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="bi bi-people me-2"></i>メンバー
                </h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    @foreach($project->users as $user)
                        <li class="d-flex align-items-center mb-3">
                            <div class="bg-primary bg-gradient rounded-circle p-2 me-3 d-flex align-items-center justify-content-center text-white fw-bold" style="width: 40px; height: 40px;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-bold">{{ $user->name }}</div>
                                <small class="text-muted">{{ $user->pivot->role === 'manager' ? 'マネージャー' : 'メンバー' }}</small>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- データ属性として値を埋め込む（JavaScriptで使用） -->
<div id="timeEntryStoreUrl" data-url="{{ route('time-entries.store', $project) }}" style="display: none;"></div>
<div id="currentUserId" data-user-id="{{ auth()->id() }}" data-today="{{ date('Y-m-d') }}" style="display: none;"></div>

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
            <form id="timeEntryForm" method="POST" action="{{ route('time-entries.store', $project) }}">
                @csrf
                <!-- _methodフィールドは編集時にJavaScriptで追加される -->
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="modal_task_id" class="form-label fw-bold">タスク（任意）</label>
                        <select class="form-select" name="task_id" id="modal_task_id">
                            <option value="">選択なし</option>
                            @foreach($project->tasks as $task)
                                <option value="{{ $task->id }}">{{ $task->title }}</option>
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
<script src="{{ asset('js/projects/show.js') }}"></script>
@endpush
@endsection
