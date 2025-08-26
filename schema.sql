-- データベース作成・利用（既存DBがあれば削除）
DROP DATABASE IF EXISTS zemi_db;
CREATE DATABASE zemi_db DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE zemi_db;

-- 学生情報（ログイン・認証・プロフィール等）を管理するテーブル
DROP TABLE IF EXISTS students;
CREATE TABLE students (
    student_id CHAR(8) NOT NULL PRIMARY KEY COMMENT '学生ID（主キー）',
    first_name VARCHAR(255) NOT NULL COMMENT '名',
    last_name VARCHAR(255) NOT NULL COMMENT '姓',
    nickname VARCHAR(255) NULL COMMENT 'ニックネーム',
    password CHAR(255) NOT NULL COMMENT 'パスワード（ハッシュ化）',
    ticket_amount INT(11) NOT NULL DEFAULT 0 COMMENT 'チケット数',
    email VARCHAR(255) NULL UNIQUE COMMENT 'メールアドレス（一意・オプション）',
    email_verified_at TIMESTAMP NULL COMMENT 'メール認証日時',
    remember_token VARCHAR(100) NULL COMMENT 'ログイン保持用トークン',
    created_at TIMESTAMP NULL COMMENT 'レコード作成日時',
    updated_at TIMESTAMP NULL COMMENT 'レコード更新日時'
);

-- カテゴリテーブル
DROP TABLE IF EXISTS categories;
CREATE TABLE categories (
    category_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'カテゴリID',
    category_name VARCHAR(255) NOT NULL COMMENT 'カテゴリ名'
);

-- 問題テーブル
DROP TABLE IF EXISTS problems;
CREATE TABLE problems (
    problem_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT '問題ID',
    category_id INT(11) NOT NULL COMMENT 'カテゴリID',
    year INT(11) NOT NULL COMMENT '年度',
    problem_number INT(11) NOT NULL COMMENT '問題番号',
    chapter INT(11) NOT NULL COMMENT '章',
    middle_category INT(11) NOT NULL COMMENT '中分類',
    small_category INT(11) NOT NULL COMMENT '小分類',
    problem_body VARCHAR(255) NOT NULL COMMENT '問題文',
    problem_image VARCHAR(255) NOT NULL COMMENT '問題画像',
    option_1 VARCHAR(255) NOT NULL COMMENT '選択肢1',
    option_2 VARCHAR(255) NOT NULL COMMENT '選択肢2',
    option_3 VARCHAR(255) NOT NULL COMMENT '選択肢3',
    option_4 VARCHAR(255) NOT NULL COMMENT '選択肢4',
    option_5 VARCHAR(255) NOT NULL COMMENT '選択肢5',
    image_option_1 VARCHAR(255) NOT NULL COMMENT '選択肢画像1',
    image_option_2 VARCHAR(255) NOT NULL COMMENT '選択肢画像2',
    image_option_3 VARCHAR(255) NOT NULL COMMENT '選択肢画像3',
    image_option_4 VARCHAR(255) NOT NULL COMMENT '選択肢画像4',
    image_option_5 VARCHAR(255) NOT NULL COMMENT '選択肢画像5',
    answer VARCHAR(255) NOT NULL COMMENT '正解',
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE CASCADE
);

-- モンスターテーブル
DROP TABLE IF EXISTS monsters;
CREATE TABLE monsters (
    monster_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'モンスターID',
    image_path VARCHAR(255) NOT NULL COMMENT '画像パス',
    description VARCHAR(255) NOT NULL COMMENT '説明',
    monster_name VARCHAR(255) NOT NULL COMMENT 'モンスター名',
    category_id INT(11) NOT NULL COMMENT 'カテゴリID',
    maximum_exp INT(11) NOT NULL COMMENT '最大経験値',
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE CASCADE
);

-- 素材テーブル
DROP TABLE IF EXISTS materials;
CREATE TABLE materials (
    material_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT '素材ID',
    image_path VARCHAR(255) NOT NULL COMMENT '画像パス',
    material_name VARCHAR(255) NOT NULL COMMENT '素材名',
    exp_amount INT(11) NOT NULL COMMENT '経験値量',
    category_id INT(11) NOT NULL COMMENT 'カテゴリID',
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE CASCADE
);

