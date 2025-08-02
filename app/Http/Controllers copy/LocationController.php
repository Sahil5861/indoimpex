<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\State;
use App\Models\City;


class LocationController extends Controller
{
    public function getStates($country_name) {
    $country = Country::where('name', $country_name)->first();
    
    if ($country) {
        $states = State::where('country_id', $country->id)->get();
        return response()->json($states);
    } else {
        return response()->json([], 404); // Country not found
    }
}

public function getCities($state_name)
{
    $state = State::where('name', $state_name)->first();
    
    if ($state) {
        $cities = City::where('state_id', $state->id)->get();
        return response()->json($cities);
    } else {
        return response()->json([], 404); // State not found
    }
}

}
