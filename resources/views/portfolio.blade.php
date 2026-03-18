<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>UG - Laravel / Java バックエンドエンジニア | ポートフォリオ</title>
    <meta name="description" content="業務システム開発を得意とするバックエンドエンジニアYujiのポートフォリオ。CRM業務管理システムのデモをご覧いただけます。">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Noto Sans JP', sans-serif; }
    </style>
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css'])
    @else
        {{-- ビルド未実行 or devサーバー停止時は Tailwind CDN で表示 --}}
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
</head>
<body class="antialiased bg-slate-50 text-slate-800 min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Hero Section --}}
        <section class="py-8 text-center">
            <h1 class="text-4xl md:text-5xl font-bold tracking-tight text-slate-900 mb-4">Yuji O</h1>
            <p class="text-lg md:text-xl text-slate-600 font-medium mb-2">Laravelを用いた業務システム開発エンジニア</p>
            <p class="text-slate-600 mb-8 max-w-xl mx-auto">顧客管理・案件管理などの業務管理システムの開発を得意としています。</p>
            <ul class="text-left max-w-sm mx-auto space-y-2 text-slate-600 mb-10">
            <p class="text-slate-600 max-w-xl mx-auto">対応可能業務</p>
                <li class="flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shrink-0"></span>
                    業務管理システム
                </li>
                <li class="flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shrink-0"></span>
                    管理画面開発
                </li>
                <li class="flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shrink-0"></span>
                    既存システム改修
                </li>
            </ul>
            <p class="text-sm font-medium text-slate-500 mb-1">使用技術</p>
            <p class="text-slate-600 mb-6">PHP / Laravel / Java / MySQL / AWS</p>
            <p class="text-slate-600 mb-10">副業として<br>週10〜20時間の開発が可能です。</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-6">
                <a href="{{ url('/login') }}" class="inline-flex items-center justify-center px-4 py-2 text-base font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-lg hover:shadow-xl transition-all focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                ▶デモを見る<br>
                （ログイン可能）
                <a href="https://github.com/ug097/crm-system" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center px-4 py-2 text-base font-semibold text-slate-700 bg-white border-2 border-slate-300 hover:border-slate-400 rounded-xl shadow hover:shadow-md transition-all focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2">
                    GitHubを見る
                </a>
            </div>
            <div class="bg-slate-50 rounded-xl border border-slate-200 p-4 mb-6 mx-32">
                <div class="space-y-3 text-slate-600 text-sm">
                    <div>
                        <p class="font-medium text-slate-700 mb-1">ログイン情報</p>
                        <p class="pl-3">メールアドレス：admin@example.com</p>
                        <p class="pl-3">パスワード：password</p>
                        <p class="font-medium text-slate-700 mb-1">※登録・編集・削除など自由に操作可能です</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Works Section --}}
        <section class="py-8 border-t border-slate-200">
            <h2 class="text-2xl md:text-3xl font-bold text-slate-900 text-center mb-12">制作物</h2>
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                <div class="p-4 md:p-6">
                    <img src="{{ asset('images/dashboard-preview.png') }}" alt="CRM業務管理システム ダッシュボード" class="w-full h-auto object-cover object-top border border-slate-200">
                </div>
                <div class="p-8 md:p-10 flex justify-center">
                    <div class="w-full max-w-lg text-left">
                        <h3 class="text-xl font-bold text-slate-900 mb-3">CRM業務管理システム</h3>
                        <p class="text-slate-600 mb-4">顧客・案件・タスクを管理する業務管理システムです。</p>
                        <p class="text-slate-600 text-sm leading-relaxed mb-10">
                            営業活動などで発生する<br>
                            ・顧客情報の分散<br>
                            ・案件進捗の把握<br>
                            ・タスク管理<br>
                            といった課題を解決することを想定して開発しました。
                        </p>
                        <ul class="text-left space-y-2 text-slate-600 mb-10">
                            <p class="text-slate-600 mb-2 font-bold">技術的ポイント</p>
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shrink-0"></span>
                                認証: 権限ごとに画面・操作を制御
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shrink-0"></span>
                                CRUD操作: プロジェクト、タスク、工数の完全なCRUD
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shrink-0"></span>
                                リレーション: Eloquent ORMを用いてN+1問題を回避
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shrink-0"></span>
                                バリデーション: フォームバリデーション
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shrink-0"></span>
                                UI/UX: Tailwind CSSを使用したモダンなUI
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shrink-0"></span>
                                データベース設計: 適切な正規化とリレーション設計
                            </li>
                        </ul>
                        <div class="bg-slate-50 rounded-xl border border-slate-200 p-4 mb-6">
                            <p class="text-sm font-medium text-slate-700 mb-3">デモアカウント</p>
                            <div class="space-y-3 text-slate-600 text-sm">
                                <div>
                                    <p class="font-medium text-slate-700 mb-1">管理者権限ユーザー</p>
                                    <p class="pl-3">メールアドレス：admin@example.com</p>
                                    <p class="pl-3">パスワード：password</p>
                                </div>
                                <div>
                                    <p class="font-medium text-slate-700 mb-1">一般権限ユーザー</p>
                                    <p class="pl-3">メールアドレス：y-kanou@example.com</p>
                                    <p class="pl-3">パスワード：password</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 flex flex-col sm:flex-row items-center justify-center gap-4">
                            <a href="{{ url('/login') }}" class="inline-flex items-center justify-center px-8 py-4 text-base font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-lg hover:shadow-xl transition-all focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                                デモを見る
                            </a>
                            <a href="https://github.com/ug097/crm-system" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center px-8 py-4 text-base font-semibold text-slate-700 bg-white border-2 border-slate-300 hover:border-slate-400 rounded-xl shadow hover:shadow-md transition-all focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2">
                                GitHubを見る
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Skills Section --}}
        <section class="py-8 border-t border-slate-200">
            <h2 class="text-2xl md:text-3xl font-bold text-slate-900 text-center mb-12">スキル</h2>
            <div class="grid sm:grid-cols-3 gap-8">
                <div class="bg-white rounded-xl shadow border border-slate-200 p-6">
                    <h3 class="text-sm font-semibold text-emerald-600 uppercase tracking-wider mb-4">Backend</h3>
                    <ul class="space-y-2 text-slate-600">
                        <li>Laravel</li>
                        <li>PHP</li>
                        <li>Java</li>
                        <li>Spring Boot</li>
                        <li>Play Framework</li>
                    </ul>
                </div>
                <div class="bg-white rounded-xl shadow border border-slate-200 p-6">
                    <h3 class="text-sm font-semibold text-emerald-600 uppercase tracking-wider mb-4">Database</h3>
                    <ul class="space-y-2 text-slate-600">
                        <li>MySQL</li>
                        <li>Oracle</li>
                    </ul>
                </div>
                <div class="bg-white rounded-xl shadow border border-slate-200 p-6">
                    <h3 class="text-sm font-semibold text-emerald-600 uppercase tracking-wider mb-4">Infrastructure</h3>
                    <ul class="space-y-2 text-slate-600">
                        <li>AWS EC2</li>
                        <li>Nginx</li>
                    </ul>
                </div>
            </div>
        </section>

        {{-- Experience Section --}}
        <section class="py-8 border-t border-slate-200 pb-24">
            <h2 class="text-2xl md:text-3xl font-bold text-slate-900 text-center mb-12">経歴</h2>
            <div class="space-y-8 max-w-xl mx-auto">
                <div class="bg-white rounded-xl shadow border border-slate-200 p-6 md:p-8 flex justify-center">
                    <div class="w-full max-w-sm text-left">
                        <p class="text-sm font-semibold text-emerald-600 mb-2">2019〜2021</p>
                        <p class="font-semibold text-slate-900 mb-1">自社開発企業</p><br>
                        <p class="text-slate-600 text-sm mb-2">Java / Spring Boot</p>
                        <p class="text-slate-600">勤怠管理システム開発</p><br>
                        <p class="text-slate-600 text-sm mb-2">PHP / Laravel</p>
                        <p class="text-slate-600">求人サイト新規開発</p>
                        <p class="text-slate-600">顧客管理システムAPI開発保守</p>
                        <p class="text-slate-600">業務管理システム開発保守</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow border border-slate-200 p-6 md:p-8 flex justify-center">
                    <div class="w-full max-w-sm text-left">
                        <p class="text-sm font-semibold text-emerald-600 mb-2">2022〜現在</p>
                        <p class="font-semibold text-slate-900 mb-1">社内SE</p><br>
                        <p class="text-slate-600 text-sm mb-2">Java / Play Framework</p>
                        <p class="text-slate-600">基幹システム運用保守</p>
                    </div>
                </div>
            </div>
        </section>

    </div>
</body>
</html>
