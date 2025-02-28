<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CountryController extends Controller
{
    public function index()
    {
        $countries = Country::whereHas('user')->get(); 
        $defaultCountry = Country::first();
        $defaultState = State::where('country_id', $defaultCountry->id)->first(); 

        $userData = [];
        $cityData = [];

        if ($defaultCountry) {
            $userData = $this->getUsersByState($defaultCountry->id);
        }

        if ($defaultState) {
            $cityData = $this->getUsersByCity($defaultState->id);
        }

        return view('country', compact('countries', 'defaultCountry', 'userData', 'defaultState', 'cityData'));
    }

    public function fetchStateData(Request $request)
    {
        $countryId = $request->country_id;
        $userData = $this->getUsersByState($countryId);
        return response()->json($userData);
    }

    public function fetchCityData(Request $request)
    {
        $stateId = $request->state_id;
        $cityData = $this->getUsersByCity($stateId);
        return response()->json($cityData);
    }

    public function fetchGenderData(Request $request)
{
    $stateId = $request->state_id;
    $genderData = User::where('state_id', $stateId)
        ->select(
            DB::raw("SUM(CASE WHEN gender = 'M' THEN 1 ELSE 0 END) as male_count"),
            DB::raw("SUM(CASE WHEN gender = 'F' THEN 1 ELSE 0 END) as female_count")
        )
        ->first();

    return response()->json($genderData);
}


    private function getUsersByState($countryId)
    {
        return State::where('states.country_id', $countryId)
            ->leftJoin('users', 'states.id', '=', 'users.state_id')
            ->select('states.id', 'states.name', DB::raw('COUNT(users.id) as users_count'))
            ->groupBy('states.id', 'states.name')
            ->get();
    }

    private function getUsersByCity($stateId)
    {
        return City::where('cities.state_id', $stateId)
            ->leftJoin('users', 'cities.id', '=', 'users.city_id')
            ->select('cities.name', DB::raw('COUNT(users.id) as users_count'))
            ->groupBy('cities.id', 'cities.name')
            ->get();
    }
}
