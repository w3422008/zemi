@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/exam.css') }}">
@endpush

@section('content')
<div class="container">
    <div class="exam-prepare-container">
        <h1 class="page-title">{{ $categoryInfo['name'] }} 模擬試験</h1>
        
        <div class="exam-info-card">
            <h2>試験情報</h2>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">問題数</span>
                    <span class="info-value">{{ $categoryInfo['questionCount'] }}問</span>
                </div>
                <div class="info-item">
                    <span class="info-label">制限時間</span>
                    <span class="info-value">{{ $categoryInfo['timeLimit'] }}分</span>
                </div>
                <div class="info-item">
                    <span class="info-label">満点</span>
                    <span class="info-value">{{ $categoryInfo['maxScore'] }}点</span>
                </div>
                <div class="info-item">
                    <span class="info-label">配点</span>
                    <span class="info-value">1問2点</span>
                </div>
            </div>
        </div>

        <div class="instructions-card">
            <h2>注意事項</h2>
            <ul class="instructions-list">
                <li>試験開始後は制限時間内に全ての問題に回答してください。</li>
                <li>すべての問題が一度に表示されます。順番は任意で構いません。</li>
                <li>各問題には複数の選択肢があります。正しいと思うものを一つ選択してください。</li>
                <li>途中で試験を終了することも可能ですが、未回答の問題は0点となります。</li>
                <li>時間が経過すると自動的に試験が終了し、結果画面に移動します。</li>
                <li>一度試験を開始すると、ブラウザを閉じても時間はカウントされ続けます。</li>
                <li>試験結果は保存され、後から確認することができます。</li>
            </ul>
        </div>

        <div class="action-buttons">
            <a href="{{ route('home') }}" class="btn btn-secondary">戻る</a>
            <a href="{{ route('exam.start', $category) }}" class="btn btn-primary btn-large">模擬試験を受ける</a>
        </div>
    </div>
</div>
@endsection