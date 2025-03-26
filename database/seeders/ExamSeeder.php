<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paketCPNS2026 = Package::create([
            'name' => 'Paket CPNS 2026',
        ]);
        $paketCPNS2026->exams()->createMany([
            ['name' => 'SKD', 'type_id' => 1],
            ['name' => 'SKB', 'type_id' => 2],
        ]);

        $paketSekdin2026 = Package::create([
            'name' => 'Paket Sekdin 2026',
        ]);
        $paketSekdin2026->exams()->createMany([
            ['name' => 'SKD', 'type_id' => 1],
            ['name' => 'TPA', 'type_id' => 3],
        ]);
    }
}
