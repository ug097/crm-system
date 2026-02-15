<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\TimeEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $stats = [
            'total_projects' => Project::whereHas('users', function ($query) use ($user) {
                $query->where('users.id', $user->id);
            })->count(),
            'active_projects' => Project::whereHas('users', function ($query) use ($user) {
                $query->where('users.id', $user->id);
            })->where('status', 'in_progress')->count(),
            'my_tasks' => Task::where('assigned_to', $user->id)
                ->whereIn('status', ['todo', 'in_progress'])
                ->count(),
            'total_hours_this_month' => TimeEntry::where('user_id', $user->id)
                ->whereMonth('date', now()->month)
                ->whereYear('date', now()->year)
                ->sum('hours'),
        ];

        $recentProjects = Project::whereHas('users', function ($query) use ($user) {
            $query->where('users.id', $user->id);
        })
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        $recentTasks = Task::where('assigned_to', $user->id)
            ->whereIn('status', ['todo', 'in_progress'])
            ->orderBy('due_date', 'asc')
            ->limit(5)
            ->with('project')
            ->get();

        $monthlyHours = TimeEntry::where('user_id', $user->id)
            ->select(DB::raw('DATE_FORMAT(date, "%Y-%m") as month'), DB::raw('SUM(hours) as total_hours'))
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(6)
            ->get();

        return view('dashboard', compact('stats', 'recentProjects', 'recentTasks', 'monthlyHours'));
    }
}

