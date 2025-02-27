<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function getDashboard()
    {
        $countries = ['names'=>[], 'data'=>[]];
        $states = [ 'names'=>[], 'data'=>[]];
        $cities = [ 'names'=>[], 'data'=>[]];


        $countryQuery = Country::limit(10)
            ->select('name', DB::raw('(select count(*) from users where country_id = countries.id) as user_count'))
            ->orderByDesc('user_count');

        logger("countries");
        $statesRes= State::limit(10)
            ->select('name', DB::raw('(select count(*) from users where state_id = states.id) as user_count'))
            ->orderByDesc('user_count')->get();

        logger("states");
        $citiesRes = City::limit(10)
            ->select('name', DB::raw('(select count(*) from users where city_id = cities.id) as user_count'))
            ->orderByDesc('user_count')->get();

        logger("cities");

        $stateIndex = 0;
        $cityIndex = 0;
        foreach($countryQuery->get() as $country){

            array_push($countries['names'], $country->name);
            array_push($countries['data'], $country->user_count);

            $state = $statesRes[$stateIndex];
            $stateIndex++;
            array_push($states['names'], $state->name);
            array_push($states['data'], $state->user_count);

            $city = $citiesRes[$cityIndex];
            $cityIndex++;
            array_push($cities['names'], $city->name);
            array_push($cities['data'], $city->user_count);
        }

        return view('dashboard', ['countries'=>$countries, 'states'=>$states, 'cities'=>$cities]);
    }
}
