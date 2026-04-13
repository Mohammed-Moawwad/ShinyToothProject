<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login – ShinyTooth</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f4f8;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,.1);
            padding: 40px 36px;
            width: 100%;
            max-width: 400px;
        }
        .logo {
            text-align: center;
            margin-bottom: 28px;
        }
        .logo h1 { color: #1a6bc4; font-size: 1.7rem; }
        .logo p { color: #6b7280; font-size: .85rem; margin-top: 4px; }
        label { display: block; font-size: .875rem; color: #374151; margin-bottom: 5px; font-weight: 500; }
        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: .95rem;
            margin-bottom: 18px;
            transition: border-color .2s;
        }
        input:focus { outline: none; border-color: #1a6bc4; }
        .error-box {
            background: #fef2f2;
            border: 1px solid #fca5a5;
            color: #dc2626;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: .875rem;
            margin-bottom: 18px;
        }
        button[type="submit"] {
            width: 100%;
            padding: 11px;
            background: #1a6bc4;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background .2s;
        }
        button[type="submit"]:hover { background: #155faa; }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">
            <h1>ShinyTooth</h1>
            <p>Administrator Login</p>
        </div>

        @if ($errors->any())
            <div class="error-box">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('admin.login') }}">
            @csrf
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Sign In</button>
        </form>
    </div>
</body>
</html>
