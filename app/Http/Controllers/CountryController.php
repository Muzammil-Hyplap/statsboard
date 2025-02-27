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
        $defaultState = State::where('country_id', $defaultCountry->id)->first(); // Get first state of the default country

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

    public function fetchGenderDataByState(Request $request)
    {
        $stateId = $request->state_id;

        $users = User::where('state_id', $stateId)->get();

        $maleCount = User::where('state_id', $stateId)->where('gender', 'M')->count();
        $femaleCount = User::where('state_id', $stateId)->where('gender', 'F')->count();

        // Debugging log
        \Log::info("Gender Data for State ID {$stateId}:", [
            'male_count' => $maleCount,
            'female_count' => $femaleCount
        ]);

        return response()->json([
            'users' => $users, // Debugging
            'male_count' => $maleCount,
            'female_count' => $femaleCount
        ]);
    }
}
