<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    protected $table = 'results';
    protected $primaryKey = ['score_id', 'number'];
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'score_id',
        'number',
        'question_year',
        'question_number',
        'question_category',
        'correctness'
    ];

    /**
     * このリザルトに関連するスコアを取得
     */
    public function score()
    {
        return $this->belongsTo(Score::class, 'score_id', 'id');
    }

    /**
     * このリザルトが参照する問題を取得
     */
    public function question()
    {
        return Question::where('year', $this->question_year)
                      ->where('number', $this->question_number)
                      ->where('category', $this->question_category)
                      ->first();
    }

    /**
     * 正解かどうかを判定
     */
    public function isCorrect()
    {
        return $this->correctness == 1;
    }
}