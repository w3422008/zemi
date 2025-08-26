@extends('layouts.app')

@section('content')
<div class="login-page-bg">
    <div class="login-container" style="height: auto;">
        <h1>新規登録</h1>
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <label for="student_id">学生ID</label>
                <input id="student_id" type="text" name="student_id" value="{{ old('student_id') }}" required autocomplete="student_id" autofocus>
                @error('student_id')
                    <p style="color: red; font-size: 12px;">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-row" style="display: flex; gap: 10px;">
                <div class="form-group" style="flex: 1;">
                    <label for="last_name">姓</label>
                    <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" required autocomplete="family-name">
                    @error('last_name')
                        <p style="color: red; font-size: 12px;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group" style="flex: 1;">
                    <label for="first_name">名</label>
                    <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}" required autocomplete="given-name">
                    @error('first_name')
                        <p style="color: red; font-size: 12px;">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="nickname">ニックネーム（任意）</label>
                <input id="nickname" type="text" name="nickname" value="{{ old('nickname') }}" autocomplete="nickname">
                @error('nickname')
                    <p style="color: red; font-size: 12px;">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">メールアドレス（任意）</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="email">
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