-- クエストテーブル
DROP TABLE IF EXISTS quests;
CREATE TABLE quests (
    quest_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'クエストID',
    quest_name VARCHAR(255) NOT NULL COMMENT 'クエスト名'
);

-- 結果テーブル
DROP TABLE IF EXISTS results;
CREATE TABLE results (
    result_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT '結果ID',
    student_id CHAR(8) NOT NULL COMMENT '学生ID',
    problem_id INT(11) NOT NULL COMMENT '問題ID',
    flag TINYINT(1) NOT NULL COMMENT 'フラグ',
    correctness INT(11) NOT NULL COMMENT '正解性',
    times INT(11) NOT NULL COMMENT '回数',
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    FOREIGN KEY (problem_id) REFERENCES problems(problem_id) ON DELETE CASCADE
);

-- 達成クエストテーブル
DROP TABLE IF EXISTS achieved_quests;
CREATE TABLE achieved_quests (
    student_id CHAR(8) NOT NULL COMMENT '学生ID',
    quest_id INT(11) NOT NULL COMMENT 'クエストID',
    PRIMARY KEY (student_id, quest_id),
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    FOREIGN KEY (quest_id) REFERENCES quests(quest_id) ON DELETE CASCADE
);

-- 所持モンスターテーブル
DROP TABLE IF EXISTS possession_monsters;
CREATE TABLE possession_monsters (
    student_id CHAR(8) NOT NULL COMMENT '学生ID',
    monster_id INT(11) NOT NULL COMMENT 'モンスターID',
    exp INT(11) NOT NULL COMMENT '経験値',
    PRIMARY KEY (student_id, monster_id),
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    FOREIGN KEY (monster_id) REFERENCES monsters(monster_id) ON DELETE CASCADE
);

-- 所持素材テーブル
DROP TABLE IF EXISTS posession_materials;
CREATE TABLE posession_materials (
    student_id CHAR(8) NOT NULL COMMENT '学生ID',
    material_id INT(11) NOT NULL COMMENT '素材ID',
    amount INT(11) NOT NULL COMMENT '数量',
    PRIMARY KEY (student_id, material_id),
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    FOREIGN KEY (material_id) REFERENCES materials(material_id) ON DELETE CASCADE
);

-- パスワードリセット申請時のトークン・メールアドレスを一時保存するテーブル
DROP TABLE IF EXISTS password_resets;
CREATE TABLE password_resets (
    email VARCHAR(255) NOT NULL COMMENT 'パスワードリセット申請メールアドレス',
    token VARCHAR(255) NOT NULL COMMENT 'リセット用トークン',
    created_at TIMESTAMP NULL COMMENT '申請日時',
    INDEX(email)
);

-- キュー処理（非同期ジョブ）が失敗した際の内容・例外情報を記録するテーブル
DROP TABLE IF EXISTS failed_jobs;
CREATE TABLE failed_jobs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT 'ジョブID（主キー）',
    uuid VARCHAR(255) NOT NULL UNIQUE COMMENT 'ジョブ一意識別子',
    connection TEXT NOT NULL COMMENT '接続名',
    queue TEXT NOT NULL COMMENT 'キュー名',
    payload LONGTEXT NOT NULL COMMENT 'ジョブ内容',
    exception LONGTEXT NOT NULL COMMENT '失敗時の例外内容',
    failed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '失敗日時'
);

-- API認証や外部サービス連携用のアクセストークンを管理するテーブル
DROP TABLE IF EXISTS personal_access_tokens;
CREATE TABLE personal_access_tokens (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT 'トークンID（主キー）',
    tokenable_type VARCHAR(255) NOT NULL COMMENT 'トークン所有者のタイプ（モデル名など）',
    tokenable_id BIGINT UNSIGNED NOT NULL COMMENT 'トークン所有者のID',
    name VARCHAR(255) NOT NULL COMMENT 'トークン名',
    token VARCHAR(64) NOT NULL UNIQUE COMMENT 'アクセストークン（一意）',
    abilities TEXT NULL COMMENT 'トークンの権限（JSON等）',
    last_used_at TIMESTAMP NULL COMMENT '最終使用日時',
    created_at TIMESTAMP NULL COMMENT 'レコード作成日時',
    updated_at TIMESTAMP NULL COMMENT 'レコード更新日時',
    INDEX tokenable (tokenable_type, tokenable_id)
);
