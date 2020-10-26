<?php

namespace App\Http\Controllers\Dealer;

use App\Booking;
use App\BranchExtra;
use App\CategoryVehicle;
use App\Commission;
use App\Company;
use App\CompanyAddress;
use App\CompanyTime;
use App\DealerExtra;
use App\GlobalExtra;
use App\Language;
use App\Setting;
use App\User;
use App\UserProfile;
use App\CountryTranslation;
use App\Country;
use App\CountryCode;
use App\Vehicle;
use App\VehicleExtra;
use App\VehicleFeature;
use App\VehicleNotAvailable;
use App\VehicleOption;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use App\Helpers\ImageUploadHelper;
class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * View Dealer Branch List
     */
    public function index(Request $request)
    {

        if($request->ajax()){

            $company_addresses = CompanyAddress::where('company_id', Session('company_id'))->get();

            return Datatables::of($company_addresses)
                ->addColumn('action', function($company_addresses){
                    $edit_button = '<a href="' . route('dealer::branch.edit', [$company_addresses->id]) . '" class="btn btn-icon btn-info" data-toggle="tooltip" data-placement="top" title="Edit"><i class="bx bx-pencil font-size-16 align-middle"></i></a>';
                    $delete_button = '<button data-id="' . $company_addresses->id . '" class="delete-single btn btn-icon btn-danger" data-toggle="tooltip" data-placement="top" title="Delete"><i class="bx bx-trash font-size-16 align-middle"></i></button>';
                    $ryde_instance_button = '<a href="' . route('dealer::ryde', [$company_addresses->id]) . '" class="status-change btn btn-icon btn-secondary" data-effect="effect-fall" data-toggle="tooltip" data-placement="top" title="Ryde"><i class="bx bx-car font-size-16 align-middle"></i></a>';
                    //  $extra_button = '<a href="' . route('dealer::branchExtra', [$company_addresses->id]) . '" class="btn btn-icon btn-warning" data-effect="effect-fall" data-toggle="tooltip" data-placement="top" title="Extra"><i class="bx bxs-extension font-size-16 align-middle"></i></a>';
                    return '<div class="btn-icon-list">' . $edit_button . ' ' . $delete_button . ' ' . $ryde_instance_button . '</div>';
                })
                ->addColumn('service_distance', function($company_addresses){
                    return $company_addresses->service_distance . ' KM';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('dealer.branch.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * Add Branch Page
     */
    public function create()
    {

        $languages = Language::all();
        $setting = Setting::where('meta_key', 'default_radius_km')->select('meta_value')->first();
        $weeks = [
            'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday',
        ];
        $user_id = Auth::user()->id;
        $company_id = Session::get('company_id');
        $company = Company::where('id', $company_id)->first();
        $global_extras = GlobalExtra::all();
        $extras = DealerExtra::where('user_id', Auth::id())->get();
        $dealer_extras = [];
        foreach($extras as $extra){
            $dealer_extras[$extra->global_extra_id] = $extra->type;
        }
        return view('dealer.branch.create', [
            'user_id'          => $user_id,
            'company_id'       => $company_id,
            'languages'        => $languages,
            'service_distance' => $setting->meta_value,
            'weeks'            => $weeks,
            'company'          => $company,
            'global_extras'    => $global_extras,
            'dealer_extras'    => $dealer_extras,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * Add Branch Details
     */
    public function store(Request $request)
    {
        $id = $request->input('edit_value');

        if($id == NULL){
            $validator_array = [
                'phone_no'         => 'required|max:255',
                'service_distance' => 'required|max:255',
                'address'          => 'required',
                'allowed_millage'  => 'numeric',
                'branch_logo'      => 'image|mimes:jpeg,png,jpg',
            ];

        } else{
            $validator_array = [
                'phone_no'         => 'required|max:255',
                'service_distance' => 'required|max:255',
                'address'          => 'required',
                'branch_logo'      => 'image|mimes:jpeg,png,jpg',
                'allowed_millage'  => 'numeric',
            ];
        }
        $validator = Validator::make($request->all(), $validator_array);
        if($validator->fails()){
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }


        if($id == NULL){
            $user_id = $request->input('user_id');

            $country_code = $request->input('country_code');
            $country_list = Country::where('country_code', $country_code)->first();
            if($country_list){
                $country_id = $country_list->id;
            } else{
                $country_order = Country::max('id');
                $country = $request->input('country');
                $code = app('App\CountryCode')->getCountryCode($country_code);
                $country_insert_id = Country::create([
                    'code'          => $code,
                    'country_code'  => $country_code,
                    'country_order' => $country_order + 1,
                ]);

                $languages = Language::all();
                foreach($languages as $language){
                    CountryTranslation::create([
                        'name'       => $country,
                        'country_id' => $country_insert_id->id,
                        'locale'     => $language->language_code,
                    ]);
                }
                $country_id = $country_insert_id->id;
            }
            if($request->hasFile('branch_logo')){
                $files = $request->file('branch_logo');
                $branch_logo = ImageUploadHelper::imageUpload($files);
            }

            $company_address_id = CompanyAddress::create([
                'branch_name'         => $request->input('branch_name'),
                'branch_logo'         => $branch_logo,
                'bank_account'        => $request->input('bank_account'),
                'trading_license'     => $request->input('trading_license'),
                'allowed_millage'     => $request->input('allowed_millage'),
                'company_id'          => $request->input('company_id'),
                'user_id'             => $user_id,
                'phone_no'            => $request->input('phone_no'),
                'branch_contact_name' => $request->input('branch_contact_name'),
                'address'             => $request->input('address'),
                'country_id'          => $country_id,
                'latitude'            => $request->input('latitude'),
                'longitude'           => $request->input('longitude'),
                'service_distance'    => $request->input('service_distance'),

            ]);

            $extras = $request->input('extra');
            $extra_ids = $request->input('extra_ids');

            //BranchExtra::where('company_address_id', $company_address_id->id)->delete();

            if($extras != NULL){
                foreach($extras as $key => $extra){
                    $branch_extra = new BranchExtra();
                    $branch_extra->company_id = $request->input('company_id');
                    $branch_extra->company_address_id = $company_address_id->id;
                    $branch_extra->global_extra_id = $extra_ids[$key];
                    $branch_extra->type = $extra;
                    $branch_extra->save();
                }
            }

            if($request->input('day_no') != NULL){
                foreach($request->input('day_no') as $key => $value){
                    $day_value = $request->input('day_value')[$key];
                    $shift_two_day_value = $request->input('shift_two_day_value')[$key];
                    if($day_value == 1 && $shift_two_day_value == 1){
                        CompanyTime::create([
                            'company_address_id' => $company_address_id->id,
                            'day_no'             => $value,
                            'sift1_start_time'   => $request->input('weekStartArray')[$key],
                            'sift1_end_time'     => $request->input('weekEndArray')[$key],
                            'sift2_start_time'   => $request->input('shiftTwoweekStartArray')[$key],
                            'sift2_end_time'     => $request->input('shiftTwoWeekEndArray')[$key],
                        ]);
                    }
                    if($day_value == 1 && $shift_two_day_value == 0){
                        CompanyTime::create([
                            'company_address_id' => $company_address_id->id,
                            'day_no'             => $value,
                            'sift1_start_time'   => $request->input('weekStartArray')[$key],
                            'sift1_end_time'     => $request->input('weekEndArray')[$key],
                        ]);
                    }
                    if($day_value == 0 && $shift_two_day_value == 1){
                        CompanyTime::create([
                            'company_address_id' => $company_address_id->id,
                            'day_no'             => $value,
                            'sift2_start_time'   => $request->input('shiftTwoweekStartArray')[$key],
                            'sift2_end_time'     => $request->input('shiftTwoWeekEndArray')[$key],
                        ]);
                    }
                }
            }

            return response()->json(['success' => true, 'message' => trans('adminMessages.branch_inserted')]);
        } else{

            User::where('id', $request->input('dealer_id'))->update([
                'name'      => $request->input('login_user_name'),
                'mobile_no' => $request->input('login_user_mobile_no'),
            ]);

            $country_code = $request->input('country_code');

            $country_list = Country::where('country_code', $country_code)->first();

            if($country_list){
                $country_id = $country_list->id;
            } else{
                $country_order = Country::max('id');
                $code = app('App\CountryCode')->getCountryCode($country_code);
                $country_insert_id = Country::create([
                    'code'          => $code,
                    'country_code'  => $country_code,
                    'country_order' => $country_order + 1,
                ]);


                $languages = Language::all();
                foreach($languages as $language){
                    CountryTranslation::create([
                        'name'       => $request->input('country'),
                        'country_id' => $country_insert_id->id,
                        'locale'     => $language->language_code,
                    ]);
                }


                $country_id = $country_insert_id->id;
            }
            if($request->hasFile('branch_logo')){
                $files = $request->file('branch_logo');
                $branch_logo = ImageUploadHelper::imageUpload($files);
            } else{
                $branch_logo = CompanyAddress::where('id', $id)->first()->branch_logo;
            }
            CompanyAddress::where('id', $id)->update([
                'branch_name'         => $request->input('branch_name'),
                'branch_logo'         => $branch_logo,
                'bank_account'        => $request->input('bank_account'),
                'trading_license'     => $request->input('trading_license'),
                'allowed_millage'     => $request->input('allowed_millage'),
                'phone_no'            => $request->input('phone_no'),
                'address'             => $request->input('address'),
                'branch_contact_name' => $request->input('branch_contact_name'),
                'latitude'            => $request->input('latitude'),
                'longitude'           => $request->input('longitude'),
                'country_id'          => $country_id,
                'service_distance'    => $request->input('service_distance'),
            ]);

            $extras = $request->input('extra');
            $extra_ids = $request->input('extra_ids');

            if($extras != NULL){
                foreach($extras as $key => $extra){

                    $branch_old_data_check = BranchExtra::where('company_address_id', $id)
                        ->where('global_extra_id', $extra_ids[$key])
                        ->first();

                    if($branch_old_data_check){
                        $branch_extra = BranchExtra::find($branch_old_data_check->id);
                        $branch_extra->company_address_id = $id;
                        $branch_extra->global_extra_id = $extra_ids[$key];
                        $branch_extra->type = $extra;
                        $branch_extra->save();

                    }
                }
            }

            CompanyTime::where('company_address_id', $id)->delete();
            if($request->input('day_no') != NULL){
                foreach($request->input('day_no') as $key => $value){
                    $day_value = $request->input('day_value')[$key];
                    $day_value = $request->input('day_value')[$key];
                    $shift_two_day_value = $request->input('shift_two_day_value')[$key];
                    if($day_value == 1 && $shift_two_day_value == 1){
                        CompanyTime::create([
                            'company_address_id' => $id,
                            'day_no'             => $value,
                            'sift1_start_time'   => $request->input('weekStartArray')[$key],
                            'sift1_end_time'     => $request->input('weekEndArray')[$key],
                            'sift2_start_time'   => $request->input('shiftTwoweekStartArray')[$key],
                            'sift2_end_time'     => $request->input('shiftTwoWeekEndArray')[$key],
                        ]);
                    }
                    if($day_value == 1 && $shift_two_day_value == 0){
                        CompanyTime::create([
                            'company_address_id' => $id,
                            'day_no'             => $value,
                            'sift1_start_time'   => $request->input('weekStartArray')[$key],
                            'sift1_end_time'     => $request->input('weekEndArray')[$key],
                        ]);
                    }
                    if($day_value == 0 && $shift_two_day_value == 1){
                        CompanyTime::create([
                            'company_address_id' => $id,
                            'day_no'             => $value,
                            'sift2_start_time'   => $request->input('shiftTwoweekStartArray')[$key],
                            'sift2_end_time'     => $request->input('shiftTwoWeekEndArray')[$key],
                        ]);
                    }
                }
            }
            return response()->json(['success' => true, 'message' => trans('adminMessages.branch_updated')]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * Edit Branch Page
     */
    public function edit($id)
    {
        $branch_details = CompanyAddress::with('companies', 'users', 'companyTime', 'country')
            ->where('id', $id)->first();


        if($branch_details){
            if($branch_details->user_id == Auth::user()->id || Session::get('company_id') == $branch_details->company_id){
                $weeks = [
                    'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday',
                ];
                $global_extras = GlobalExtra::all();
                $extras = BranchExtra::where('company_address_id', $id)->get();
                $dealer_extras = [];
                foreach($extras as $extra){
                    $dealer_extras[$extra->global_extra_id] = $extra->type;
                }

                return view('dealer.branch.edit', [
                    'branch_id'      => $id,
                    'weeks'          => $weeks,
                    'branch_details' => $branch_details,
                    'dealer_id'      => Auth::user()->id,
                    'global_extras'  => $global_extras,
                    'dealer_extras'  => $dealer_extras,
                ]);
            } else{
                abort(404);
            }
        } else{
            abort(404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * Delete Branch
     */
    public function destroy($id)
    {

        Booking::where('company_address_id', $id)->delete();
        CompanyTime::where('company_address_id', $id)->delete();
        Commission::where('company_address_id', $id)->delete();
        BranchExtra::where('company_address_id', $id)->delete();
        $vehicles = Vehicle::where('company_address_id', $id)->get();
        foreach($vehicles as $vehicle){
            VehicleExtra::where('vehicle_id', $vehicle->id)->delete();
            VehicleFeature::where('vehicle_id', $vehicle->id)->delete();
            VehicleOption::where('vehicles_id', $vehicle->id)->delete();
            CategoryVehicle::where('vehicle_id', $vehicle->id)->delete();
            VehicleNotAvailable::where('vehicle_id', $vehicle->id)->delete();
        }
        Vehicle::where('company_address_id', $id)->delete();
        CompanyAddress::where('id', $id)->delete();
        return response()->json(['success' => true, 'message' => trans('adminMessages.branch_inserted')]);
    }
}
