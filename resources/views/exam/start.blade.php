@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/exam.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/exam.js') }}"></script>
@endpush

@section('content')
<div class="exam_container">
    <div class="exam-container">
        <!-- 試験ヘッダー -->
        <div class="exam-header">
            <div class="exam-title">
                <h1>{{ $categoryInfo['name'] }} 模擬試験</h1>
                <p>{{ $categoryInfo['questionCount'] }}問 / {{ $categoryInfo['timeLimit'] }}分</p>
            </div>
            <div class="exam-timer">
                <div class="timer-display" id="timer">
                    <span id="minutes">{{ $categoryInfo['timeLimit'] }}</span>:<span id="seconds">00</span>
                </div>
                <div class="timer-label">残り時間</div>
            </div>
        </div>

        <!-- 試験フォーム -->
        <form id="examForm" method="POST" action="{{ route('exam.finish') }}">
            @csrf
            <input type="hidden" name="category" value="{{ $category }}">
            
            <!-- 問題情報を隠しフィールドで送信 -->
            @foreach($questions as $index => $questionData)
                <input type="hidden" name="questions[{{ $index }}][year]" value="{{ $questionData['question']->year }}">
                <input type="hidden" name="questions[{{ $index }}][number]" value="{{ $questionData['question']->number }}">
                <input type="hidden" name="questions[{{ $index }}][category]" value="{{ $questionData['question']->category }}">
            @endforeach

            <!-- 問題一覧 -->
            <div class="questions-container">
                @foreach($questions as $index => $questionData)
                    <div class="question-card" data-question="{{ $index + 1 }}">
                        <div class="question-header">
                            <h3 class="question-number">問題 {{ $index + 1 }}</h3>
                        </div>
                        
                        <div class="question-body">
                            <p class="question-text">{{ $questionData['question']->body }}</p>
                            @if($questionData['question']->image_path)
                                <div class="question-image">
                                    <img src="{{ asset('images/' . $questionData['question']->image_path) }}" alt="問題図" class="img-responsive">
                                </div>
                            @endif
                        </div>

                        <div class="options-container">
                            @if($questionData['options'])
                                @foreach($questionData['options']->sortBy('number') as $option)
                                    <label class="option-label">
                                        <input type="radio" 
                                               name="answers[{{ $index }}]" 
                                               value="{{ $option->number }}"
                                               class="option-input">
                                        <span class="option-text">{{ $option->number }}. {{ $option->body }}</span>
                                        <span class="option-check"></span>
                                    </label>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- 試験終了ボタン -->
            <div class="exam-footer">
                <button type="button" class="btn btn-danger btn-large" id="finishExamBtn">
                    回答を終了する
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 終了確認モーダル -->
<div class="modal fade" id="finishConfirmModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">試験終了確認</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <strong>注意:</strong> 一度試験を終了すると、回答を変更することはできません。
                </div>
                <p id="modalMessage">試験を終了してよろしいですか？</p>
                <div class="progress-info">
                    <span>回答済み: <strong id="answeredCount">0</strong> / {{ $categoryInfo['questionCount'] }}問</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">いいえ</button>
                <button type="button" class="btn btn-primary" id="confirmFinish">はい</button>
            </div>
        </div>
    </div>
</div>

<script>
// 試験データをJavaScriptに渡す
window.examData = {
    timeLimit: @json($categoryInfo['timeLimit'] ?? 60),
    totalQuestions: @json($categoryInfo['questionCount'] ?? 50)
};
</script>
@endsection