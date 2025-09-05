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
            'id' => 'S0000001',
            'display_name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        Student::create([
            'id' => 'S0000002',
            'display_name' => 'サンプル花子',
            'email' => 'sample@example.com',
            'password' => Hash::make('password'),
        ]);
    }
}
