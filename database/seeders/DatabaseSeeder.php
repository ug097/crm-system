<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\TimeEntry;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 管理者ユーザー
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => '管理者',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // テスト用ユーザー
        $testUsers = [
            [
                'name' => 'マネージャー太郎',
                'email' => 'manager@example.com',
                'password' => Hash::make('password'),
                'role' => 'manager',
            ],
            [
                'name' => 'テストユーザー1',
                'email' => 'test1@example.com',
                'password' => Hash::make('password'),
                'role' => 'member',
            ],
            [
                'name' => 'テストユーザー2',
                'email' => 'test2@example.com',
                'password' => Hash::make('password'),
                'role' => 'member',
            ],
            [
                'name' => 'テストユーザー3',
                'email' => 'test3@example.com',
                'password' => Hash::make('password'),
                'role' => 'member',
            ],
        ];

        foreach ($testUsers as $testUser) {
            User::firstOrCreate(
                ['email' => $testUser['email']],
                $testUser
            );
        }

        // 一般ユーザー
        $users = User::factory()->count(5)->create([
            'role' => 'member',
        ]);

        // プロジェクト作成
        $projects = Project::factory()->count(3)->create([
            'created_by' => $admin->id,
        ]);

        // プロジェクトにメンバーを追加
        foreach ($projects as $project) {
            $project->users()->attach($admin->id, ['role' => 'manager']);
            foreach ($users->random(rand(2, 4)) as $user) {
                $project->users()->attach($user->id, ['role' => 'member']);
            }

            // タスク作成
            $tasks = Task::factory()->count(rand(5, 10))->create([
                'project_id' => $project->id,
                'created_by' => $admin->id,
                'assigned_to' => $users->random()->id,
            ]);

            // 工数記録作成
            foreach ($tasks as $task) {
                TimeEntry::factory()->count(rand(2, 5))->create([
                    'project_id' => $project->id,
                    'task_id' => $task->id,
                    'user_id' => $task->assigned_to,
                ]);
            }
        }

        $this->command->info('シーダーが完了しました。');
        $this->command->info('管理者アカウント: admin@example.com / password');
    }
}
