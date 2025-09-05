<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStudentsTableStructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            // 既存の列を削除
            $table->dropColumn([
                'first_name', 
                'last_name', 
                'nickname', 
                'ticket_amount', 
                'email_verified_at', 
                'remember_token'
            ]);
            
            // 主キーを変更（student_id → id）
            $table->dropPrimary(['student_id']);
            $table->renameColumn('student_id', 'id');
            $table->primary('id');
            
            // 新しい列を追加
            $table->string('display_name')->after('id');
            
            // emailカラムをNOT NULLに変更
            $table->string('email')->nullable(false)->change();
            
            // timestampsを削除
            $table->dropTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            // 変更を戻す
            $table->dropPrimary(['id']);
            $table->renameColumn('id', 'student_id');
            $table->char('student_id', 8)->primary()->change();
            
            $table->dropColumn('display_name');
            
            $table->string('first_name')->after('student_id');
            $table->string('last_name')->after('first_name');
            $table->string('nickname')->nullable()->after('last_name');
            $table->integer('ticket_amount')->default(0)->after('password');
            $table->string('email')->nullable()->change();
            $table->timestamp('email_verified_at')->nullable()->after('email');
            $table->rememberToken();
            $table->timestamps();
        });
    }
}
