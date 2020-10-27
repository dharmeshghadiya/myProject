<?php

namespace App\Http\Controllers\Dealer;

use App\Models\Booking;
use App\Models\BranchExtra;
use App\Models\Brand;
use App\Models\ReportModel;
use App\Models\CategoryVehicle;
use App\Models\GlobalExtra;
use App\Models\Option;
use App\Models\ModelYear;
use App\Models\Company;
use App\Models\CompanyAddress;
use App\Models\Engine;
use App\Models\Feature;
use App\Models\Fuel;
use App\Models\Gearbox;
use App\Models\Color;
use App\Models\Insurance;
use App\Models\Vehicle;
use App\Models\Ryde;
use App\Models\VehicleExtra;
use App\Models\VehicleFeature;
use App\Models\VehicleNotAvailable;
use App\Models\VehicleOption;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;


class MainRydeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\App\Modelslication|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * View Ryde Vehicle List
     */
    public function index(Request $request)
    {

        if($request->ajax()){
            $company_address_id = $request->input('company_address_id');
            $color = $request->input('color');
            $year = $request->input('year');
            $make = $request->input('make');
            $filter = $request->input('filter');
            // DB::enableQueryLog();

            $vehicles = Vehicle::where(['company_id' => Session('company_id')]);

            if($filter != null){

                if($company_address_id != null){
                    $vehicles = $vehicles->where('company_address_id', $company_address_id);
                }

                $vehicles = $vehicles->with([
                    'companyAddress',
                    'ryde' => function($q){
                        $q->with('brand', 'modelYear', 'color');
                    },
                ])->whereHas('ryde', function($query) use ($make, $color, $year){
                    if($make != null){
                        $query->where('brand_id', $make);
                    }
                    if($color != null){
                        $query->where('color_id', $color);
                    }
                    if($year != null){
                        $query->where('model_year_id', $year);
                    }
                });
            } else{
                $vehicles = $vehicles->with([
                    'companyAddress',
                    'ryde' => function($query){
                        $query->with('brand', 'modelYear', 'color');
                    },
                ]);
            }

            $vehicles = $vehicles->get();
            //dd($vehicles);
            //dd(DB::getQueryLog());
            //dd($vehicles);
            return Datatables::of($vehicles)
                ->addColumn('address', function($vehicles){
                    return $vehicles->companyAddress->address . '(' . $vehicles->companyAddress->service_distance . ' KM)';
                })
                ->addColumn('make', function($vehicles){
                    $brandName = '';
                    if(!empty($vehicles->ryde->brand->name)){
                        $brandName = $vehicles->ryde->brand->name;
                    }
                    return $brandName;
                })
                ->addColumn('model', function($vehicles){
                    $modelName = '';
                    if(!empty($vehicles->ryde->name)){
                        $modelName = $vehicles->ryde->name;
                    }
                    return $modelName;
                })
                ->addColumn('modelYear', function($vehicles){
                    $modelYears = '';
                    if(!empty($vehicles->ryde->modelYear->name)){
                        $modelYears = $vehicles->ryde->modelYear->name;
                    }
                    return $modelYears;
                })
                ->addColumn('color', function($vehicles){
                    $colorName = '';
                    if(!empty($vehicles->ryde->color->name)){

                        $colorName = $vehicles->ryde->color->name;
                    }
                    return $colorName;
                })
                ->addColumn('status', function($vehicles){
                    $checkBooking=Booking::where('vehicle_id',$vehicles->id)
                        ->whereRaw("? BETWEEN start_date AND end_date",[date('Y-m-d H:i:s')])
                        ->count();
                    $checkAvailable=VehicleNotAvailable::where('vehicle_id',$vehicles->id)
                        //->whereRaw("? BETWEEN start_date AND end_date",[date('Y-m-d H:i:s')])
                        ->count();
                    if($checkBooking==1){
                        $status = '<span class=" badge badge-info">' . config('languageString.booked') . '</span>';
                    }
                    else if($checkAvailable==1){
                        $status = '<span class=" badge badge-warning">' . config('languageString.maintenance') . '</span>';
                    }
                    else if($vehicles->status == 'Active'){
                        $status = '<span class=" badge badge-success">' . config('languageString.active') . '</span>';
                    }else if($vehicles->status == 'Sold'){
                        $status = '<span class=" badge badge-success">' . config('languageString.sold') . '</span>';
                    }

                    else if($vehicles->status == 'Retired'){
                        $status = '<span class=" badge badge-warning">' . config('languageString.retired') . '</span>';
                    }

                    return $status;
                })
                ->addColumn('action', function($vehicles){
                    $checkBooking=Booking::where('vehicle_id',$vehicles->id)
                        ->whereRaw("? BETWEEN start_date AND end_date",[date('Y-m-d H:i:s')])
                        ->count();
                    $checkAvailable=VehicleNotAvailable::where('vehicle_id',$vehicles->id)
                        //->whereRaw("? BETWEEN start_date AND end_date",[date('Y-m-d H:i:s')])
                        ->count();
                    $sold_button = ''; $retire_button = ''; $delete_button = ''; $status_button = '';
                    $edit_button = '<a href="' . route('dealer::ryde.edit', [$vehicles->company_address_id, $vehicles->id]) . '" class="btn btn-icon btn-info" data-toggle="tooltip" data-placement="top" title="' . config('languageString.edit') . '"><i class="bx bx-pencil font-size-16 align-middle"></i></a>';
                    $view_detail_button = '<button data-id="' . $vehicles->id . '" class="vehicle-details btn btn-icon btn-secondary" data-effect="effect-fall" data-toggle="tooltip" data-placement="top" title="' . config('languageString.view_details') . '"><i class="bx bx-bullseye font-size-16 align-middle"></i></button>';


                    $vehicle_not_available = '';
                    if($checkAvailable==0 && $checkBooking==0 && $vehicles->status == 'Active'){
                        $status = 'Sold';
                        $translate_status = config('languageString.sold');
                        $sold_button = '<button data-id="' . $vehicles->id . '" data-status="' . $status . '" class="sold btn btn-sm btn btn-icon btn-primary waves-effect waves-light" data-effect="effect-fall" data-toggle="tooltip" data-placement="top" title="' . $translate_status . '" ><i class="bx bxs-cart-download"></i></button>';
                        $retired_status = 'Retired';
                        $retire_translate_status = config('languageString.retired');
                        //$delete_button = '<button data-id="' . $vehicles->id . '" class="delete-single btn btn-icon btn-danger" data-toggle="tooltip" data-placement="top" title="' . config('languageString.delete') . '"><i class="bx bx-trash font-size-16 align-middle"></i></button>';
                        $retire_button = '<button data-id="' . $vehicles->id . '" data-status="' . $retired_status . '" class="status-change btn btn-sm btn btn-icon btn-dark waves-effect waves-light" data-effect="effect-fall" data-toggle="tooltip" data-placement="top" title="' . $retire_translate_status . '" ><i class="fa fa-stop-circle font-size-16 align-middle"></i></button>';
                    }if($checkBooking==0 && $vehicles->status == 'Active'){
                        $vehicle_not_available = '<a href="' . route('dealer::vehicleNotAvailable', [$vehicles->id]) . '" class="btn btn-icon btn-info" data-toggle="tooltip" data-placement="top" title="' . config('languageString.ryde_not_availability') . '"><i class="bx bx-error-circle"></i></a>';
                    }

                    $duplicat_button = '<button data-id="' . $vehicles->id . '"  class="duplicate-record btn btn-icon btn-warning" data-effect="effect-fall" data-toggle="tooltip" data-placement="top" title="' . 'Duplicate' . '" ><i class="bx bx-merge font-size-16 align-middle"></i></button>';

                    return '<div class="btn-icon-list">' . $duplicat_button . ' ' . $edit_button . ' ' . $delete_button . ' ' . $view_detail_button . ' ' . $status_button . ' ' . $vehicle_not_available .' '. $sold_button.' '.$retire_button.'</div>';
                })
                ->rawColumns(['action', 'status', 'vehicle_not_available'])
                ->make(true);
        }

        //$company_address = CompanyAddress::where('id', $id)->first();
        $companyAddresses = CompanyAddress::where('company_id', Session('company_id'))->get();
        $colors = Color::all();
        $years = ModelYear::all();
        $makes = Brand::all();

        return view('dealer.mainRyde.index',
            [
                'companyAddresses' => $companyAddresses,
                'years'            => $years,
                'makes'            => $makes,
                'colors'           => $colors,
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param $branch_id
     * @return \Illuminate\Contracts\Foundation\App\Modelslication|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * Add Ryde Vehicle Page
     */
    public function create()
    {
        $makes = Brand::all();
        $features = Feature::all();
        $engines = Engine::all();
        $gearboxes = Gearbox::all();
        $fuels = Fuel::all();
        $modelYears = ModelYear::all();
        $options = Option::all();
        $colors = Color::all();
        $insurances = Insurance::all();
        $companyAddresses = CompanyAddress::where('company_id', Session('company_id'))->get();
        $company = Company::where('id', Session('company_id'))->first();


        return view('dealer.mainRyde.create', [
            'makes'            => $makes,
            'modelYears'       => $modelYears,
            'options'          => $options,
            'features'         => $features,
            'colors'           => $colors,
            'insurances'       => $insurances,
            'companyAddresses' => $companyAddresses,
            'is_owner'         => Session('is_owner'),
            'engines'          => $engines,
            'gearboxes'        => $gearboxes,
            'fuels'            => $fuels,
            'company'          => $company,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * Add Ryde Vehicle Details
     */
    public function store(Request $request)
    {
        $id = $request->input('edit_value');

        if($id == NULL){
            $validator_array = [
                'hourly_amount'  => 'required',
                'weekly_amount'  => 'required',
                'daily_amount'   => 'required',
                'monthly_amount' => 'required',
                'ryde_id'        => 'required',
                'color'          => 'required',
                'insurance'      => 'required',
                'van_number'     => 'required',
                'plate_number'   => 'required',
                'engine'         => 'required',
                'gearbox'        => 'required',
                'fuel'           => 'required',
            ];

            $duplicate_ryde = Vehicle::where([
                'plate_number' => $request->plate_number,
            ])->first();
        } else{
            $validator_array = [
                'hourly_amount'  => 'required',
                'weekly_amount'  => 'required',
                'daily_amount'   => 'required',
                'monthly_amount' => 'required',
                'ryde_id'        => 'required',
                'color'          => 'required',
                'insurance'      => 'required',
                'van_number'     => 'required',
                'plate_number'   => 'required',
                'engine'         => 'required',
                'gearbox'        => 'required',
                'fuel'           => 'required',
            ];

            $duplicate_ryde = Vehicle::where([
                'plate_number' => $request->plate_number,
            ])->where('id', '!=', $id)->first();
        }


        $validator = Validator::make($request->all(), $validator_array);
        if($validator->fails()){
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }

        if($duplicate_ryde){
            return response()->json(['success' => false, 'message' => config('languageString.duplicate_plate_number')]);
        } else{
            if($id == NULL){
                $insert_id = Vehicle::create([
                    'company_id'         => Session('company_id'),
                    'company_address_id' => $request->input('company_address_id'),
                    'vehicle_type_id'    => 1,
                    'hourly_amount'      => $request->input('hourly_amount'),
                    'daily_amount'       => $request->input('daily_amount'),
                    'weekly_amount'      => $request->input('weekly_amount'),
                    'monthly_amount'     => $request->input('monthly_amount'),
                    'security_deposit'   => $request->input('security_deposit'),
                    'insurance_id'       => $request->input('insurance'),
                    'ryde_id'            => $request->input('ryde_id'),
                    'engine_id'          => $request->input('engine'),
                    'fuel_id'            => $request->input('fuel'),
                    'gearbox_id'         => $request->input('gearbox'),
                    'trim'               => $request->input('trim'),
                    'van_number'         => $request->input('van_number'),
                    'plate_number'       => $request->input('plate_number'),
                    'buy_price'          => $request->input('buy_price'),
                    'allowed_millage'    => $request->input('allowed_millage'),
                    'cost_per_extra_km'  => $request->input('cost_per_extra_km'),
                    'status'             => 'Active',
                ]);


                $extras = $request->input('extra');
                $extra_ids = $request->input('extra_ids');
                $price = $request->input('price');
                foreach($extras as $key => $extra){
                    VehicleExtra::create([
                        'vehicle_id'      => $insert_id->id,
                        'global_extra_id' => $extra_ids[$key],
                        'type'            => $extra,
                        'price'           => $price[$key],
                    ]);
                }

                if($request->input('option') != NULL){
                    foreach($request->input('option') as $value){
                        VehicleOption::create([
                            'vehicles_id' => $insert_id->id,
                            'option_id'   => $value,
                        ]);
                    }
                }

                if($request->input('featured') != NULL){
                    foreach($request->input('featured') as $key => $value){
                        VehicleFeature::create([
                            'vehicle_id' => $insert_id->id,
                            'feature_id' => $value,
                        ]);

                    }
                }

                return response()->json([
                    'success'   => true,
                    'message'   => config('languageString.ryde_inserted'),
                    'branch_id' => $request->input('company_address_id'),
                ]);
            } else{
                Vehicle::where('id', $id)->update([
                    'hourly_amount'     => $request->input('hourly_amount'),
                    'daily_amount'      => $request->input('daily_amount'),
                    'weekly_amount'     => $request->input('weekly_amount'),
                    'monthly_amount'    => $request->input('monthly_amount'),
                    'security_deposit'  => $request->input('security_deposit'),
                    'insurance_id'      => $request->input('insurance'),
                    'ryde_id'           => $request->input('ryde_id'),
                    'engine_id'         => $request->input('engine'),
                    'fuel_id'           => $request->input('fuel'),
                    'gearbox_id'        => $request->input('gearbox'),
                    'trim'              => $request->input('trim'),
                    'van_number'        => $request->input('van_number'),
                    'plate_number'      => $request->input('plate_number'),
                    'buy_price'         => $request->input('buy_price'),
                    'allowed_millage'   => $request->input('allowed_millage'),
                    'cost_per_extra_km' => $request->input('cost_per_extra_km'),
                ]);


                $extras = $request->input('extra');
                $extra_ids = $request->input('extra_ids');
                $price = $request->input('price');

                foreach($extras as $key => $extra){
                    $vehicle_extra = VehicleExtra::where('global_extra_id', $extra_ids[$key])->where('vehicle_id', $id)->first();
                    if($vehicle_extra){
                        $branch_extra = VehicleExtra::find($vehicle_extra->id);
                        $branch_extra->vehicle_id = $id;
                        $branch_extra->global_extra_id = $extra_ids[$key];
                        $branch_extra->price = $price[$key];
                        $branch_extra->type = $extra;
                        $branch_extra->save();
                    }
                }

                if($request->input('option') == NULL){
                    VehicleOption::where('vehicles_id', $id)->delete();
                } else{
                    $results = VehicleOption::where('vehicles_id', $id)->whereNotIn('option_id', $request->input('option'))->get();

                    foreach($results as $result){
                        VehicleOption::where('id', $result->id)->delete();
                    }

                    if($request->input('option') != NULL){
                        foreach($request->input('option') as $key => $value){
                            if(VehicleOption::where('option_id', $value)->where('vehicles_id', $id)->count() == 0){
                                VehicleOption::create([
                                    'vehicles_id' => $id,
                                    'option_id'   => $value,
                                ]);
                            }

                        }
                    }
                }

                if($request->input('featured') == NULL){
                    VehicleFeature::where('vehicle_id', $id)->delete();
                } else{
                    $results = VehicleFeature::where('vehicle_id', $id)->whereNotIn('feature_id', $request->input('featured'))->get();
                    foreach($results as $result){
                        VehicleFeature::where('id', $result->id)->delete();
                    }
                    if($request->input('featured') != NULL){
                        foreach($request->input('featured') as $key => $value){
                            if(VehicleFeature::where('feature_id', $value)->where('vehicle_id', $id)->count() == 0){
                                VehicleFeature::create([
                                    'vehicle_id' => $id,
                                    'feature_id' => $value,
                                ]);
                            }

                        }
                    }
                }

                return response()->json([
                    'success'   => true,
                    'message'   => trans('adminMessages.vehicle_updated'),
                    'branch_id' => $request->input('company_address_id'),
                ]);
            }

        }


    }

    /*
     * Create Duplicate Record of Vehicle
     */
    public function duplicateRecord($id)
    {

        $vehicle = Vehicle::where('id', $id)->first();

        $insert_id = Vehicle::create([
            'company_id'         => $vehicle->company_id,
            'company_address_id' => $vehicle->company_address_id,
            'vehicle_type_id'    => 1,
            'hourly_amount'      => $vehicle->hourly_amount,
            'daily_amount'       => $vehicle->daily_amount,
            'weekly_amount'      => $vehicle->weekly_amount,
            'monthly_amount'     => $vehicle->monthly_amount,
            'security_deposit'   => $vehicle->security_deposit,
            'insurance_id'       => $vehicle->insurance_id,
            'ryde_id'            => $vehicle->ryde_id,
            'engine_id'          => $vehicle->engine_id,
            'fuel_id'            => $vehicle->fuel_id,
            'gearbox_id'         => $vehicle->gearbox_id,
            'trim'               => $vehicle->trim,
            'van_number'         => $vehicle->van_number,
            'plate_number'       => $vehicle->plate_number,
            'buy_price'          => $vehicle->buy_price,
            'allowed_millage'    => $vehicle->allowed_millage,
            'cost_per_extra_km'  => $vehicle->cost_per_extra_km,
            'status'             => 'Active',
        ]);

        $extras = VehicleExtra::where('vehicle_id', $id)->get();

        foreach($extras as $extra){
            VehicleExtra::create([
                'vehicle_id'      => $insert_id->id,
                'global_extra_id' => $extra->global_extra_id,
                'type'            => $extra->type,
                'price'           => $extra->price,
            ]);
        }

        $vehicleFeatures = VehicleFeature::where('vehicle_id', $id)->get();
        foreach($vehicleFeatures as $vehicleFeature){
            VehicleFeature::create([
                'vehicle_id' => $insert_id->id,
                'feature_id' => $vehicleFeature->feature_id,
            ]);

        }


        return response()->json([
            'success'   => true,
            'message'   => trans('adminMessages.duplicate_inserted'),
            'branch_id' => $vehicle->company_address_id,
        ]);

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
     * @param $branch_id
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\App\Modelslication|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * Edit Ryde Vehicle Detail Page
     */
    public function edit($id)
    {
        $vehicle = Vehicle::with('companyAddress', 'vehicleExtra', 'vehicleFeature', 'ryde', 'vehicleOptions')->find($id);

        if($vehicle){
            $makes = Brand::all();
            $features = Feature::all();
            $engines = Engine::all();
            $gearboxes = Gearbox::all();
            $fuels = Fuel::all();
            $modelYears = ModelYear::all();
            $options = Option::all();
            $colors = Color::all();
            $insurances = Insurance::all();
            $global_extras = GlobalExtra::all();
            $extras = VehicleExtra::where('vehicle_id', $id)->get();
            $companyAddresses = CompanyAddress::where('company_id', Session('company_id'))->get();
            $vehicle_extra = $vehicle_extra_price = [];
            foreach($extras as $extra){
                $vehicle_extra[$extra->global_extra_id] = $extra->type;
                $vehicle_extra_price[$extra->global_extra_id] = $extra->price;
            }

            $brand_models = Ryde::where('brand_id', $vehicle->ryde->brand_id)->where('model_year_id', $vehicle->ryde->model_year_id)->where('color_id', $vehicle->ryde->color_id)->get();

            return view('dealer.mainRyde.edit', [
                'vehicle'             => $vehicle,
                'options'             => $options,
                'makes'               => $makes,
                'modelYears'          => $modelYears,
                'features'            => $features,
                'colors'              => $colors,
                'insurances'          => $insurances,
                'branch_id'           => '',
                'brand_models'        => $brand_models,
                'engines'             => $engines,
                'gearboxes'           => $gearboxes,
                'fuels'               => $fuels,
                'global_extras'       => $global_extras,
                'vehicle_extra'       => $vehicle_extra,
                'vehicle_extra_price' => $vehicle_extra_price,
                'companyAddresses'    => $companyAddresses,
            ]);
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
     * Delete Ryde Vehicle
     */
    public function destroy($id)
    {
        Vehicle::where('id', $id)->delete();
        VehicleExtra::where('vehicle_id', $id)->delete();
        CategoryVehicle::where('vehicle_id', $id)->delete();
        VehicleNotAvailable::where('vehicle_id', $id)->delete();
        VehicleFeature::where('vehicle_id', $id)->delete();
        VehicleOption::where('vehicles_id', $id)->delete();
        Booking::where('vehicle_id', $id)->delete();

        return response()->json(['success' => true, 'message' => trans('adminMessages.vehicle_deleted')]);
    }

    /*
     * Change Ryde Vehicle Status
     */
    public function changeStatus($id, $status)
    {
        Vehicle::where('id', $id)->update(['status' => $status]);
        return response()->json(['success' => true, 'message' => trans('adminMessages.vehicle_status')]);
    }

    /*
     * add vehicle for not available
     */
    public function vehicleNotAvailable($id)
    {
        $vehicle = Vehicle::with('companyAddress')->where('id', $id)->first();
        $vehicleNotAvailability = VehicleNotAvailable::where('vehicle_id', $id)->get();

        if($vehicle){
            return view('dealer.vehicle.vehicleNotAvailable', [
                'vehicle_id'             => $id,
                'vehicle'                => $vehicle,
                'vehicleNotAvailability' => $vehicleNotAvailability,
            ]);
        } else{
            abort(404);
        }
    }

    /*
     * update vehicle for not available
     */
    public function UpdateVehicleNotAvailable(Request $request)
    {
        $validator_array = [
            'start_date' => 'required',
            'end_date'   => 'required',

        ];
        $validator = Validator::make($request->all(), $validator_array);
        if($validator->fails()){
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }

        $start_date = $request->input('start_date');

        $end_date = $request->input('end_date');

        if($start_date > $end_date){
            return response()->json(['success' => false, 'message' => trans('adminMessages.check_date')]);
        }

        $duplicate = VehicleNotAvailable::where(['start_date' => $start_date, 'end_date' => $end_date])->first();
        if($duplicate){

            return response()->json(['success' => false, 'message' => trans('adminMessages.vehicleNotAvailable_duplicate')]);

        } else{


            $check_exist = VehicleNotAvailable::where(['vehicle_id' => $request->input('id')])->first();
            if($check_exist){

                VehicleNotAvailable::where('vehicle_id', $request->input('id'))->update([
                    'start_date'  => $start_date,
                    'end_date'    => $end_date,
                    'description' => $request->description,
                ]);
                $vehicleNotAvailability = VehicleNotAvailable::with('vehicle')->get();


                return response()->json(['success' => true, 'message' => trans('adminMessages.vehicleNotAvailable_updated'), 'id' => $request->input('id')]);

            } else{

                VehicleNotAvailable::create([
                    'vehicle_id'  => $request->input('id'),
                    'start_date'  => $start_date,
                    'end_date'    => $end_date,
                    'description' => $request->description,
                ]);


                return response()->json(['success' => true, 'message' => trans('adminMessages.vehicleNotAvailable_inserted'), 'id' => $request->input('id')]);
            }
        }
    }

    /*
     * delete vehicle not available
     */
    public function vehicleNotAvailableDelete($id, $vehicle_id)
    {

        VehicleNotAvailable::where('id', $id)->delete();
        return response()->json(['success' => true, 'message' => trans('adminMessages.vehicleNotAvailable_deleted'), 'id' => $vehicle_id]);
    }

    /*
     * get branch list of company
     */
    public function getBranch(Request $request)
    {
        $company_id = Session('company_id');

        $company_addresses = CompanyAddress::where('company_id', $company_id)->get();
        echo '<option value="">Please Select Branch</option>';
        foreach($company_addresses as $company_address){
            echo "<option value='" . $company_address->id . "'>" . $company_address->address . "</option>";
        }

    }

    /*
     * get ryde list of models
     */
    public function getRyde(Request $request)
    {
        $brand_id = $request->brand_id;
        $color_id = $request->color_id;
        $year_id = $request->year_id;
        $model_id = $request->model_id;

        if(!empty($brand_id) && !empty($model_id) && !empty($year_id) && !empty($color_id)){
            $rydes = Ryde::where('id', $model_id)->with('rydeInstance')->first();

            if($rydes){
                return view('dealer.vehicle.rydeShow', [
                    'id'          => $rydes->id,
                    'model_image' => $rydes->image,
                    'body'        => $rydes->rydeInstance->body->name,
                    'door'        => $rydes->rydeInstance->door->name,
                    'seats'       => $rydes->rydeInstance->seats,
                ]);

            } else{
                return view('dealer.vehicle.rydeShow');
            }
        }
    }

    public function getExtrasAndSecurityDeposit(Request $request)
    {
        $global_extras = GlobalExtra::all();
        $company_details = Company::where('id', Session('company_id'))->first();
        if(!empty($request->vehicle_id)){
            $extras = VehicleExtra::where('vehicle_id', $request->vehicle_id)->get();
        } else{
            $extras = BranchExtra::where('company_address_id', $request->company_address_id)->get();
        }

        $branch_extras = [];
        foreach($extras as $extra){
            $branch_extras[$extra->global_extra_id] = $extra->type;
        }

        return view('dealer.mainRyde.extraShow', [
            'global_extras' => $global_extras,
            'branch_extras' => $branch_extras,
        ]);

    }

    /*
     * show Vehicle Details
     */
    public function vehicleDetails($id)
    {
        $vehicle = Vehicle::where('id', $id)
            ->with([
                'ryde' => function($query){
                    $query->with('brand', 'modelYear', 'color');
                },
                'vehicleOptions',
            ])->first();

        $array['globalModalTitle'] = config('languageString.rydes') . ': ' . $vehicle->ryde->brand->name . ' | ' . $vehicle->ryde->name . ' | ' . $vehicle->ryde->modelYear->name;

        $array['globalModalDetails'] = '<table class="table table-bordered">';
        $array['globalModalDetails'] .= '<thead class="thead-light"><tr><th colspan="6" class="text-center">' . config('languageString.ryde_details') . '</th></tr></thead>';
        $array['globalModalDetails'] .= '<thead class="thead-dark"><tr><th>' . config('languageString.color') . '</th><th>' . config('languageString.hourly_amount') . '
        </th><th>' . config('languageString.daily_amount') . '</th><th>' . config('languageString.weekly_amount') . '</th><th>' . config('languageString.monthly_amount') . '</th></tr></thead>';
        $array['globalModalDetails'] .= '<tr>';
        $array['globalModalDetails'] .= '<td>' . $vehicle->ryde->color->name . '</td>';
        $array['globalModalDetails'] .= '<td>' . '$ ' . $vehicle->hourly_amount . '</td>';
        $array['globalModalDetails'] .= '<td>' . '$ ' . $vehicle->daily_amount . '</td>';
        $array['globalModalDetails'] .= '<td>' . '$ ' . $vehicle->weekly_amount . '</td>';
        $array['globalModalDetails'] .= '<td>' . '$ ' . $vehicle->monthly_amount . '</td>';
        $array['globalModalDetails'] .= '</tr>';
        $array['globalModalDetails'] .= '</table>';


        if(isset($vehicle->engine->name) && isset($vehicle->gearbox->name) && isset($vehicle->fuel->name)){
            $array['globalModalDetails'] .= '<table class="table table-bordered">';
            $array['globalModalDetails'] .= '<thead class="thead-light"><tr><th colspan="6" class="text-center">' . config('languageString.ryde_details') . '</th></tr></thead>';
            $array['globalModalDetails'] .= '<thead class="thead-dark"><tr><th>' . config('languageString.engine') . '</th><th>' . config('languageString.gearbox') . '
        </th><th>' . config('languageString.fuel') . '</th><th>' . config('languageString.van_number') . '</th><th>' . config('languageString.plate_number') . '</th><th>' . config('languageString.trim') . '</th></tr></thead>';
            $array['globalModalDetails'] .= '<tr>';
            $array['globalModalDetails'] .= '<td>' . $vehicle->engine->name . '</td>';
            $array['globalModalDetails'] .= '<td>' . $vehicle->gearbox->name . '</td>';
            $array['globalModalDetails'] .= '<td>' . $vehicle->fuel->name . '</td>';
            $array['globalModalDetails'] .= '<td>' . $vehicle->van_number . '</td>';
            $array['globalModalDetails'] .= '<td>' . $vehicle->plate_number . '</td>';
            $array['globalModalDetails'] .= '<td>' . $vehicle->trim . '</td>';
            $array['globalModalDetails'] .= '</tr>';
            $array['globalModalDetails'] .= '</table>';
        }


        $array['globalModalDetails'] .= '<table class="table table-bordered">';
        $array['globalModalDetails'] .= '<thead class="thead-light"><tr><th colspan="6" class="text-center">' . config('languageString.ryde_extra') . '</th></tr></thead>';
        $array['globalModalDetails'] .= '<thead class="thead-dark"><tr><th>' . config('languageString.extras') . '</th><th>' . config('languageString.price') . '</th></tr></thead>';
        foreach($vehicle->vehicleExtra as $vehicleExtra){
            $array['globalModalDetails'] .= '<tr>';
            $array['globalModalDetails'] .= '<td> ' . $vehicleExtra->extra->name . '</td>';
            $array['globalModalDetails'] .= '<td> $' . $vehicleExtra->price . '</td>';
            $array['globalModalDetails'] .= '</tr>';
        }
        $array['globalModalDetails'] .= '</table>';

        $url = asset($vehicle->ryde->image);

        $array['globalModalDetails'] .= "<img src='" . $url . "' />";
        $array['globalModalDetails'] .= '<table class="table table-bordered">';
        $array['globalModalDetails'] .= '<thead class="thead-light"><tr><th colspan="3" class="text-center">' . config('languageString.ryde_feature') . '</th></tr></thead>';

        foreach($vehicle->vehicleOptions as $option){
            $array['globalModalDetails'] .= '<tr>';
            $array['globalModalDetails'] .= '<td colspan="3">' . $option->option->name . '</td>';
            $array['globalModalDetails'] .= '</tr>';
        }
        $array['globalModalDetails'] .= '</table>';


        return response()->json(['success' => true, 'data' => $array]);
    }

    /*
     * Get Models List of specific year color and brand
     */
    public function getModel(Request $request)
    {
        $brand_id = $request->input('brand_id');
        $year_id = $request->input('year_id');
        $color_id = $request->input('color_id');

        $rydes = Ryde::where('brand_id', $brand_id)->where('model_year_id', $year_id)->where('color_id', $color_id)->get();
        if(count($rydes) > 0){
            echo "<option value=''>Please Select Model</option>";
            foreach($rydes as $ryde){
                echo "<option value='" . $ryde->id . "'>" . $ryde->name . "</option>";
            }
        } else{
            echo "<option value=''>No Model Found</option>";
        }
    }

    public function addReportModel(Request $request)
    {

        $validator_array = [
            'make_id'       => 'required',
            'model_year_id' => 'required',
            'toYearId'      => 'required',
            'color_id'      => 'required',
        ];

        $validator = Validator::make($request->all(), $validator_array);
        if($validator->fails()){
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }

        $ReportModel = ReportModel::where([
            'company_id'    => Session('company_id'),
            'brand_id'      => $request->input('make_id'),
            'model_year_id' => $request->input('model_year_id'),
            'to_year_id'    => $request->input('toYearId'),
            'color_id'      => $request->input('color_id'),
        ])->first();

        if($ReportModel){
            return response()->json(['success' => false, 'message' => trans('adminMessages.report_model_duplicate')]);
        }

        ReportModel::create([
            'company_id'    => Session('company_id'),
            'brand_id'      => $request->input('make_id'),
            'model_year_id' => $request->input('model_year_id'),
            'to_year_id'    => $request->input('toYearId'),
            'color_id'      => $request->input('color_id'),
            'message'       => $request->input('message'),
        ]);

        return response()->json(['success' => true, 'message' => trans('adminMessages.report_model_successfuly_sent')]);
    }

}
