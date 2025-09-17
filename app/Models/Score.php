<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    use HasFactory;

    protected $table = 'scores';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    
    // updated_atカラムが存在しないため、タイムスタンプを無効化
    public $timestamps = false;

    protected $fillable = [
        'id',
        'student_id',
        'score',
        'amount'
    ];

    /**
     * このスコアに関連する学生を取得
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    /**
     * このスコアに関連する結果を取得
     */
    public function results()
    {
        return $this->hasMany(Result::class, 'score_id', 'id');
    }

    /**
     * このスコアに関連する選択肢を取得
     */
    public function chosenOptions()
    {
        return $this->hasMany(ChosenOption::class, 'score_id', 'id');
    }

    /**
     * スコアIDを生成
     */
    public static function generateScoreId($studentId, $category)
    {
        return now()->format('YmdHis') . $studentId . $category;
    }
}