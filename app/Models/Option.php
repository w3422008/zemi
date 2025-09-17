<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    protected $table = 'options';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'number',
        'question_year',
        'question_number',
        'question_category',
        'correct',
        'body'
    ];

    /**
     * この選択肢が属する問題を取得
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
        return $this->correct == 1;
    }
}