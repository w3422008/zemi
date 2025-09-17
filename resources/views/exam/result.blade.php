@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/exam.css') }}">
<style>
    .result-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        margin-bottom: 2rem;
        border-radius: 10px;
        text-align: center;
    }
    
    .score-display {
        font-size: 2.5rem;
        font-weight: bold;
        margin: 1rem 0;
    }
    
    .result-stats {
        display: flex;
        justify-content: space-around;
        margin-top: 1rem;
    }
    
    .stat-item {
        text-align: center;
    }
    
    .stat-value {
        font-size: 1.5rem;
        font-weight: bold;
    }
    
    .question-result {
        background: white;
        border: 1px solid #ddd;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        padding: 1.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .question-header {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
        font-weight: bold;
        font-size: 1.1rem;
    }
    
    .result-icon {
        font-size: 1.5rem;
        margin-right: 0.5rem;
        width: 30px;
    }
    
    .correct-icon {
        color: #28a745;
    }
    
    .incorrect-icon {
        color: #dc3545;
    }
    
    .unanswered-icon {
        color: #6c757d;
    }
    
    .option-item {
        margin: 0.5rem 0;
        padding: 0.75rem;
        border-radius: 5px;
        border: 1px solid #eee;
    }
    
    .option-chosen {
        font-weight: bold;
        background-color: #f8f9fa;
        border-color: #007bff;
    }
    
    .option-correct {
        color: #dc3545;
        background-color: #ffe6e6;
        border-color: #dc3545;
    }
    
    .option-correct::before {
        content: "○ ";
        color: #dc3545;
        font-weight: bold;
    }
    
    .option-unchosen {
        color: #6c757d;
    }
    
    .back-button {
        display: inline-block;
        margin-top: 2rem;
        padding: 0.75rem 1.5rem;
        background-color: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s;
    }
    
    .back-button:hover {
        background-color: #0056b3;
        color: white;
        text-decoration: none;
    }
</style>
@endpush

@section('content')
<div class="container">
    <!-- 結果ヘッダー -->
    <div class="result-header">
        <h1>{{ $categoryInfo['name'] }} 模擬試験結果</h1>
        <div class="score-display">{{ $score->score }}点 / {{ $categoryInfo['maxScore'] }}点</div>
        <div class="result-stats">
            <div class="stat-item">
                <div class="stat-value">{{ $score->score / 2 }}</div>
                <div>正答数</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $score->amount }}</div>
                <div>回答数</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $categoryInfo['questionCount'] }}</div>
                <div>出題数</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ round(($score->score / 2) / $categoryInfo['questionCount'] * 100, 1) }}%</div>
                <div>正答率</div>
            </div>
        </div>
    </div>

    <!-- 問題一覧 -->
    <div class="results-container">
        @foreach($detailedResults as $result)
            <div class="question-result">
                <div class="question-header">
                    @if($result['is_answered'])
                        @if($result['is_correct'])
                            <span class="result-icon correct-icon">○</span>
                        @else
                            <span class="result-icon incorrect-icon">×</span>
                        @endif
                    @else
                        <span class="result-icon unanswered-icon">-</span>
                    @endif
                    問題 {{ $result['number'] }}
                </div>
                
                <div class="question-body">
                    <p class="question-text">{{ $result['question']->body }}</p>
                    @if($result['question']->image_path)
                        <div class="question-image">
                            <img src="{{ asset('images/' . $result['question']->image_path) }}" alt="問題画像" class="img-fluid">
                        </div>
                    @endif
                    
                    <!-- 選択肢一覧 -->
                    <div class="options-list">
                        @foreach($result['options'] as $option)
                            @php
                                $isChosen = $result['chosen_option_number'] == $option->number;
                                $isCorrect = $result['correct_option_number'] == $option->number;
                                $classes = ['option-item'];
                                
                                if ($isChosen) {
                                    $classes[] = 'option-chosen';
                                }
                                if ($isCorrect) {
                                    $classes[] = 'option-correct';
                                }
                                if (!$isChosen && !$isCorrect) {
                                    $classes[] = 'option-unchosen';
                                }
                            @endphp
                            
                            <div class="{{ implode(' ', $classes) }}">
                                {{ $option->number }}. {{ $option->body }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- 戻るボタン -->
    <div class="text-center">
        <a href="{{ route('home') }}" class="back-button">ホームに戻る</a>
    </div>
</div>
@endsection