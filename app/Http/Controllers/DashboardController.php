<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\State;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function getDashboard()
    {
        $states = [ 'names'=>[], 'data'=>[]];

        $countriesMale = [];
        $countriesFemale = [];
        $countries= [];

        $countriesRes = Country::limit(20)
            ->select('countries.id as id', 'countries.name as name', DB::raw('count(users.id) as user_count'), 'users.gender as gender')
            ->join('users', 'users.country_id', '=', 'countries.id' )
            ->groupBy('countries.id', 'countries.name', 'users.gender')
            ->orderByDesc('user_count')
            ->get();

        $statesRes= State::limit(10)
            ->select('states.id as id', 'states.name as name', DB::raw('count(users.id) as user_count'))
            ->join('users', 'users.state_id', '=', 'states.id' )
            ->groupBy('states.id', 'states.name')
            ->orderByDesc('user_count')
            ->get();


        for($i=0;$i<20;$i++){
            $country =  $countriesRes[$i];
            $name = $country->name;
            $gender = $country->gender;
            $userCount = $country->user_count;

            if($gender=="F"){
                $countriesFemale[$name] = $userCount;
            }else{
                $countriesMale[$name] = $userCount;
            }


            if(!isset($countries[$name])){
                $countries[$name] = $userCount;
            }else{
                $countries[$name] += $userCount;
            }

        }

        for($i=0;$i<10;$i++){
            $state = $statesRes[$i];
            array_push($states['names'], $state->name);
            array_push($states['data'], $state->user_count);

        }

        return view('dashboard', ['countries'=>$countries,'countriesMale'=>$countriesMale,'countriesFemale'=>$countriesFemale, 'states'=>$states, 'cities'=>[]]);
    }
}
