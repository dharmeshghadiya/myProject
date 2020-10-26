<?php

namespace App\Http\Controllers\Admin;


use App\Booking;
use App\CategoryVehicle;
use App\ModelYear;
use App\Ryde;
use App\Vehicle;
use App\VehicleExtra;
use App\VehicleFeature;
use App\VehicleNotAvailable;
use App\VehicleOption;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ModelYearController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if($request->ajax()){

            $model_year = ModelYear::get();

            return Datatables::of($model_year)
                ->addColumn('action', function($model_year){
                    $edit_button = '<a href="' . route('admin::modelYear.edit', [$model_year->id]) . '" class="btn btn-info btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.edit') . '"><i class="bx bx-pencil font-size-16 align-middle"></i></a>';
                    $delete_button = '<button data-id="' . $model_year->id . '" class="delete-single btn btn-danger btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.delete') . '"><i class="bx bx-trash font-size-16 align-middle"></i></button>';
                    return '<div class="btn-icon-list">' . $edit_button . ' ' . $delete_button . '</div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.modelYear.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.modelYear.create');
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
                'name' => 'required',
            ];
            $duplicate = ModelYear::where('name', $request->input('name'))->first();
        } else{
            $validator_array = [
                'name' => 'required',
            ];
            $duplicate = ModelYear::where('name', $request->input('name'))
                ->where('id', '!=', $id)->first();
        }
        $validator = Validator::make($request->all(), $validator_array);
        if($validator->fails()){
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }
        if($duplicate){
            return response()->json(['success' => false, 'message' => trans('adminMessages.model_year_duplicate')]);
        } else{
            if($id == NULL){
                ModelYear::create([
                    'name' => $request->input('name'),
                ]);

                return response()->json(['success' => true, 'message' => trans('adminMessages.model_year_inserted')]);
            } else{
                ModelYear::where('id', $id)->update([
                    'name' => $request->input('name'),
                ]);
                return response()->json(['success' => true, 'message' => trans('adminMessages.model_year_updated')]);
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
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $model_year = ModelYear::find($id);
        if($model_year){
            return view('admin.modelYear.edit', ['model_year' => $model_year]);
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
        $rydes = Ryde::where('model_year_id', $id)->get();
        foreach($rydes as $ryde){
            $vehicles = Vehicle::where('ryde_id', $ryde->id)->get();
            foreach($vehicles as $vehicle){
                VehicleExtra::where('vehicle_id', $vehicle->id)->delete();
                VehicleFeature::where('vehicle_id', $vehicle->id)->delete();
                VehicleOption::where('vehicles_id', $vehicle->id)->delete();
                CategoryVehicle::where('vehicle_id', $vehicle->id)->delete();
                VehicleNotAvailable::where('vehicle_id', $vehicle->id)->delete();
                Booking::where('vehicle_id', $vehicle->id)->delete();
            }
            Vehicle::where('ryde_id', $ryde->id)->delete();
        }
        Ryde::where('model_year_id', $id)->delete();
        ModelYear::where('id', $id)->delete();
        return response()->json(['success' => true, 'message' => trans('adminMessages.model_year_deleted')]);
    }
}
