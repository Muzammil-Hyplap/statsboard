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
            ->select('countries.id as id', 'countries.name as name', DB::raw('count(users.id) as user_count'))
            ->join('users', 'users.country_id', '=', 'countries.id' )
            ->groupBy('countries.id')
            ->orderByDesc('user_count');

        $statesRes= State::limit(10)
            ->select('states.id as id', 'states.name as name', DB::raw('count(users.id) as user_count'))
            ->join('users', 'users.state_id', '=', 'states.id' )
            ->groupBy('states.id')
            ->orderByDesc('user_count')->get();

        $citiesRes = City::limit(10)
            ->select('cities.id as id', 'cities.name as name', DB::raw('count(users.id) as user_count'))
            ->join('users', 'users.city_id', '=', 'cities.id' )
            ->groupBy('cities.id')
            ->orderByDesc('user_count')->get();


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
