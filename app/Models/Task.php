<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_id',
        'title',
        'description',
        'status',
        'priority',
        'due_date',
        'assigned_to',
        'created_by',
        'estimated_hours',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function timeEntries(): HasMany
    {
        return $this->hasMany(TimeEntry::class);
    }

    public function getTotalHoursAttribute(): float
    {
        return $this->timeEntries()->sum('hours');
    }

    /**
     * ステータスの日本語ラベル（表示用）
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'todo' => '未着手',
            'in_progress' => '進行中',
            'review' => 'レビュー中',
            'done' => '完了',
            default => $this->status ?? '未着手',
        };
    }

    /**
     * 優先度の日本語ラベル（表示用）
     */
    public function getPriorityLabelAttribute(): string
    {
        return match ($this->priority) {
            'low' => '低',
            'medium' => '中',
            'high' => '高',
            'urgent' => '緊急',
            default => $this->priority ?? '中',
        };
    }
}

