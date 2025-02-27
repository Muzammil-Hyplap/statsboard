<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function getDashboard()
    {
        $countries = ['names'=>[], 'data'=>[], 'states'=>[], 'statesData'=>[]];

        foreach(Country::limit(10)->with('state')->get() as $country){
            array_push($countries['names'], $country->name);
            array_push($countries['data'], random_int(1000, 100000));

            /*logger($country->name);*/
            /*logger(count($country->state));*/

            array_push($countries['statesData'], random_int(1000, 100000));
        }

        return view('dashboard', ['countries'=>$countries]);
    }
}
