<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Project;
use App\Models\Task;

class TaskController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $query = Task::with(['project', 'assignee', 'creator']);
        
        // admin権限を持つユーザーのみ全ユーザーのタスクを表示
        // それ以外のユーザーは自分のタスクのみを表示
        if (!$user->isAdmin()) {
            $query->where('assigned_to', $user->id);
        }
        
        $tasks = $query
            ->orderBy('due_date', 'asc')
            ->orderBy('priority', 'desc')
            ->paginate(20);

        return view('tasks.index', compact('tasks'));
    }

    public function create(Project $project)
    {
        $users = $project->users;
        return view('tasks.create', compact('project', 'users'));
    }

    public function store(StoreTaskRequest $request, Project $project)
    {
        $validated = $request->validated();

        $task = Task::create([
            'project_id' => $project->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
            'priority' => $validated['priority'],
            'due_date' => $validated['due_date'] ?? null,
            'assigned_to' => $validated['assigned_to'] ?? null,
            'created_by' => auth()->id(),
            'estimated_hours' => $validated['estimated_hours'] ?? null,
        ]);

        return redirect()->route('projects.show', $project)->with('success', 'タスクを作成しました。');
    }

    public function show(Task $task)
    {
        $task->load(['project', 'assignee', 'creator', 'timeEntries.user']);
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $users = $task->project->users;
        return view('tasks.edit', compact('task', 'users'));
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $validated = $request->validated();

        $task->update($validated);

        return redirect()->route('tasks.show', $task)->with('success', 'タスクを更新しました。');
    }

    public function destroy(Task $task)
    {
        $project = $task->project;
        $task->delete();
        return redirect()->route('projects.show', $project)->with('success', 'タスクを削除しました。');
    }
}

