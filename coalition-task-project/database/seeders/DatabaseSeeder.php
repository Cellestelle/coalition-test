<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $alpha = \App\Models\Project::firstOrCreate(['name' => 'Alpha']);
        $beta = \App\Models\Project::firstOrCreate(['name' => 'Beta']);

        foreach (['Design spec', 'API scaffold', 'Write tests'] as $i => $n) {
            \App\Models\Task::firstOrCreate([
                'name' => $n,
                'project_id' => $alpha->id,
                'priority' => $i + 1
            ]);
        }
        foreach (['Marketing copy', 'Landing page', 'Deploy'] as $i => $n) {
            \App\Models\Task::firstOrCreate([
                'name' => $n,
                'project_id' => $beta->id,
                'priority' => $i + 1
            ]);
        }
    }

}
