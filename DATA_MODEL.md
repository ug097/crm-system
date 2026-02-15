# CRMシステム データモデル

## 論理削除（ソフトデリート）

以下のテーブルは削除時に物理削除を行わず、`deleted_at` カラムに日時を記録する**論理削除**とする。

- **users**（ユーザー）
- **projects**（プロジェクト）
- **project_user**（プロジェクト-ユーザー中間テーブル）
- **tasks**（タスク）
- **time_entries**（工数記録）

`deleted_at` が NULL でないレコードは通常のクエリから除外される。論理削除されたユーザーはログイン不可。

## エンティティ一覧

### 1. users（ユーザー）
- **主キー**: `id`
- **削除**: 論理削除
- **属性**:
  - `id`: BIGINT (AUTO_INCREMENT)
  - `name`: VARCHAR (ユーザー名)
  - `email`: VARCHAR (UNIQUE, メールアドレス)
  - `role`: VARCHAR (デフォルト: 'member', 役割: admin/manager/member)
  - `password`: VARCHAR (ハッシュ化されたパスワード)
  - `email_verified_at`: TIMESTAMP (NULL可, メール認証日時)
  - `remember_token`: VARCHAR (NULL可, ログイン保持トークン)
  - `created_at`: TIMESTAMP
  - `updated_at`: TIMESTAMP
  - `deleted_at`: TIMESTAMP (NULL可, 論理削除日時)

### 2. projects（プロジェクト）
- **主キー**: `id`
- **外部キー**: `created_by` → `users.id`
- **削除**: 論理削除
- **属性**:
  - `id`: BIGINT (AUTO_INCREMENT)
  - `name`: VARCHAR (プロジェクト名)
  - `description`: TEXT (NULL可, 説明)
  - `start_date`: DATE (NULL可, 開始日)
  - `end_date`: DATE (NULL可, 終了日)
  - `status`: ENUM (デフォルト: 'planning', ステータス: planning/in_progress/on_hold/completed/cancelled)
  - `created_by`: BIGINT (作成者ID, CASCADE削除)
  - `created_at`: TIMESTAMP
  - `updated_at`: TIMESTAMP
  - `deleted_at`: TIMESTAMP (NULL可, 論理削除日時)

### 3. project_user（プロジェクト-ユーザー中間テーブル）
- **主キー**: `id`
- **複合ユニーク**: `(project_id, user_id)`
- **外部キー**: 
  - `project_id` → `projects.id` (CASCADE削除)
  - `user_id` → `users.id` (CASCADE削除)
- **削除**: 論理削除（メンバー除外時は pivot レコードに deleted_at を設定）
- **属性**:
  - `id`: BIGINT (AUTO_INCREMENT)
  - `project_id`: BIGINT (プロジェクトID)
  - `user_id`: BIGINT (ユーザーID)
  - `role`: ENUM (デフォルト: 'member', プロジェクト内の役割: manager/member)
  - `created_at`: TIMESTAMP
  - `updated_at`: TIMESTAMP
  - `deleted_at`: TIMESTAMP (NULL可, 論理削除日時)

### 4. tasks（タスク）
- **主キー**: `id`
- **外部キー**: 
  - `project_id` → `projects.id` (CASCADE削除)
  - `assigned_to` → `users.id` (NULL可, SET NULL削除, 担当者)
  - `created_by` → `users.id` (CASCADE削除, 作成者)
- **削除**: 論理削除
- **属性**:
  - `id`: BIGINT (AUTO_INCREMENT)
  - `project_id`: BIGINT (プロジェクトID)
  - `title`: VARCHAR (タスクタイトル)
  - `description`: TEXT (NULL可, 説明)
  - `status`: VARCHAR(255) (デフォルト: 'todo', ステータス: todo / in_progress / review / done → 表示: 未着手/進行中/レビュー中/完了)
  - `priority`: VARCHAR(255) (デフォルト: 'medium', 優先度: low / medium / high / urgent → 表示: 低/中/高/緊急)
  - `due_date`: DATE (NULL可, 期限日)
  - `assigned_to`: BIGINT (NULL可, 担当者ID)
  - `created_by`: BIGINT (作成者ID)
  - `estimated_hours`: INTEGER (NULL可, 見積工数)
  - `created_at`: TIMESTAMP
  - `updated_at`: TIMESTAMP
  - `deleted_at`: TIMESTAMP (NULL可, 論理削除日時)

