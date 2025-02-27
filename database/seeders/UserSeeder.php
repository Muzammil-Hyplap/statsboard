<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Fetch 50 random countries
        $countries = Country::inRandomOrder()->take(30)->get();

        foreach ($countries as $country) {
            // Fetch 10 random states for each country
            $states = State::where('country_id', $country->id)->inRandomOrder()->take(5)->get();

            foreach ($states as $state) {
                // Fetch 10 random cities for each state
                $cities = City::where('state_id', $state->id)->inRandomOrder()->take(5)->get();

                foreach ($cities as $city) {
                    // Create random users for this state and city
                    User::factory()->count(random_int(5, 50))->create([
                        'country_id' => $country->id,
                        'state_id' => $state->id,
                        'city_id' => $city->id,
                    ]);
                }
            }
        }
    }
}
