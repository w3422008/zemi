<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Question;
use App\Models\Option;
use App\Models\Score;
use App\Models\Result;
use App\Models\ChosenOption;

class ExamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 模擬試験の準備画面を表示
     */
    public function prepare($category)
    {
        // カテゴリの検証
        if (!in_array($category, ['medical', 'processing', 'system'])) {
            abort(404);
        }

        // カテゴリ情報を設定
        $categoryInfo = $this->getCategoryInfo($category);
        
        return view('exam.prepare', compact('category', 'categoryInfo'));
    }

    /**
     * 模擬試験の開始画面を表示
     */
    public function start($category)
    {
        // カテゴリの検証
        if (!in_array($category, ['medical', 'processing', 'system'])) {
            abort(404);
        }

        // カテゴリ情報を取得
        $categoryInfo = $this->getCategoryInfo($category);
        
        try {
            // カテゴリ内の全問題数を確認
            $totalQuestions = Question::where('category', $category)->count();
            
            // 実際に利用可能な問題数を考慮してquestionCountを調整
            $requestedQuestions = $categoryInfo['questionCount'];
            $actualQuestions = min($totalQuestions, $requestedQuestions);
            
            // 問題を取得（ランダムに並び替え）
            $questions = Question::where('category', $category)
                               ->inRandomOrder()
                               ->limit($actualQuestions)
                               ->get();

            // 問題と選択肢を配列で構築
            $questionsWithOptions = [];
            foreach ($questions as $index => $question) {
                $options = Option::where('question_year', $question->year)
                                ->where('question_number', $question->number)
                                ->where('question_category', $question->category)
                                ->orderBy('number')
                                ->get();
                
                $questionsWithOptions[] = [
                    'question' => $question,
                    'options' => $options
                ];
            }

            // 実際の問題数でcategoryInfoを更新
            $categoryInfo['questionCount'] = count($questionsWithOptions);

            return view('exam.start', [
                'category' => $category,
                'categoryInfo' => $categoryInfo,
                'questions' => $questionsWithOptions
            ]);
            
        } catch (\Exception $e) {
            return back()->with('error', '試験の開始に失敗しました: ' . $e->getMessage());
        }
    }

    /**
     * 模擬試験の終了処理
     */
    public function finish(Request $request)
    {
        $student = Auth::user();
        if (!$student) {
            return redirect()->route('login')->with('error', 'ログインが必要です。');
        }

        $category = $request->input('category');
        $questions = $request->input('questions', []);
        
        // 回答データの取得
        $answers = $request->input('answers', []);
        
        // スコアIDの生成（日付・時間+ユーザーid+分野名）
        $scoreId = now()->format('YmdHis') . $student->id . $category;
        
        // 正答数と回答数の計算
        $correctCount = 0;
        $answeredCount = 0;
        
        foreach ($questions as $index => $questionData) {
            $selectedOption = $answers[$index] ?? null;
            
            if ($selectedOption) {
                $answeredCount++;
                
                // 正答の取得
                $correctOption = Option::where('question_year', $questionData['year'])
                                     ->where('question_number', $questionData['number'])
                                     ->where('question_category', $questionData['category'])
                                     ->where('correct', 1)
                                     ->first();
                
                if ($correctOption && $correctOption->number == $selectedOption) {
                    $correctCount++;
                }
            }
        }
        
        // scoresテーブルに保存
        Score::create([
            'id' => $scoreId,
            'student_id' => $student->id,
            'score' => $correctCount * 2, // 1問2点
            'amount' => $answeredCount,
        ]);
        
        // resultsテーブルとchosen_optionsテーブルに保存
        foreach ($questions as $index => $questionData) {
            $selectedOption = $answers[$index] ?? null;
            $questionNumber = $index + 1;
            
            // 正答の取得
            $correctOption = Option::where('question_year', $questionData['year'])
                                 ->where('question_number', $questionData['number'])
                                 ->where('question_category', $questionData['category'])
                                 ->where('correct', 1)
                                 ->first();
            
            $isCorrect = 0;
            if ($selectedOption && $correctOption && $correctOption->number == $selectedOption) {
                $isCorrect = 1;
            }
            
            // resultsテーブルに保存
            Result::create([
                'score_id' => $scoreId,
                'number' => $questionNumber,
                'question_year' => $questionData['year'],
                'question_number' => $questionData['number'],
                'question_category' => $questionData['category'],
                'correctness' => $isCorrect,
            ]);
            
            // 選択した選択肢がある場合、chosen_optionsテーブルに保存
            if ($selectedOption) {
                ChosenOption::create([
                    'score_id' => $scoreId,
                    'result_number' => $questionNumber,
                    'number' => $selectedOption,
                    'question_year' => $questionData['year'],
                    'question_number' => $questionData['number'],
                    'question_category' => $questionData['category'],
                ]);
            }
        }
        
        // 結果画面にリダイレクト
        return redirect()->route('exam.result', ['scoreId' => $scoreId]);
    }

    /**
     * 模擬試験の結果画面を表示
     */
    public function result($scoreId)
    {
        $score = Score::with(['results', 'chosenOptions'])
                     ->where('id', $scoreId)
                     ->where('student_id', Auth::id())
                     ->first();

        if (!$score) {
            abort(404);
        }

        // 詳細な結果データを構築
        $detailedResults = [];
        foreach ($score->results as $result) {
            // 問題データを取得
            $question = Question::where('year', $result->question_year)
                              ->where('number', $result->question_number)
                              ->where('category', $result->question_category)
                              ->first();
                              
            $options = Option::where('question_year', $result->question_year)
                           ->where('question_number', $result->question_number)
                           ->where('question_category', $result->question_category)
                           ->orderBy('number')
                           ->get();

            $chosenOption = $score->chosenOptions
                                 ->where('result_number', $result->number)
                                 ->first();

            // 正答を取得
            $correctOption = $options->where('correct', 1)->first();

            $detailedResults[] = [
                'number' => $result->number,
                'question' => $question,
                'options' => $options,
                'chosen_option_number' => $chosenOption ? $chosenOption->number : null,
                'correct_option_number' => $correctOption ? $correctOption->number : null,
                'is_correct' => $result->correctness,
                'is_answered' => $chosenOption !== null
            ];
        }

        // カテゴリ情報を取得（最初の結果から）
        $firstResult = $score->results->first();
        $category = $firstResult ? $firstResult->question_category : 'unknown';
        $categoryInfo = $this->getCategoryInfo($category);

        return view('exam.result', compact('score', 'detailedResults', 'categoryInfo'));
    }

    /**
     * カテゴリ情報を取得
     */
    private function getCategoryInfo($category)
    {
        $categoryData = [
            'medical' => [
                'name' => '医学・医療系',
                'questionCount' => 50,
                'timeLimit' => 60,
                'maxScore' => 100
            ],
            'processing' => [
                'name' => '情報処理技術系',
                'questionCount' => 50,
                'timeLimit' => 60,
                'maxScore' => 100
            ],
            'system' => [
                'name' => '医療情報システム系',
                'questionCount' => 60,
                'timeLimit' => 90,
                'maxScore' => 120
            ]
        ];

        return $categoryData[$category] ?? $categoryData['medical'];
    }
}