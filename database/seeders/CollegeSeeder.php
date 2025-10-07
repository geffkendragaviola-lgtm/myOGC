<?php

namespace Database\Seeders;

use App\Models\College;
use Illuminate\Database\Seeder;

class CollegeSeeder extends Seeder
{
    public function run(): void
    {
        $colleges = [
            ['name' => 'College of Arts and Social Sciences'],
            ['name' => 'College of Engineering'],
            ['name' => 'College of Engineering Technology'],
            ['name' => 'College of Economics, Business and Accountancy'],
            ['name' => 'College of Education'],
            ['name' => 'College of Science and Mathematics'],
            ['name' => 'College of Computer Studies'],
            ['name' => 'College of Health Sciences']
        ];

        foreach ($colleges as $college) {
            College::firstOrCreate(
                ['name' => $college['name']], // Check if exists by name
                $college // Create if doesn't exist
            );
        }
    }
}
