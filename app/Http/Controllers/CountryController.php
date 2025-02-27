<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use App\Models\user;
use Illuminate\Support\Facades\DB;

class CountryController extends Controller
{
    public function index()
    {
        $countries = Country::whereHas('user')->get(); 
        $defaultCountry = Country::first();

        $userData = [];
        if ($defaultCountry) {
            $userData = $this->getUsersByState($defaultCountry->id);
        }

        return view('country', compact('countries', 'defaultCountry', 'userData'));
    }

    public function fetchStateData(Request $request)
    {
        $countryId = $request->country_id;
        $userData = $this->getUsersByState($countryId);
        return response()->json($userData);
    }

    private function getUsersByState($countryId)
    {
        return State::where('states.country_id', $countryId) // ✅ Explicitly specify `states.country_id`
            ->leftJoin('users', 'states.id', '=', 'users.state_id')
            ->select('states.name', DB::raw('COUNT(users.id) as users_count'))
            ->groupBy('states.id', 'states.name')
            ->get();
    }
}
