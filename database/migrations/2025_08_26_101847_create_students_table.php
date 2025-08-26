<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->char('student_id', 8)->primary()->comment('学生ID（主キー）');
            $table->string('first_name')->comment('名');
            $table->string('last_name')->comment('姓');
            $table->string('nickname')->nullable()->comment('ニックネーム');
            $table->string('password')->comment('パスワード（ハッシュ化）');
            $table->integer('ticket_amount')->default(0)->comment('チケット数');
            $table->string('email')->nullable()->unique()->comment('メールアドレス（一意・オプション）');
            $table->timestamp('email_verified_at')->nullable()->comment('メール認証日時');
            $table->rememberToken()->comment('ログイン保持用トークン');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students');
    }
}
