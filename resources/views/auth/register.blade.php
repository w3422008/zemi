@extends('layouts.app')

@section('content')
<div class="login-page-bg">
    <div class="login-container" style="height: auto;">
        <h1>新規登録</h1>
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <label for="id">学生ID</label>
                <input id="id" type="text" name="id" value="{{ old('id') }}" required autocomplete="id" autofocus>
                @error('id')
                    <p style="color: red; font-size: 12px;">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="display_name">表示名</label>
                <input id="display_name" type="text" name="display_name" value="{{ old('display_name') }}" required autocomplete="name">
                @error('display_name')
                    <p style="color: red; font-size: 12px;">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">メールアドレス</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email">
                @error('email')
                    <p style="color: red; font-size: 12px;">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">パスワード</label>
                <input id="password" type="password" name="password" required autocomplete="new-password">
                @error('password')
                    <p style="color: red; font-size: 12px;">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="password-confirm">パスワード確認</label>
                <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password">
            </div>

            <button type="submit" class="login-button">登録</button>
        </form>
        <a href="{{ route('login') }}" class="register-link">ログイン画面へ戻る</a>
    </div>
</div>
@endsection
