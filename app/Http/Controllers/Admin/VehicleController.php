<?php

namespace App\Http\Controllers\Admin;


use App\Body;
use App\Booking;
use App\BookingDetails;
use App\BranchExtra;
use App\Brand;
use App\BrandModel;
use App\CategoryVehicle;
use App\GlobalExtra;
use App\Option;
use App\RideExtra;
use App\RydeInstance;
use App\ModelYear;
use App\Company;
use App\CompanyAddress;
use App\Door;
use App\Engine;
use App\Extra;
use App\Feature;
use App\Fuel;
use App\Gearbox;
use App\Color;
use App\Insurance;
use App\Language;
use App\VehicleOption;
use App\Vehicle;
use App\Ryde;
use App\VehicleAttribute;
use App\VehicleExtra;
use App\VehicleFeature;
use App\VehicleNotAvailable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use function foo\func;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, $id = '')
    {

        if($request->ajax()){
            //DB::enableQueryLog();
            $vehicles = Vehicle::where('company_address_id', $request->branch_id)
                ->with([
                    'ryde' => function($query){
                        $query->with('brand', 'modelYear', 'color');
                    },
                ])->get();
            //dd(DB::getQueryLog());
            // dd($vehicles);
            return Datatables::of($vehicles)
                ->addColumn('make', function($vehicles){
                    return $vehicles->ryde->brand->name;
                })
                ->addColumn('model', function($vehicles){
                    return $vehicles->ryde->name;
                })
                ->addColumn('modelYear', function($vehicles){
                    return $vehicles->ryde->modelYear->name;
                })
                ->addColumn('color', function($vehicles){
                    return $vehicles->ryde->color->name;
                })
                ->addColumn('status', function($vehicles){
                    if($vehicles->status == 'Active'){
                        $status = '<span class=" badge badge-primary">' . config('languageString.active') . '</span>';
                    } else{
                        $status = '<span  class=" badge badge-danger">' . config('languageString.in_active') . '</span>';
                    }
                    return $status;
                })
                ->addColumn('action', function($vehicles){
                    $edit_button = '<a href="' . route('dealer::ryde.edit', [$vehicles->company_address_id, $vehicles->id]) . '" class="btn btn-sm btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="' . config('languageString.edit') . '"><i class="bx bx-pencil font-size-16 align-middle"></i></a>';
                    $delete_button = '<button data-id="' . $vehicles->id . '" class="delete-single btn btn-sm btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="' . config('languageString.delete') . '"><i class="bx bx-trash font-size-16 align-middle"></i></button>';
                    $view_detail_button = '<button data-id="' . $vehicles->id . '" class="vehicle-details btn btn-sm btn-outline-primary waves-effect waves-light" data-effect="effect-fall" data-toggle="tooltip" data-placement="top" title="' . config('languageString.view_details') . '"><i class="bx bx-bullseye font-size-16 align-middle"></i></button>';
                    $status = 'Active';
                    $translate_status = config('languageString.active');
                    if($vehicles->status == 'Active'){
                        $status = 'InActive';
                        $translate_status = config('languageString.in_active');
                    }
                    $status_button = '<button data-id="' . $vehicles->id . '" data-status="' . $status . '" class="status-change btn btn-sm btn-outline-success waves-effect waves-light" data-effect="effect-fall" data-toggle="tooltip" data-placement="top" title="' . $translate_status . '" ><i class="bx bx-refresh font-size-16 align-middle"></i></button>';

                    $vehicle_not_available = '';
                    if($vehicles->status == 'Active'){


                        $vehicle_not_available = '<a href="' . route('dealer::vehicleNotAvailable', [$vehicles->id]) . '" class="btn btn-sm btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="' . config('languageString.ryde_not_availability') . '"><i class="bx bx-error-circle"></i></a>';
                    }

                    return $edit_button . ' ' . $delete_button . ' ' . $view_detail_button .' ' . $status_button . ' ' . $vehicle_not_available;
                })
                ->rawColumns(['action', 'status', 'vehicle_not_available'])
                ->make(true);
        }

        $company_address = CompanyAddress::where('id', $id)->first();

        return view('admin.vehicle.index', compact('id', 'company_address'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param $branch_id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($company_id, $branch_id)
    {

        $makes = Brand::all();
        $features = Feature::all();
        $engines = Engine::all();
        $gearboxes = Gearbox::all();
        $fuels = Fuel::all();
        $modelYears = ModelYear::all();
        $colors = Color::all();
        $insurances = Insurance::all();
        $options = Option::all();
        $company_details = CompanyAddress::with('companies')->where('id', $branch_id)->first();
        $global_extras = GlobalExtra::all();

        $extras = BranchExtra::where('company_address_id', $branch_id)->get();
        $branch_extras = [];
        foreach($extras as $extra){
            $branch_extras[$extra->global_extra_id] = $extra->type;
        }
        return view('admin.vehicle.create', [
            'makes'            => $makes,
            'modelYears'       => $modelYears,
            'features'         => $features,
            'colors'           => $colors,
            'insurances'       => $insurances,
            'options'          => $options,
            'company_details'  => $company_details,
            'security_deposit' => $company_details->companies->security_deposit,
            'fuels'            => $fuels,
            'is_owner'         => Session('is_owner'),
            'engines'          => $engines,
            'gearboxes'        => $gearboxes,
            'global_extras'    => $global_extras,
            'branch_extras'    => $branch_extras,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
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
            return response()->json(['success' => false, 'message' => trans('adminMessages.duplicate_plate_number')]);
        } else{

            if($id == NULL){
                $insert_id = Vehicle::create([
                    'company_id'         => $request->input('company_id'),
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
                    'success'    => true,
                    'message'    => trans('adminMessages.vehicle_inserted'),
                    'branch_id'  => $request->input('company_address_id'),
                    'company_id' => $request->input('company_id'),
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
                    'success'    => true,
                    'message'    => trans('adminMessages.vehicle_updated'),
                    'branch_id'  => $request->input('company_address_id'),
                    'company_id' => $request->input('company_id'),
                ]);
            }

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
     * @param $branch_id
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($branch_id, $id)
    {
        $vehicle = Vehicle::with('companyAddress', 'vehicleExtra', 'vehicleFeature', 'ryde', 'vehicleOptions')->find($id);

        if($vehicle){
            $makes = Brand::all();
            $features = Feature::all();
            $engines = Engine::all();
            $gearboxes = Gearbox::all();
            $fuels = Fuel::all();
            $modelYears = ModelYear::all();
            $colors = Color::all();
            $insurances = Insurance::all();
            $options = Option::all();
            $company_details = CompanyAddress::with('companies')
                ->where('id', $branch_id)
                ->first();

            $brand_models = Ryde::where('brand_id', $vehicle->ryde->brand_id)
                ->where('model_year_id', $vehicle->ryde->model_year_id)
                ->where('to_year_id', $vehicle->ryde->to_year_id)
                ->where('color_id', $vehicle->ryde->color_id)
                ->get();
            $global_extras = GlobalExtra::all();
            $extras = VehicleExtra::where('vehicle_id', $id)->get();
            $vehicle_extra = $vehicle_extra_price = [];
            foreach($extras as $extra){
                $vehicle_extra[$extra->global_extra_id] = $extra->type;
                $vehicle_extra_price[$extra->global_extra_id] = $extra->price;
            }


            return view('admin.vehicle.edit', [
                'vehicle'             => $vehicle,
                'makes'               => $makes,
                'modelYears'          => $modelYears,
                'features'            => $features,
                'colors'              => $colors,
                'options'             => $options,
                'insurances'          => $insurances,
                'branch_id'           => $branch_id,
                'brand_models'        => $brand_models,
                'company_details'     => $company_details,
                'engines'             => $engines,
                'gearboxes'           => $gearboxes,
                'fuels'               => $fuels,
                'global_extras'       => $global_extras,
                'vehicle_extra'       => $vehicle_extra,
                'vehicle_extra_price' => $vehicle_extra_price,
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
     */
    public function destroy($id)
    {
        Vehicle::where('id', $id)->delete();
        VehicleExtra::where('vehicle_id', $id)->delete();
        VehicleFeature::where('vehicle_id', $id)->delete();
        VehicleOption::where('vehicles_id', $id)->delete();
        CategoryVehicle::where('vehicle_id', $id)->delete();
        VehicleNotAvailable::where('vehicle_id', $id)->delete();
        Booking::where('vehicle_id', $id)->delete();
        return response()->json(['success' => true, 'message' => trans('adminMessages.vehicle_deleted')]);
    }

    public function changeStatus($id, $status)
    {
        $vehicle = Vehicle::where('id', $id)->first();
        Vehicle::where('id', $id)->update(['status' => $status]);
        return response()->json(['success' => true, 'message' => trans('adminMessages.vehicle_status'), 'company_id' => $vehicle->company_id, 'branch_id' => $vehicle->company_address_id]);
    }

    public function vehicleNotAvailable($id)
    {
        $vehicle = Vehicle::with('companyAddress')->where('id', $id)->first();
        $vehicleNotAvailability = VehicleNotAvailable::where('vehicle_id', $id)->with([
            'vehicle' => function($query){
                $query->with([
                    'ryde' => function($q){
                        $q->with('brand', 'modelYear', 'color', 'rydeInstance');
                    },
                ], 'vehicleOptions');
            },
        ])->get();

        if($vehicle){
            return view('admin.vehicle.vehicleNotAvailable', [
                'vehicle_id'             => $id,
                'vehicle'                => $vehicle,
                'vehicleNotAvailability' => $vehicleNotAvailability,
            ]);
        } else{
            abort(404);
        }
    }

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

    public function vehicleNotAvailableDelete($id, $vehicle_id)
    {

        VehicleNotAvailable::where('id', $id)->delete();
        return response()->json(['success' => true, 'message' => trans('adminMessages.vehicleNotAvailable_deleted'), 'id' => $vehicle_id]);
    }


    public function getBranch(Request $request)
    {
        $company_id = $request->input('company_id');

        $company_addresses = CompanyAddress::where('company_id', $company_id)->get();
        echo '<option value="">Please Select Branch</option>';
        foreach($company_addresses as $company_address){
            echo "<option value='" . $company_address->id . "'>" . $company_address->address . "</option>";
        }

    }

    public function getRyde(Request $request)
    {
        $brand_id = $request->brand_id;
        $color_id = $request->color_id;
        $year_id = $request->year_id;
        $to_year_id = $request->to_year_id;
        $model_id = $request->model_id;

        if(!empty($brand_id) && !empty($model_id) && !empty($year_id) && !empty($color_id) && !empty($to_year_id)){
            $rydes = Ryde::where('id', $model_id)->with('rydeInstance')->first();

            if($rydes){
                return view('admin.vehicle.rydeShow', [
                    'id'          => $rydes->id,
                    'model_image' => $rydes->image,
                    'body'        => $rydes->rydeInstance->body->name,
                    'door'        => $rydes->rydeInstance->door->name,
                    'seats'       => $rydes->rydeInstance->seats,
                ]);

            } else{
                return view('admin.vehicle.rydeShow');
            }
        }
    }

    public function vehicleDetails($id)
    {
        $vehicle = Vehicle::where('id', $id)
            ->with([
                'ryde' => function($query){
                    $query->with('brand', 'modelYear', 'color', 'vehicleOptions', 'rydeInstance');
                },
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
        $array['globalModalDetails'] .= '<table class="table table-bordered">';
        $array['globalModalDetails'] .= '<thead class="thead-light"><tr><th colspan="6" class="text-center">' . config('languageString.ryde_details') . '</th></tr></thead>';
        $array['globalModalDetails'] .= '<thead class="thead-dark"><tr><th>' . config('languageString.buy_price') . '</th>
        <th>' . config('languageString.allowed_millage') . '</th><th>' . config('languageString.cost_per_extra_km') . '</th><th>' . config('languageString.trim') . '</th></tr></thead>';
        $array['globalModalDetails'] .= '<tr>';
        $array['globalModalDetails'] .= '<td>' . $vehicle->buy_price . '</td>';
        $array['globalModalDetails'] .= '<td>' . $vehicle->allowed_millage . '</td>';
        $array['globalModalDetails'] .= '<td>' . $vehicle->cost_per_extra_km . '</td>';
        $array['globalModalDetails'] .= '<td>' . $vehicle->trim . '</td>';
        $array['globalModalDetails'] .= '</tr>';
        $array['globalModalDetails'] .= '</table>';

        if(isset($vehicle->engine->name) && isset($vehicle->gearbox->name) && isset($vehicle->fuel->name)){
            $array['globalModalDetails'] .= '<table class="table table-bordered">';
            $array['globalModalDetails'] .= '<thead class="thead-light"><tr><th colspan="6" class="text-center">' . config('languageString.ryde_details') . '</th></tr></thead>';
            $array['globalModalDetails'] .= '<thead class="thead-dark"><tr><th>' . config('languageString.engine') . '</th><th>' . config('languageString.gearbox') . '
        </th><th>' . config('languageString.fuel') . '</th><th>' . config('languageString.van_number') . '</th><th>' . config('languageString.plate_number') . '</th></tr></thead>';
            $array['globalModalDetails'] .= '<tr>';
            $array['globalModalDetails'] .= '<td>' . $vehicle->engine->name . '</td>';
            $array['globalModalDetails'] .= '<td>' . $vehicle->gearbox->name . '</td>';
            $array['globalModalDetails'] .= '<td>' . $vehicle->fuel->name . '</td>';
            $array['globalModalDetails'] .= '<td>' . $vehicle->van_number . '</td>';
            $array['globalModalDetails'] .= '<td>' . $vehicle->plate_number . '</td>';
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

    public function getModel(Request $request)
    {
        $brand_id = $request->input('brand_id');
        $year_id = $request->input('year_id');
        $to_year_id = $request->input('to_year_id');
        $color_id = $request->input('color_id');

        $rydes = Ryde::where('brand_id', $brand_id)->where('model_year_id', $year_id)->where('to_year_id', $to_year_id)->where('color_id', $color_id)->get();
        if(count($rydes) > 0){
            echo "<option value=''>Please Select Model</option>";
            foreach($rydes as $ryde){
                echo "<option value='" . $ryde->id . "'>" . $ryde->name . "</option>";
            }
        } else{
            echo "<option value=''>No Model Found</option>";
        }
    }

}
