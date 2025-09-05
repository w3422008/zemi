<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTableNew extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // テーブルが既に存在する場合はスキップ
        if (!Schema::hasTable('students')) {
            Schema::create('students', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->string('display_name');
                $table->string('email')->unique();
                $table->string('password');
            });
        }
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