### 5. time_entries（工数記録）
- **主キー**: `id`
- **外部キー**: 
  - `project_id` → `projects.id` (CASCADE削除)
  - `task_id` → `tasks.id` (NULL可, SET NULL削除)
  - `user_id` → `users.id` (CASCADE削除)
- **削除**: 論理削除
- **属性**:
  - `id`: BIGINT (AUTO_INCREMENT)
  - `project_id`: BIGINT (プロジェクトID)
  - `task_id`: BIGINT (NULL可, タスクID)
  - `user_id`: BIGINT (ユーザーID)
  - `date`: DATE (作業日)
  - `hours`: DECIMAL(5,2) (作業時間)
  - `description`: TEXT (NULL可, 作業内容)
  - `created_at`: TIMESTAMP
  - `updated_at`: TIMESTAMP
  - `deleted_at`: TIMESTAMP (NULL可, 論理削除日時)

## リレーションシップ

### User（ユーザー）
- **1対多**: `createdProjects()` - 作成したプロジェクト
- **多対多**: `projects()` - 参加しているプロジェクト（中間テーブル: project_user）
- **1対多**: `assignedTasks()` - 担当しているタスク
- **1対多**: `createdTasks()` - 作成したタスク
- **1対多**: `timeEntries()` - 工数記録

### Project（プロジェクト）
- **多対1**: `creator()` - 作成者（User）
- **多対多**: `users()` - 参加ユーザー（中間テーブル: project_user）
- **1対多**: `tasks()` - タスク
- **1対多**: `timeEntries()` - 工数記録
- **多対多**: `managers()` - マネージャー（project_user.role = 'manager'）
- **多対多**: `members()` - メンバー（project_user.role = 'member'）

### Task（タスク）
- **多対1**: `project()` - 所属プロジェクト
- **多対1**: `assignee()` - 担当者（User）
- **多対1**: `creator()` - 作成者（User）
- **1対多**: `timeEntries()` - 工数記録
- **アクセサ**: `total_hours` - 合計工数（timeEntriesの合計）

### TimeEntry（工数記録）
- **多対1**: `project()` - プロジェクト
- **多対1**: `task()` - タスク（NULL可）
- **多対1**: `user()` - ユーザー

## ビジネスルール

1. **ユーザー役割**:
   - `admin`: 管理者（全権限）
   - `manager`: マネージャー（プロジェクト管理権限）
   - `member`: 一般メンバー

2. **プロジェクトステータス**:
   - `planning`: 計画中
   - `in_progress`: 進行中
   - `on_hold`: 保留
   - `completed`: 完了
   - `cancelled`: キャンセル

3. **タスクステータス**（DBに格納する値 → 表示ラベル）:
   - `todo` → 未着手
   - `in_progress` → 進行中
   - `review` → レビュー中
   - `done` → 完了

4. **タスク優先度**（DBに格納する値 → 表示ラベル）:
   - `low` → 低
   - `medium` → 中
   - `high` → 高
   - `urgent` → 緊急

5. **プロジェクト-ユーザー役割**:
   - `manager`: プロジェクトマネージャー
   - `member`: プロジェクトメンバー

6. **論理削除**:
   - users, projects, project_user, tasks, time_entries の削除は論理削除とする
   - `deleted_at` が NULL のレコードのみ有効として扱う
   - プロジェクトメンバー除外時は project_user の該当行に `deleted_at` を設定（sync は行わず、pivot の論理削除で対応）

