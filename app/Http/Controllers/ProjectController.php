<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\User;

class ProjectController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $projects = Project::whereHas('users', function ($query) use ($user) {
            $query->where('users.id', $user->id);
        })
            ->with(['creator', 'users'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        $users = User::all();
        return view('projects.create', compact('users'));
    }

    public function store(StoreProjectRequest $request)
    {
        $validated = $request->validated();

        $project = Project::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'status' => $validated['status'],
            'created_by' => auth()->id(),
        ]);

        // プロジェクトマネージャーとメンバーを追加
        $syncData = [];
        $managerIds = $validated['managers'] ?? [];
        if (empty($managerIds)) {
            $managerIds = [auth()->id()];
        }
        foreach ($managerIds as $userId) {
            $syncData[$userId] = ['role' => 'manager'];
        }
        $memberIds = $validated['members'] ?? [];
        foreach ($memberIds as $userId) {
            if (!isset($syncData[$userId])) {
                $syncData[$userId] = ['role' => 'member'];
            }
        }
        $project->users()->sync($syncData);

        return redirect()->route('projects.show', $project)->with('success', 'プロジェクトを作成しました。');
    }

    public function show(Project $project)
    {
        $project->load(['creator', 'users', 'tasks.assignee', 'tasks.creator']);
        
        $tasks = $project->tasks()->with(['assignee', 'creator'])->orderBy('created_at', 'desc')->get();
        
        $timeEntries = $project->timeEntries()
            ->with(['user', 'task'])
            ->orderBy('date', 'desc')
            ->limit(20)
            ->get();

        $totalHours = $project->timeEntries()->sum('hours');

        return view('projects.show', compact('project', 'tasks', 'timeEntries', 'totalHours'));
    }

    public function edit(Project $project)
    {
        $users = User::all();
        $project->load('users');
        return view('projects.edit', compact('project', 'users'));
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $validated = $request->validated();

        $project->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'status' => $validated['status'],
        ]);

        // プロジェクトマネージャーとメンバーを更新（project_user は論理削除のため sync は使わない）
        $syncData = [];
        $managerIds = $validated['managers'] ?? [];
        foreach ($managerIds as $userId) {
            $syncData[$userId] = ['role' => 'manager'];
        }
        $memberIds = $validated['members'] ?? [];
        foreach ($memberIds as $userId) {
            if (!isset($syncData[$userId])) {
                $syncData[$userId] = ['role' => 'member'];
            }
        }
        $desiredIds = array_keys($syncData);
        $pivots = ProjectUser::where('project_id', $project->id)->withTrashed()->get();
        foreach ($pivots as $pivot) {
            if (in_array((int) $pivot->user_id, $desiredIds, true)) {
                $pivot->restore();
                $pivot->update(['role' => $syncData[(int) $pivot->user_id]['role']]);
            } else {
                $pivot->delete();
            }
        }
        foreach ($desiredIds as $userId) {
            if (!$pivots->contains('user_id', $userId)) {
                $project->users()->attach($userId, $syncData[$userId]);
            }
        }

        return redirect()->route('projects.show', $project)->with('success', 'プロジェクトを更新しました。');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'プロジェクトを削除しました。');
    }
}

