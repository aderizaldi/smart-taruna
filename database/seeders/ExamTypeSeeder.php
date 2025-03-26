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

        $typeSKB = Type::create(['name' => 'SKB', 'passing_score' => 0]);
        $typeSKB->sections()->createMany([
            ['name' => 'SKB', 'scoring_type' => 'point', 'passing_score' => 0],
        ]);

        $typeTPA = Type::create(['name' => 'TPA', 'passing_score' => 0]);
        $typeTPA->sections()->createMany([
            ['name' => 'Psikotes', 'scoring_type' => 'point', 'passing_score' => 0],
        ]);

        $typeTryOutTWK = Type::create(['name' => 'Try Out TWK', 'passing_score' => 65]);
        $typeTryOutTWK->sections()->createMany([
            ['name' => 'TWK', 'scoring_type' => 'right_or_wrong', 'wrong_answer_point' => 0, 'right_answer_point' => 5, 'passing_score' => 65],
        ]);

        $typeTryOutTIU = Type::create(['name' => 'Try Out TIU', 'passing_score' => 80]);
        $typeTryOutTIU->sections()->createMany([
            ['name' => 'TIU', 'scoring_type' => 'right_or_wrong', 'wrong_answer_point' => 0, 'right_answer_point' => 5, 'passing_score' => 80],
        ]);

        $typeTryOutTKP = Type::create(['name' => 'Try Out TKP', 'passing_score' => 166]);
        $typeTryOutTKP->sections()->createMany([
            ['name' => 'TKP', 'scoring_type' => 'right_or_wrong', 'wrong_answer_point' => 0, 'right_answer_point' => 5, 'passing_score' => 166],
        ]);
    }
}
