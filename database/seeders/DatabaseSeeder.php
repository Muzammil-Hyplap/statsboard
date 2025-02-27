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
        $states = State::get();
        $countryDone = [];

        foreach ($states as $state) {
            if(count($countryDone)>=30){
                break;
            }

            $countofcity = count($state->city)-1;

            if ($countofcity < 0 || isset($countryDone[$state->country->name])) {
                continue;
            }

            User::factory()->count(random_int(5, 400))->create([
                'country_id' => $state->country->id,
                'state_id' => $state->id,
                'city_id' => $state->city[random_int(0, $countofcity)]->id,
            ]);

            $countryDone[$state->country->name] = true;
        }
    }
}
