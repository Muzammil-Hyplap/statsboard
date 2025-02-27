<?php

namespace Database\Seeders;

use App\Models\State;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $states = State::limit(30)->with('city')->get();

        foreach ($states as $state) {
            $countofcity = count($state->city)-1;

            if ($countofcity < 0) {
                continue;
            }


            User::factory()->count(random_int(5, 40))->create([
                'country_id' => $state->country->id,
                'state_id' => $state->id,
                'city_id' => $state->city[random_int(0, $countofcity)]->id,
            ]);
        }
    }
}
