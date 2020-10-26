<?php

namespace App\Http\Controllers\Api\V1;

use App\Country;
use App\Http\Resources\CountryResource;
use App\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CountryController extends Controller
{
    public function index()
    {
        $countries = CountryResource::collection(
            Country::where('status', 'Active')->orderBy('country_order', 'ASC')->get()
        );


        if (count($countries) > 0) {
            $search = Setting::where('meta_key', 'is_country_search')->select('meta_value')->first();
            return [
                'success' => true,
                'data' => $countries,
                'search' => $search
            ];
        } else {
            return [
                'success' => false,
                'message' => Config('languageString.country_not_found')
            ];
        }
    }
}
