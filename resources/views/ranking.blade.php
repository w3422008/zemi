@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/ranking.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/ranking.js') }}"></script>
@endpush

@section('content')
<div class="ranking-card">
    <!-- ヘッダー -->
    <div class="ranking-header">
        <h1 class="ranking-title">🏆 ランキング</h1>
        <p class="ranking-subtitle">みんなで競い合って学習を楽しもう！</p>
    </div>

    <!-- タブメニュー -->
    <div class="tab-menu">
        <button class="active" data-tab="total" data-label="総クエスト">
            <span class="tab-icon">📚</span>
            <span class="tab-label">総クエスト</span>
        </button>
        <button data-tab="medical" data-label="医学・医療系">
            <span class="tab-icon">🏥</span>
            <span class="tab-label">医学・医療系</span>
        </button>
        <button data-tab="it" data-label="情報処理技術系">
            <span class="tab-icon">💻</span>
            <span class="tab-label">情報処理技術系</span>
        </button>
        <button data-tab="medical-it" data-label="医療情報システム系">
            <span class="tab-icon">🔬</span>
            <span class="tab-label">医療情報システム系</span>
        </button>
        <button data-tab="today" data-label="今日の成績">
            <span class="tab-icon">⭐</span>
            <span class="tab-label">今日の成績</span>
        </button>
    </div>

    <!-- ランキングタイトル（スマホ用） -->
    <div id="ranking-title-mobile" class="ranking-title-mobile"></div>

    <!-- コンテンツエリア -->
    <div class="ranking-content">
        <!-- 各ランキングのタブコンテンツ -->
        <div id="tab-total" class="tab-content active">
            <div class="ranking-list" id="total-quests-ranking">
                <!-- トップ3表示エリア -->
                <div class="podium" id="total-podium" style="display:none;"></div>
                <!-- 4位以下のリスト -->
                <div class="ranking-list-extended" id="total-extended"></div>
            </div>
            <div class="no-data" id="no-total-quests" style="display:none;">
                <p>📊 まだランキングデータがありません</p>
                <p>学習を始めてランキングに参加しよう！</p>
            </div>
        </div>

        <div id="tab-medical" class="tab-content">
            <div class="ranking-list" id="medical-quests-ranking">
                <div class="podium" id="medical-podium" style="display:none;"></div>
                <div class="ranking-list-extended" id="medical-extended"></div>
            </div>
            <div class="no-data" id="no-medical-quests" style="display:none;">
                <p>🏥 医学・医療系のランキングデータがありません</p>
                <p>医療問題に挑戦してみよう！</p>
            </div>
        </div>

        <div id="tab-it" class="tab-content">
            <div class="ranking-list" id="it-quests-ranking">
                <div class="podium" id="it-podium" style="display:none;"></div>
                <div class="ranking-list-extended" id="it-extended"></div>
            </div>
            <div class="no-data" id="no-it-quests" style="display:none;">
                <p>💻 情報処理技術系のランキングデータがありません</p>
                <p>IT問題に挑戦してみよう！</p>
            </div>
        </div>

        <div id="tab-medical-it" class="tab-content">
            <div class="ranking-list" id="medical-it-quests-ranking">
                <div class="podium" id="medical-it-podium" style="display:none;"></div>
                <div class="ranking-list-extended" id="medical-it-extended"></div>
            </div>
            <div class="no-data" id="no-medical-it-quests" style="display:none;">
                <p>🔬 医療情報システム系のランキングデータがありません</p>
                <p>医療IT問題に挑戦してみよう！</p>
            </div>
        </div>

        <div id="tab-today" class="tab-content">
            <div class="ranking-list" id="today-performance-ranking">
                <div class="podium" id="today-podium" style="display:none;"></div>
                <div class="ranking-list-extended" id="today-extended"></div>
            </div>
            <div class="no-data" id="no-today-performance" style="display:none;">
                <p>⭐ 今日の成績データがありません</p>
                <p>今日も学習を頑張ろう！</p>
            </div>
        </div>
    </div>
</div>
@endsection
