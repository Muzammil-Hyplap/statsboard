<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Country;

class CountryController extends Controller
{
    public function country(){
        $defaultCountry= Country::where('name', 'India')->first();
        return view('country-dashboard', compact('defaultCountry'));
    }

    public function getUsersByState(Request $request){
        $countryId = $request->input('country_id');

        $data = User::where('state_id, count(*) as user_count')
            ->where('country_id', $countryId)
            ->groupBy('state_id')
            ->with('state')
            ->get();

        return response()->json($data);
    }
}
