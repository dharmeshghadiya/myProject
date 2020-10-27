<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Announcement;
use App\Models\CompanyAddress;
use App\Models\Setting;
use App\Models\DriverRequirement;
use App\Models\Country;
use App\Models\Vehicle;
use App\Models\CategoryVehicle;
use App\Helpers\VehicleAvailableCheckHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'latitude'     => 'required',
            'longitude'    => 'required',
            'pick_up_date' => 'required',
            'return_date'  => 'required',
            'country_code' => 'required',
            'is_country'   => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 403);
        }

        $country_data = $this->getCountryId($request->input('country_code'));

        $filter_array['location'] = $request->input('location');
        $filter_array['latitude'] = $request->input('latitude');
        $filter_array['longitude'] = $request->input('longitude');
        $filter_array['pick_up_date'] = $request->input('pick_up_date');
        $filter_array['body_id'] = $request->input('body_id');
        $filter_array['return_date'] = $request->input('return_date');
        $filter_array['sort_by'] = $request->input('sort_by');
        $filter_array['start_price'] = $request->input('start_price');
        $filter_array['end_price'] = $request->input('end_price');
        $filter_array['dealer_id'] = $request->input('dealer_id');
        $filter_array['insurance_id'] = $request->input('insurance_id');
        $filter_array['make'] = $request->input('make');
        $filter_array['model'] = $request->input('model');
        $filter_array['page'] = $request->input('page');
        $filter_array['is_country'] = $request->input('is_country');
        $filter_array['limit'] = 10;
        $filter_array['country_id'] = '';
        if($country_data){
            $filter_array['country_id'] = $country_data->id;
        }

        if($request->input('page') == 1){
            $get_default_distance = Setting::where('meta_key', 'default_radius_km')->first();
            $filter_array['default_distance'] = $get_default_distance->meta_value;
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
        } else{
            $filter_array['company'] = $request->input('company');
        }


        $vehicles = $this->getVehicle($filter_array);
        // dd($vehicles);
        $array = [];
        $i = 0;

        //  dd($vehicles);

        foreach($vehicles as $vehicle){
            $response = VehicleAvailableCheckHelper::inRideBetween($filter_array['pick_up_date'], $filter_array['return_date'], $vehicle->id);
            $not_availability_response = VehicleAvailableCheckHelper::inRideAvailability($filter_array['pick_up_date'], $filter_array['return_date'], $vehicle->id);
            if($response == 0 && $not_availability_response == 0){
                $array[$i]['vehicle_id'] = $vehicle->id;
                $array[$i]['company_id'] = $vehicle->company_id;
                $array[$i]['company_address_id'] = $vehicle->id;
                $array[$i]['company_name'] = $vehicle->companies->name;
                $array[$i]['address'] = $vehicle->companyAddress->address;
                $array[$i]['hourly_amount'] = $vehicle->hourly_amount;
                $array[$i]['daily_amount'] = $vehicle->daily_amount;
                $array[$i]['weekly_amount'] = $vehicle->weekly_amount;
                $array[$i]['monthly_amount'] = $vehicle->monthly_amount;
                $array[$i]['image'] = $vehicle->ryde->image;
                $array[$i]['trim'] = $vehicle->trim;
                $array[$i]['security_deposit'] = $vehicle->security_deposit;
                $array[$i]['make'] = $vehicle->ryde->brand->name;
                $array[$i]['model'] = $vehicle->ryde->modelYear->name;
                $feature_array = [];
                if($vehicle->vehicleFeature != NULL){
                    $j = 0;
                    foreach($vehicle->vehicleFeature as $feature){
                        $feature_array[$j]['image'] = $feature->feature->image;
                        $feature_array[$j]['name'] = $feature->feature->name;
                        $j++;
                    }
                }
                $array[$i]['features'] = $feature_array;
                $i++;
            }
        }

        $announcement = '';
        if($country_data){
            $announcements_data = Announcement::where("country_id", $country_data->id)->first();
            if($announcements_data){
                $announcement = $announcements_data->name;
            }
        }


        $filters = $this->getVehicleFilter($filter_array['company']);

        $bodies_array = $makes_array = $models_array = $insurances_array = $dealers_array = $price_array = [];
        foreach($filters as $filter){
            $bodies_array[] = [
                'id'   => $filter->ryde->rydeInstance->body->id,
                'name' => $filter->ryde->rydeInstance->body->name,
            ];
            $makes_array[] = [
                'id'   => $filter->ryde->brand->id,
                'name' => $filter->ryde->brand->name,
            ];
            $models_array[] = [
                'make_id' => $filter->ryde->brand->id,
                'id'      => $filter->ryde->modelYear->id,
                'name'    => $filter->ryde->modelYear->name,
            ];
            $insurances_array[] = [
                'id'   => $filter->insurances->id,
                'name' => $filter->insurances->name,
            ];;
            $dealers_array[] = [
                'id'   => $filter->companies->id,
                'name' => $filter->companies->name,
            ];
            $price_array[] = $filter->daily_amount;
        }


        $price_value = $this->priceMinMax($price_array);

        if($filter_array['sort_by'] == 'year'){
            $array = $this->sortAssociativeArrayByKey($array, 'model', 'DESC');
        }
        return response()->json([
            'success'          => true,
            'data'             => $array,
            'announcements'    => $announcement,
            'models_array'     => $this->arrayUnique($models_array),
            'insurances_array' => $this->arrayUnique($insurances_array),
            'dealers_array'    => $this->arrayUnique($dealers_array),
            'bodies_array'     => $this->arrayUnique($bodies_array),
            'makes_array'      => $this->arrayUnique($makes_array),
            'price_min'        => $price_value['price_min'],
            'price_max'        => $price_value['price_max'],
            'company'          => $filter_array['company'],
            'total_page'       => ceil($vehicles->total() / $vehicles->perPage()),
            'search'           => $this->settingValue(),
        ]);
    }

    function sortAssociativeArrayByKey($array, $key, $direction)
    {
        switch($direction){
            case "ASC":
                usort($array, function($first, $second) use ($key){
                    return $first[$key] <=> $second[$key];
                });
                break;
            case "DESC":
                usort($array, function($first, $second) use ($key){
                    return $second[$key] <=> $first[$key];
                });
                break;
            default:
                break;
        }

        return $array;
    }

    public function priceMinMax($price_array)
    {
        if(count($price_array) > 0){
            return ['price_min' => min($price_array), 'price_max' => max($price_array)];
        }
        return ['price_min' => 0, 'price_max' => 0];
    }

    public function settingValue()
    {
        $settings = Setting::select('meta_value', 'meta_key')->get();
        $array = [];
        foreach($settings as $setting){
            $array[$setting->meta_key] = $setting->meta_value;
        }
        return $array;

    }

    public function arrayUnique($array)
    {
        $serialized_array = array_map("serialize", $array);
        foreach($serialized_array as $key => $val){
            $result[$val] = true;
        }

        return array_map("unserialize", (array_keys($result)));

    }

    public function getCountryId($country_code)
    {
        if($country_code != ''){
            return Country::where('country_code', $country_code)->first();
        } else{
            return false;
        }
    }

    public function show($id)
    {
        //DB::enableQueryLog();

        $response = Vehicle::with([
            'ryde'            => function($query){
                $query->with('brand', 'modelYear', 'color');
                $query->with([
                    'rydeInstance' => function($query){
                        $query->with(['body', 'door']);
                    },
                ]);
            }, 'vehicleExtra' => function($query){
                $query->with('extra');
            }, 'companyAddress', 'vehicleFeature', 'companies', 'insurances', 'engine', 'fuel', 'gearbox',
        ])->where('id', $id)->first();
        // dd(DB::getQueryLog());
        $extra_array = [];
        if($response->vehicleExtra != NULL){
            $i = 0;
            foreach($response->vehicleExtra as $key => $extra){
                if($extra->type != 2){
                    $extra_array[$i]['id'] = $extra->id;
                    $extra_array[$i]['name'] = $extra->extra->name;
                    $extra_array[$i]['description'] = $extra->extra->description;
                    $extra_array[$i]['type'] = $extra->type;
                    $extra_array[$i]['price'] = $extra->price;
                    $i++;
                }
            }
        }

        $insurance_name = '';
        if($response->insurances != NULL){
            $insurance_name = $response->insurances->name;
        }
        $seats = '';
        if($response->ryde->rydeInstance != NULL){
            $seats = $response->ryde->rydeInstance->seats;
        }

        $feature_array = [];
        if($response->vehicleFeature != NULL){
            $j = 0;
            foreach($response->vehicleFeature as $feature){
                $feature_array[$j]['image'] = $feature->feature->image;
                $feature_array[$j]['name'] = $feature->feature->name;
                $j++;
            }
        }

        $company_details = CompanyAddress::with('country')->where('id', $response->company_address_id)->first();
        $driver_requirement_array = [];
        $tax_name = $tax_percentage = [];
        if($company_details){
            $driver_requirements = DriverRequirement::where('country_id', $company_details->country_id)->get();
            if(count($driver_requirements) > 0){
                foreach($driver_requirements as $driver_requirement)
                    $driver_requirement_array[] = $driver_requirement->name;
            }

            $tax_name = $company_details->country->tax_name;
            $tax_percentage = $company_details->country->tax_percentage;
        }

        return [
            'success'            => true,
            'make'               => $response->ryde->brand->name,
            'make_image'         => $response->ryde->brand->image,
            'model'              => $response->ryde->name,
            'trim'               => $response->trim,
            'vehicle_id'         => $response->id,
            'company_address_id' => $response->company_address_id,
            'company_id'         => $response->company_id,
            'company_icon'       => '',
            'company_name'       => $response->companies->name,
            'hourly_amount'      => $response->hourly_amount,
            'daily_amount'       => $response->daily_amount,
            'weekly_amount'      => $response->weekly_amount,
            'monthly_amount'     => $response->monthly_amount,
            'security_deposit'   => $response->security_deposit,
            'image'              => $response->ryde->image,
            'bodies'             => $response->ryde->rydeInstance->body->name,
            'engines'            => $response->engine->name,
            'fuels'              => $response->fuel->name,
            'door'               => $response->ryde->rydeInstance->door->name,
            'gearboxes'          => $response->gearbox->name,
            'insurances'         => $insurance_name,
            'seats'              => $seats,
            'color'              => $response->ryde->color->name,
            'address'            => $response->companyAddress->address,
            'latitude'           => $response->companyAddress->latitude,
            'longitude'          => $response->companyAddress->longitude,
            'extra'              => $extra_array,
            'features'           => $feature_array,
            'driver_requirement' => $driver_requirement_array,
            'tax_name'           => $tax_name,
            'tax_percentage'     => $tax_percentage,
        ];
    }


    public function checkRideAvailable(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vehicle_id' => 'required|integer',
            'start_date' => 'required',
            'end_date'   => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 403);
        }
        $vehicle_id = $request->input('vehicle_id');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $start_date = Carbon::parse($start_date)->addSeconds(1);


        $is_ride_booked = VehicleAvailableCheckHelper::inRideBetween($start_date, $end_date, $vehicle_id);
        $is_ride_available = VehicleAvailableCheckHelper::inRideAvailability($start_date, $end_date, $vehicle_id);

        if($is_ride_booked == 0 && $is_ride_available == 0){
            return response()->json([
                'success' => true,
                'message' => Config('languageString.ryde_available'),
            ], 200);
        } else{
            return response()->json([
                'success' => false,
                'message' => Config('languageString.ryde_not_available'),
            ], 200);
        }

    }

    public function featuredCategoriesDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'longitude'    => 'required',
            'latitude'     => 'required',
            'country_code' => 'required',
            'is_country'   => 'required',
            'category_id'  => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 403);
        }

        //  DB::enableQueryLog();
        $country_data = $this->getCountryId($request->input('country_code'));

        $filter_array['category_id'] = $request->input('category_id');
        $filter_array['latitude'] = $request->input('latitude');
        $filter_array['longitude'] = $request->input('longitude');
        $filter_array['is_country'] = $request->input('is_country');
        $filter_array['country_id'] = '';
        if($country_data){
            $filter_array['country_id'] = $country_data->id;
        }
        $get_default_distance = Setting::where('meta_key', 'default_radius_km')->first();
        $filter_array['default_distance'] = $get_default_distance->meta_value;

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

        //  DB::enableQueryLog();

        $category_vehicles = CategoryVehicle::with([
            'vehicle' => function($query){
                $query->with([
                    'companies', 'companyAddress', 'vehicleExtra', 'vehicleFeature',
                    'ryde' => function($query){
                        $query->with('brand');
                        $query->with('modelYear');
                    },
                ]);
            },
        ])->whereIn('company_address_id', $filter_array['company'])
            ->where('category_id', $filter_array['category_id'])
            ->get();

        $array = [];
        $i = 0;
        foreach($category_vehicles as $key => $category_vehicle){
            $is_array_add = 0;

            if($request->input('pick_up_date') != NULL && $request->input('return_date') != NULL){
                $is_array_add = 1;
            }
            $response = VehicleAvailableCheckHelper::inRideBetween($request->input('pick_up_date'), $request->input('return_date'), $category_vehicle->vehicle->id);
            $not_availability_response = VehicleAvailableCheckHelper::inRideAvailability($request->input('pick_up_date'), $request->input('return_date'), $category_vehicle->vehicle->id);
            if($response == 0 && $not_availability_response == 0){
                $is_array_add = 1;
            }
            if($is_array_add == 1){
                $array[$i]['vehicle_id'] = $category_vehicle->vehicle->id;
                $array[$i]['company_id'] = $category_vehicle->vehicle->company_id;
                $array[$i]['company_address_id'] = $category_vehicle->vehicle->company_address_id;
                $array[$i]['company_name'] = $category_vehicle->vehicle->companies->name;
                $array[$i]['address'] = $category_vehicle->vehicle->companyAddress->address;
                $array[$i]['hourly_amount'] = $category_vehicle->vehicle->hourly_amount;
                $array[$i]['daily_amount'] = $category_vehicle->vehicle->daily_amount;
                $array[$i]['weekly_amount'] = $category_vehicle->vehicle->weekly_amount;
                $array[$i]['monthly_amount'] = $category_vehicle->vehicle->monthly_amount;
                $array[$i]['image'] = $category_vehicle->vehicle->ryde->image;
                $array[$i]['security_deposit'] = $category_vehicle->vehicle->security_deposit;
                $array[$i]['make'] = $category_vehicle->vehicle->ryde->brand->name;
                $array[$i]['model'] = $category_vehicle->vehicle->ryde->modelYear->name;
                $feature_array = [];
                if($category_vehicle->vehicle->vehicleFeature != NULL){
                    $j = 0;
                    foreach($category_vehicle->vehicle->vehicleFeature as $feature){
                        $feature_array[$j]['image'] = $feature->feature->image;
                        $feature_array[$j]['name'] = $feature->feature->name;
                        $j++;
                    }
                }
                $array[$i]['features'] = $feature_array;
                $i++;
            }

        }
        return response()->json([
            'success' => true,
            'data'    => $array,
        ], 200);
    }

    public function getVehicle($filter_array)
    {

        $query = Vehicle::with([
            'companies', 'companyAddress', 'insurances', 'vehicleExtra', 'engine',
            'fuel', 'gearbox', 'vehicleFeature' => function($query){
                $query->with('feature');
            },
        ])->whereHas('ryde', function($query) use ($filter_array){
            $query->with('brand');

            if($filter_array['body_id'] != NULL){
                $query->whereHas('rydeInstance', function($query) use ($filter_array){
                    $query->whereIn('ryde_instances.body_id', [$filter_array['body_id']]);
                });
            }
            if($filter_array['make'] != NULL){
                $query->whereIn('brand_id', [$filter_array['make']]);
            }
            if($filter_array['model'] != NULL){
                $query->whereIn('model_year_id', [$filter_array['model']]);
            }

        })->whereIn('company_address_id', $filter_array['company']);
        if($filter_array['insurance_id'] != NULL){
            $query->whereIn('insurance_id', [$filter_array['insurance_id']]);
        }
        if($filter_array['dealer_id'] != NULL){
            $query->whereIn('company_id', [$filter_array['dealer_id']]);
        }
        if($filter_array['start_price'] != NULL){
            $query->whereBetween('daily_amount', [$filter_array['start_price'], $filter_array['end_price']]);
        }
        if($filter_array['sort_by'] != NULL){
            if($filter_array['sort_by'] == 'new'){
                $query->orderBy('id', 'DESC');
            }
            if($filter_array['sort_by'] == 'rating'){
                $query->orderBy('vehicles.id', 'DESC');
            }
            if($filter_array['sort_by'] == 'price_asc'){
                $query->orderBy('daily_amount', 'ASC');
            }
            if($filter_array['sort_by'] == 'price_desc'){
                $query->orderBy('daily_amount', 'DESC');
            }
        }
        return $query->paginate($filter_array['limit']);

    }


    public function getVehicleFilter($company_id)
    {
        $query = Vehicle::with([
            'ryde' => function($query){
                $query->with([
                    'brand', 'modelYear', 'rydeInstance' => function($query){
                        $query->with('body');
                    },
                ]);
            }, 'companies', 'insurances',
        ])->whereIn('company_address_id', $company_id);
        return $query->get();

    }

}
