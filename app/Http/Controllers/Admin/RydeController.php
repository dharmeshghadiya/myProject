<?php

namespace App\Http\Controllers\Admin;


use App\Body;
use App\Booking;
use App\Brand;
use App\Color;
use App\BrandModel;
use App\ModelYear;
use App\Category;
use App\CategoryVehicle;
use App\Company;
use App\CompanyAddress;
use App\Door;
use App\Engine;
use App\Fuel;
use App\Gearbox;
use App\Insurance;
use App\Language;
use App\Ryde;
use App\RydeFeature;
use App\RydeInstance;
use App\RydeTranslation;
use App\User;
use App\Vehicle;
use App\VehicleExtra;
use App\VehicleFeature;
use App\VehicleNotAvailable;
use App\VehicleOption;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class RydeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {

        if($request->ajax()){
            //DB::enableQueryLog();
            $rydes = Ryde::with('brand', 'modelYear', 'color', 'rydeInstance')->get();
            //dd(DB::getQueryLog());
            //dd($rydes);
            return Datatables::of($rydes)
                ->addColumn('image', function($rydes){
                    $url = asset($rydes->image);
                    return "<img src='" . $url . "' style='width:100px' />";
                })
                ->addColumn('brand_name', function($rydes){
                    return $rydes->brand->name;
                })
                ->addColumn('modelYear', function($rydes){
                    return $rydes->modelYear->name;
                })
                ->addColumn('color', function($rydes){
                    $colorName = '';
                    if(!empty($rydes->color->name)){

                        $colorName = $rydes->color->name;
                    }
                    return $colorName;
                })
                ->addColumn('model_name', function($rydes){
                    return $rydes->name;
                })
                ->addColumn('action', function($rydes){
                    $edit_button = '<a href="' . route('admin::ryde.edit', [$rydes->id]) . '" class="btn btn-info btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.edit') . '"><i class="bx bx-pencil font-size-16 align-middle"></i></a>';
                    $delete_button = '<button data-id="' . $rydes->id . '" class="delete-single btn btn-danger btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.delete') . '"><i class="bx bx-trash font-size-16 align-middle"></i></button>';

                    $view_detail_button = '<button data-id="' . $rydes->id . '" class="ryde-details btn  btn-secondary btn-icon" data-effect="effect-fall" data-toggle="tooltip" data-placement="top" title="' . config('languageString.view_details') . '"><i class="bx bx-bullseye font-size-16 align-middle"></i></button>';

                    return '<div class="btn-icon-list">' . $edit_button . ' ' . $delete_button . ' ' . $view_detail_button . '</div>';
                })
                ->rawColumns(['action', 'image'])
                ->make(true);
        }
        return view('admin.ryde.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $languages = Language::all();
        $makes = Brand::all();
        $bodies = Body::all();
        $engines = Engine::all();
        $doors = Door::all();
        $fuels = Fuel::all();
        $colors = Color::all();
        $modelYears = ModelYear::all();
        $gearboxes = Gearbox::all();
        $insurances = Insurance::all();

        return view('admin.ryde.create', [
            'languages'  => $languages,
            'makes'      => $makes,
            'modelYears' => $modelYears,
            'colors'     => $colors,
            'bodies'     => $bodies,
            'engines'    => $engines,
            'doors'      => $doors,
            'fuels'      => $fuels,
            'gearboxes'  => $gearboxes,
            'insurances' => $insurances,
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
                'make'    => 'required',
                'year'    => 'required',
                'image'   => 'required',
                'color'   => 'required',
                'body_id' => 'required',
                'door'    => 'required',
                'seat'    => 'required',
            ];
            $duplicate_ryde = Ryde::where([
                'brand_id'      => $request->make,
                'model_year_id' => $request->year,
                'color_id'      => $request->color,
            ])->first();
        } else{
            $validator_array = [
                'make'    => 'required',
                'year'    => 'required',
                'color'   => 'required',
                'body_id' => 'required',
                'door'    => 'required',
                'seat'    => 'required',
            ];
            $duplicate_ryde = Ryde::where([
                'brand_id'      => $request->make,
                'model_year_id' => $request->year,
                'color_id'      => $request->color,
            ])->where('id', '!=', $id)->first();

        }

        $validator = Validator::make($request->all(), $validator_array);
        if($validator->fails()){
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }

        if($duplicate_ryde){
            $duplicate_ryde_name = RydeTranslation::where([
                'name'   => $request->input('en_name'),
                'locale' => 'en',
            ])->where('ryde_id', '!=', $id)->first();
            if($duplicate_ryde_name){
                return response()->json(['success' => false, 'message' => trans('adminMessages.ryde_duplicate')]);
            }
        }

        $image_1_path = $file_1_name = '';
        if($request->hasFile('image')){
            $image_1_path = 'uploads/' . date('Y') . '/' . date('m');
            $files = $request->file('image');

            if(!File::exists(public_path() . "/" . $image_1_path)){
                File::makeDirectory(public_path() . "/" . $image_1_path, 0777, true);
            }

            $extension = $files->getClientOriginalExtension();
            $destination_path = public_path() . '/' . $image_1_path;
            $file_1_name = uniqid() . '.' . $extension;
            $files->move($destination_path, $file_1_name);

            if($id != NULL){
                Ryde::where('id', $id)->update([
                    'image' => $image_1_path . '/' . $file_1_name,
                ]);
            }
        }

        if($id == NULL){
            $insert_id = Ryde::create([
                'brand_id'      => $request->make,
                'model_year_id' => $request->year,
                'to_year_id' => $request->to_year_id,
                'color_id'      => $request->color,
                'image'         => $image_1_path . '/' . $file_1_name,
            ]);

            $languages = Language::all();
            foreach($languages as $language){
                RydeTranslation::create([
                    'name'    => $request->input($language->language_code . '_name'),
                    'ryde_id' => $insert_id->id,
                    'locale'  => $language->language_code,
                ]);
            }
            RydeInstance::create([
                'ryde_id' => $insert_id->id,
                'body_id' => $request->input('make'),
                'door_id' => $request->input('door'),
                'seats'   => $request->input('seat'),
            ]);

            return response()->json(['success' => true, 'message' => trans('adminMessages.ryde_inserted')]);
        } else{

            Ryde::where('id', $id)->update([
                'brand_id'      => $request->make,
                'model_year_id' => $request->year,
                'to_year_id' => $request->to_year_id,
                'color_id'      => $request->color,
            ]);

            RydeInstance::where('ryde_id', $id)->update([
                'body_id' => $request->input('make'),
                'door_id' => $request->input('door'),
                'seats'   => $request->input('seat'),
            ]);


            return response()->json(['success' => true, 'message' => trans('adminMessages.ryde_updated')]);
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
     */
    public function edit($id)
    {
        $ryde = Ryde::with('rydeInstance')->find($id);

        if($ryde){
            $languages = Language::all();
            $makes = Brand::all();
            $bodies = Body::all();
            $engines = Engine::all();
            $doors = Door::all();
            $fuels = Fuel::all();
            $gearboxes = Gearbox::all();

            $colors = Color::all();
            $modelYears = ModelYear::all();
            return view('admin.ryde.edit', [
                'ryde'       => $ryde,
                'languages'  => $languages,
                'makes'      => $makes,
                'bodies'     => $bodies,
                'engines'    => $engines,
                'doors'      => $doors,
                'fuels'      => $fuels,
                'gearboxes'  => $gearboxes,
                'modelYears' => $modelYears,
                'colors'     => $colors,
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
        $vehicles = Vehicle::where('ryde_id', $id)->get();
        foreach($vehicles as $vehicle){
            VehicleExtra::where('vehicle_id', $vehicle->id)->delete();
            VehicleFeature::where('vehicle_id', $vehicle->id)->delete();
            VehicleOption::where('vehicles_id', $vehicle->id)->delete();
            CategoryVehicle::where('vehicle_id', $vehicle->id)->delete();
            VehicleNotAvailable::where('vehicle_id', $vehicle->id)->delete();
            Booking::where('vehicle_id', $vehicle->id)->delete();
        }
        Vehicle::where('ryde_id', $id)->delete();
        RydeInstance::where('ryde_id', $id)->delete();
        Ryde::where('id', $id)->delete();

        return response()->json(['success' => true, 'message' => trans('adminMessages.ryde_deleted')]);
    }


    public function getModelImage(Request $request)
    {
        $model_id = $request->input('model_id');
        $model_image = BrandModel::where('id', $model_id)->select('image')->first();
        echo "<img src='" . asset($model_image->image) . "' style='max-width:30%'>";
    }

    public function rydeDetails($id)
    {
        $rydes = Ryde::where('id', $id)->with('brand', 'modelYear', 'rydeInstance', 'color')->first();
        $colorName = '';
        if(!empty($rydes->color->name)){
            $colorName = $rydes->color->name;
        }

        $array['globalModalTitle'] = $rydes->brand->name . ' | ' . $rydes->name . ' | ' . $rydes->modelYear->name . ' | ' . $colorName;
        $array['globalModalDetails'] = '<table class="table table-bordered">';
        $array['globalModalDetails'] .= '<thead class="thead-light"><tr><th colspan="6" class="text-center">' . config('languageString.ryde_details') . '</th></tr></thead>';
        $array['globalModalDetails'] .= '<thead class="thead-dark"><tr><th>' . config('languageString.body') . '</th><th>' . config('languageString.door') . '</th><th>' . config('languageString.seat') . '</th></tr></thead>';
        $array['globalModalDetails'] .= '<tr>';
        $array['globalModalDetails'] .= '<td>' . $rydes->rydeInstance->body->name . '</td>';

        $array['globalModalDetails'] .= '<td>' . $rydes->rydeInstance->door->name . '</td>';


        $array['globalModalDetails'] .= '<td>' . $rydes->rydeInstance->seats . '</td>';
        $array['globalModalDetails'] .= '</tr>';
        $array['globalModalDetails'] .= '</table>';
        $url = asset($rydes->image);
        $array['globalModalDetails'] .= "<img src='" . $url . "' />";
        $array['globalModalDetails'] .= '<table class="table table-bordered">';

        $array['globalModalDetails'] .= '</table>';

        return response()->json(['success' => true, 'data' => $array]);
    }

    public function viewBranchRyde($company_id, $branch_id)
    {
        $company_details = Company::with([
            'companyAddress' => function($query) use ($branch_id){
                $query->where('id', $branch_id);
            },
        ])->where('id', $company_id)->first();

        $vehicles = Vehicle::with([
            'ryde' => function($query){
                $query->with('brand', 'modelYear', 'color');
            },
        ])->where('company_address_id', $branch_id)->get();
        return view('admin.dealer.viewRyde', [
            'vehicles'        => $vehicles,
            'company_details' => $company_details,
            'branch_id'       => $branch_id,
        ]);

    }


}
