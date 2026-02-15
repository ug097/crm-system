<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>新規登録 - {{ config('app.name', 'Laravel') }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Noto Sans JP', sans-serif;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .register-card {
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        .register-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card register-card border-0">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <div class="register-icon rounded-circle d-inline-flex align-items-center justify-content-center mb-3">
                                <i class="bi bi-person-plus-fill text-white fs-1"></i>
                            </div>
                            <h2 class="fw-bold text-primary mb-2">新規登録</h2>
                            <p class="text-muted">新しいアカウントを作成</p>
                        </div>

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('register') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label fw-bold">名前</label>
                                <input type="text" class="form-control form-control-lg" id="name" name="name" required value="{{ old('name') }}" placeholder="山田 太郎">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold">メールアドレス</label>
                                <input type="email" class="form-control form-control-lg" id="email" name="email" required value="{{ old('email') }}" placeholder="your@email.com">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label fw-bold">パスワード</label>
                                <input type="password" class="form-control form-control-lg" id="password" name="password" required placeholder="••••••••">
                            </div>
                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label fw-bold">パスワード（確認）</label>
                                <input type="password" class="form-control form-control-lg" id="password_confirmation" name="password_confirmation" required placeholder="••••••••">
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">
                                <i class="bi bi-person-check me-2"></i>登録
                            </button>
                            <div class="text-center pt-3 border-top">
                                <p class="text-muted mb-0">
                                    既にアカウントをお持ちですか？
                                    <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">ログインはこちら</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
