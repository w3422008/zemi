@extends('layouts.app')

@section('content')
<div class="login-page-bg">
    <div class="login-container">
        <h1>ログイン</h1>
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="id">学生ID</label>
                <input type="text" id="id" name="id" required value="{{ old('id') }}" autofocus>
            </div>
            <div class="form-group">
                <label for="password">パスワード</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="login-button">ログイン</button>
        </form>
        <a href="{{ route('register') }}" class="register-link">新規登録はこちら</a>
    </div>
</div>
@endsection
