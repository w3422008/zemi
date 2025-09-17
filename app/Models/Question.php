<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $table = 'questions';
    public $timestamps = false;

    protected $fillable = [
        'year',
        'number',
        'category',
        'edition',
        'section',
        'chapter',
        'item',
        'body',
        'image_path'
    ];

    /**
     * 複合主キーを無効にし、自動IDを使用
     */
    protected $primaryKey = null;
    public $incrementing = false;

    /**
     * Questionの選択肢を取得
     */
    public function options()
    {
        return Option::where('question_year', $this->year)
                    ->where('question_number', $this->number)
                    ->where('question_category', $this->category)
                    ->orderBy('number');
    }

    /**
     * カテゴリ別の問題を取得
     */
    public static function getByCategory($category, $limit = null)
    {
        $query = self::where('category', $category);
        
        if ($limit) {
            $query->limit($limit);
        }
        
        return $query->get();
    }
}