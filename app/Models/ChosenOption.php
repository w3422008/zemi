<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChosenOption extends Model
{
    use HasFactory;

    protected $table = 'chosen_options';
    protected $primaryKey = ['score_id', 'result_number'];
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'score_id',
        'result_number',
        'number',
        'question_year',
        'question_number',
        'question_category'
    ];

    /**
     * この選択された選択肢に関連するスコアを取得
     */
    public function score()
    {
        return $this->belongsTo(Score::class, 'score_id', 'id');
    }

    /**
     * この選択された選択肢が参照する問題を取得
     */
    public function question()
    {
        return Question::where('year', $this->question_year)
                      ->where('number', $this->question_number)
                      ->where('category', $this->question_category)
                      ->first();
    }

    /**
     * この選択された選択肢の詳細を取得
     */
    public function option()
    {
        return Option::where('number', $this->number)
                    ->where('question_year', $this->question_year)
                    ->where('question_number', $this->question_number)
                    ->where('question_category', $this->question_category)
                    ->first();
    }
}