<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExamTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $typeSKD = Type::create(['name' => 'SKD', 'passing_score' => 311]);
        $typeSKD->sections()->createMany([
            ['name' => 'TWK', 'scoring_type' => 'right_or_wrong', 'wrong_answer_point' => 0, 'right_answer_point' => 5, 'passing_score' => 65],
            ['name' => 'TIU', 'scoring_type' => 'right_or_wrong', 'wrong_answer_point' => 0, 'right_answer_point' => 5, 'passing_score' => 80],
            ['name' => 'TKP', 'scoring_type' => 'point', 'passing_score' => 166],
        ]);

        $typeTPA = Type::create(['name' => 'TPA', 'passing_score' => 0]);
        $typeTPA->sections()->createMany([
            ['name' => 'Psikotes', 'scoring_type' => 'point', 'passing_score' => 0],
        ]);

        $typeTryOutSKD = Type::create(['name' => 'Try Out SKD', 'passing_score' => 65]);
        $typeTryOutSKD->sections()->createMany([
            ['name' => 'SKD', 'scoring_type' => 'right_or_wrong', 'wrong_answer_point' => 0, 'right_answer_point' => 5, 'passing_score' => 65],
        ]);

        $typeTryOutTPA = Type::create(['name' => 'Try Out TIU', 'passing_score' => 80]);
        $typeTryOutTPA->sections()->createMany([
            ['name' => 'TIU', 'scoring_type' => 'right_or_wrong', 'wrong_answer_point' => 0, 'right_answer_point' => 5, 'passing_score' => 80],
        ]);

        $typeTryOutTPA = Type::create(['name' => 'Try Out TKP', 'passing_score' => 166]);
        $typeTryOutTPA->sections()->createMany([
            ['name' => 'TKP', 'scoring_type' => 'right_or_wrong', 'wrong_answer_point' => 0, 'right_answer_point' => 5, 'passing_score' => 166],
        ]);
    }
}
