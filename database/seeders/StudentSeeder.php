<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Student;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Student::create([
            'student_id' => 'S0000001',
            'first_name' => 'テスト',
            'last_name' => '太郎',
            'nickname' => 'test_user',
            'password' => Hash::make('password'),
            'ticket_amount' => 10,
            'email' => 'test@example.com',
        ]);

        Student::create([
            'student_id' => 'S0000002',
            'first_name' => 'サンプル',
            'last_name' => '花子',
            'nickname' => 'sample_user',
            'password' => Hash::make('password'),
            'ticket_amount' => 5,
            'email' => 'sample@example.com',
        ]);
    }
}
