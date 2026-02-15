<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTimeEntryRequest;
use App\Http\Requests\UpdateTimeEntryRequest;
use App\Models\Project;
use App\Models\TimeEntry;

class TimeEntryController extends Controller
{
    public function store(StoreTimeEntryRequest $request, Project $project)
    {
        $validated = $request->validated();

        TimeEntry::create([
            'project_id' => $project->id,
            'task_id' => $validated['task_id'] ?? null,
            'user_id' => auth()->id(),
            'date' => $validated['date'],
            'hours' => $validated['hours'],
            'description' => $validated['description'] ?? null,
        ]);

        return back()->with('success', '工数を記録しました。');
    }

    public function update(UpdateTimeEntryRequest $request, TimeEntry $timeEntry)
    {
        // 自分の工数記録のみ更新可能
        if ($timeEntry->user_id !== auth()->id()) {
            return back()->withErrors(['error' => '他のユーザーの工数記録は更新できません。']);
        }

        $validated = $request->validated();

        $timeEntry->update($validated);

        return back()->with('success', '工数を更新しました。');
    }

    public function destroy(TimeEntry $timeEntry)
    {
        // 自分の工数記録のみ削除可能
        if ($timeEntry->user_id !== auth()->id()) {
            abort(403, '他のユーザーの工数記録は削除できません。');
        }

        $timeEntry->delete();

        return back()->with('success', '工数を削除しました。');
    }
}

