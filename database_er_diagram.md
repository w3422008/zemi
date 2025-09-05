# データベース ER図

このファイルは zemi_app のデータベース構造を示すER図です。

## ER図

```mermaid
erDiagram
    students {
        VARCHAR(255) id PK
        VARCHAR(255) display_name
        VARCHAR(255) email UK
        VARCHAR(255) password
    }
    
    questions {
        INTEGER year PK
        INTEGER number PK
        ENUM category PK "system, processing, medical"
        INTEGER edition
        INTEGER section
        INTEGER chapter
        INTEGER item
        VARCHAR(255) body
        VARCHAR(255) image_path
    }
    
    options {
        INTEGER number PK
        INTEGER question_year PK, FK
        INTEGER question_number PK, FK
        ENUM question_category PK, FK "system, processing, medical"
        BOOLEAN correct
        VARCHAR(255) body
    }
    
    scores {
        VARCHAR(255) id PK
        VARCHAR(255) student_id FK
        INTEGER score
        INTEGER amount
        DATETIME createad_at
    }
    
    results {
        VARCHAR(255) score_id PK, FK
        INTEGER number PK
        INTEGER question_year FK
        INTEGER question_number FK
        ENUM question_category FK "system, processing, medical"
        BOOLEAN correctness
    }
    
    chosen_options {
        VARCHAR(255) score_id PK, FK
        INTEGER result_number PK, FK
        INTEGER number FK
        INTEGER question_year FK
        INTEGER question_number FK
        ENUM question_category FK "system, processing, medical"
    }
    
    students ||--o{ scores : "has"
    scores ||--o{ results : "contains"
    results ||--|| chosen_options : "links to"
    questions ||--o{ options : "has"
    questions ||--o{ results : "answered in"
    questions ||--o{ chosen_options : "selected from"
    options ||--o{ chosen_options : "can be chosen"
```

## テーブル説明

### students（学生）
- **id**: 学生の一意識別子
- **display_name**: 表示名
- **email**: メールアドレス（ユニーク）
- **password**: パスワード

### questions（問題）
- **year, number, category**: 複合主キー
- **category**: システム、処理、医療の3つのカテゴリ
- **edition, section, chapter, item**: 問題の階層構造
- **body**: 問題文
- **image_path**: 問題に関連する画像のパス

### options（選択肢）
- **number**: 選択肢番号
- **question_year, question_number, question_category**: 問題への外部キー
- **correct**: 正解フラグ
- **body**: 選択肢の内容

### scores（得点）
- **id**: 得点記録の一意識別子
- **student_id**: 学生への外部キー
- **score**: 得点
- **amount**: 問題数
- **createad_at**: 作成日時

### results（結果）
- **score_id, number**: 複合主キー
- **question_year, question_number, question_category**: 問題への外部キー
- **correctness**: 正解/不正解フラグ

### chosen_options（選択された選択肢）
- **score_id, result_number**: 複合主キー
- **number, question_year, question_number, question_category**: 選択肢と問題への外部キー

## システム概要

このデータベースは学習管理システムのもので、以下の機能をサポートしています：

1. **学生管理**: 学生の登録と認証
2. **問題管理**: カテゴリ別の問題とその選択肢
3. **試験実施**: 学生の回答記録と採点
4. **結果分析**: 個別問題ごとの正解率や学習履歴の追跡

学生が試験を受けると、`scores`テーブルに全体の得点が記録され、`results`テーブルに各問題の正解/不正解が、`chosen_options`テーブルに実際に選択した選択肢が記録される構造になっています。
