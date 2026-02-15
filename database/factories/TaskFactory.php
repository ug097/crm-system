<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = \Faker\Factory::create('ja_JP');
        
        // 業務上自然なタスク名と説明のテンプレート
        $taskTemplates = [
            [
                'title' => '要件定義書の作成',
                'description' => '新機能の要件を整理し、要件定義書を作成する。ステークホルダーとのヒアリング結果を反映する。',
            ],
            [
                'title' => 'データベース設計',
                'description' => '新規テーブルの設計とER図の作成。既存テーブルとの整合性を確認する。',
            ],
            [
                'title' => 'APIエンドポイントの実装',
                'description' => 'RESTful APIのエンドポイントを実装する。リクエスト・レスポンスのバリデーションを含む。',
            ],
            [
                'title' => 'フロントエンド画面の実装',
                'description' => 'ユーザー一覧画面の実装。検索機能とページネーションを含む。',
            ],
            [
                'title' => '単体テストの作成',
                'description' => '新規機能の単体テストを作成する。カバレッジ80%以上を目標とする。',
            ],
            [
                'title' => 'コードレビュー',
                'description' => 'プルリクエストのコードレビューを実施する。コーディング規約の遵守を確認する。',
            ],
            [
                'title' => 'バグ修正',
                'description' => 'ログイン機能の不具合を修正する。パスワードリセット処理のエラーハンドリングを改善する。',
            ],
            [
                'title' => 'パフォーマンス最適化',
                'description' => 'データベースクエリの最適化を行う。N+1問題の解消とインデックスの追加を実施する。',
            ],
            [
                'title' => 'セキュリティ対策の実装',
                'description' => 'SQLインジェクション対策とXSS対策を実装する。セキュリティチェックリストに基づいて確認する。',
            ],
            [
                'title' => 'ドキュメント整備',
                'description' => 'API仕様書の更新とユーザーマニュアルの作成。最新の機能変更を反映する。',
            ],
            [
                'title' => 'デプロイ作業',
                'description' => 'ステージング環境へのデプロイを実施する。本番環境へのデプロイ準備を行う。',
            ],
            [
                'title' => '顧客ヒアリング',
                'description' => '新機能に関する顧客の要望をヒアリングする。フィードバックをまとめて報告書を作成する。',
            ],
            [
                'title' => 'UI/UXデザインの作成',
                'description' => '新機能の画面デザインを作成する。ワイヤーフレームから詳細デザインまで作成する。',
            ],
            [
                'title' => 'インテグレーションテスト',
                'description' => 'システム全体の結合テストを実施する。テストケースに基づいて実行し、結果を記録する。',
            ],
            [
                'title' => 'ログ分析と改善',
                'description' => 'アプリケーションログを分析し、エラーの原因を特定する。改善策を提案する。',
            ],
            [
                'title' => 'バックアップ設定',
                'description' => 'データベースの自動バックアップ設定を実施する。復旧手順のドキュメントも作成する。',
            ],
            [
                'title' => 'モニタリング設定',
                'description' => 'アプリケーションの監視設定を行う。アラートの閾値設定と通知先の設定を含む。',
            ],
            [
                'title' => 'チームミーティングの準備',
                'description' => '週次ミーティングの議題を整理し、資料を準備する。進捗状況をまとめる。',
            ],
            [
                'title' => '外部API連携の実装',
                'description' => '決済APIとの連携を実装する。エラーハンドリングとリトライ処理を含む。',
            ],
            [
                'title' => 'レポート機能の追加',
                'description' => '売上レポート機能を追加する。グラフ表示とCSVエクスポート機能を含む。',
            ],
        ];
        
        $selectedTask = $faker->randomElement($taskTemplates);
        
        return [
            'project_id' => Project::factory(),
            'title' => $selectedTask['title'],
            'description' => $faker->optional(0.8)->passthrough($selectedTask['description']),
            'status' => $faker->randomElement(['todo', 'in_progress', 'review', 'done']),
            'priority' => $faker->randomElement(['low', 'medium', 'high', 'urgent']),
            'due_date' => $faker->optional()->dateTimeBetween('now', '+1 month'),
            'assigned_to' => User::factory(),
            'created_by' => User::factory(),
            'estimated_hours' => $faker->optional()->numberBetween(1, 40),
        ];
    }
}

