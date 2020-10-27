<?php

namespace App\Http\Controllers\Api\V1;

use App\Category;
use App\Vehicle;
use App\Company;
use App\CompanyAddress;
use App\CategoryVehicle;
use App\Helpers\VehicleAvailableCheckHelper;
use App\Country;
use App\Http\Resources\CategoryResource;
use App\Setting;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country_code' => 'required',
            'is_country'   => 'required',
            'longitude'    => 'required',
            'latitude'     => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 403);
        }

        //  DB::enableQueryLog();

        $country_data = Country::where('country_code', $request->input('country_code'))->first();

        $filter_array['latitude'] = $request->input('latitude');
        $filter_array['longitude'] = $request->input('longitude');
        $filter_array['is_country'] = $request->input('is_country');
        $filter_array['min_distance'] = $this->minCompanyDistance();
        $filter_array['max_distance'] = $this->maxCompanyDistance();
        $get_default_distance = Setting::where('meta_key', 'default_radius_km')->first();
        $filter_array['default_distance'] = $get_default_distance->meta_value;
        $filter_array['country_id'] = '';
        if($country_data){
            $filter_array['country_id'] = $country_data->id;
        }

        $var = new CompanyAddress();
        $companies = $var->haversineCompany($var, $filter_array);
        if(count($companies) > 0){
            $array = [];
            foreach($companies as $key => $company){
                $array[$key] = $company->id;
            }
            $filter_array['company'] = $array;
        } else{
            return response()->json([
                'success' => false,
                'message' => Config('languageString.service_not_available'),
            ]);
        }
        $categories = Category::all();

        if(count($categories) > 0){
            $i = 0;
            $array = [];

            foreach($categories as $category){
                $vehicles = CategoryVehicle::with('vehicle')
                    ->where('category_id', $category->id)
                    ->whereIn('company_address_id', $filter_array['company'])
                    ->get();

                if($request->input('pick_up_date') != NULL && $request->input('return_date') != NULL){
                    $total = 0;
                    foreach($vehicles as $key => $vehicle){
                        $response = VehicleAvailableCheckHelper::inRideBetween($request->input('pick_up_date'), $request->input('return_date'), $vehicle->id);
                        $not_availability_response = VehicleAvailableCheckHelper::inRideAvailability($request->input('pick_up_date'), $request->input('return_date'), $vehicle->id);
                        if($response == 0 && $not_availability_response == 0){
                            $total += 1;
                        }
                    }
                } else{
                    $total = count($vehicles);
                }
                $array[$i]['id'] = $category->id;
                $array[$i]['name'] = $category->name;
                $array[$i]['image'] = $category->image;
                $array[$i]['total_rides'] = $total;
                $i++;
            }


            return response()->json([
                'success' => true,
                'data'    => $array,
            ], 200);
        } else{
            return response()->json([
                'success' => false,
                'message' => Config('languageString.ryde_not_found'),
            ], 200);
        }
    }

    public function arrayUnique($array)
    {
        $serialized_array = array_map("serialize", $array);
        foreach($serialized_array as $key => $val){
            $result[$val] = true;
        }

        return array_map("unserialize", (array_keys($result)));

    }


    public function minCompanyDistance()
    {
        return CompanyAddress::min('service_distance');
    }

    public function maxCompanyDistance()
    {
        return CompanyAddress::max('service_distance');
    }

}
