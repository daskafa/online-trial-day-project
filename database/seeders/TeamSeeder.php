<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teamList = [
            [
                'name' => 'Liverpool',
            ],
            [
                'name' => 'Manchester City',
            ],
            [
                'name' => 'Chelsea',
            ],
            [
                'name' => 'Arsenal',
            ],
        ];

        foreach ($teamList as $key => $team) {
            $teamList[$key]['team_power'] = random_int(1, 10);
            $teamList[$key]['supporter_power'] = random_int(1, 10);
            $teamList[$key]['goalkeeper_power'] = random_int(1, 10);
            $teamList[$key]['created_at'] = now();
        }

        Team::insert($teamList);
    }
}
