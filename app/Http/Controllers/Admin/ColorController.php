<?php

namespace App\Http\Controllers\Admin;


use App\Models\Booking;
use App\Models\CategoryVehicle;
use App\Models\Color;
use App\Models\ColorTranslation;
use App\Models\Language;
use App\Models\Ryde;
use App\Models\Vehicle;
use App\Models\VehicleExtra;
use App\Models\VehicleFeature;
use App\Models\VehicleNotAvailable;
use App\Models\VehicleOption;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            //DB::enableQueryLog();
            $colors = Color::listsTranslations('name')
                ->select('colors.id')
                ->get();
            // dd(DB::getQueryLog());
            return Datatables::of($colors)
                ->addColumn('action', function($colors){
                    $edit_button = '<a href="' . route('admin::color.edit', [$colors->id]) . '" class="btn btn-info btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.edit') . '"><i class="bx bx-pencil font-size-16 align-middle"></i></a>';
                    $delete_button = '<button data-id="' . $colors->id . '" class="delete-single btn btn-danger btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.delete') . '"><i class="bx bx-trash font-size-16 align-middle"></i></button>';
                    return '<div class="btn-icon-list">' . $edit_button . ' ' . $delete_button . '</div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.color.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $languages = Language::all();
        return view('admin.color.create', ['languages' => $languages]);
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
            $color_order = Color::max('id');

            $insert_id = Color::create([
                'color_order' => $color_order + 1,
            ]);
            $languages = Language::all();
            foreach($languages as $language){
                ColorTranslation::create([
                    'name'     => $request->input($language->language_code . '_name'),
                    'color_id' => $insert_id->id,
                    'locale'   => $language->language_code,
                ]);
            }
            return response()->json(['success' => true, 'message' => trans('adminMessages.color_inserted')]);
        } else{
            $languages = Language::all();
            foreach($languages as $language){
                ColorTranslation::updateOrCreate([
                    'color_id' => $id,
                    'locale'   => $language->language_code,
                ],
                    [
                        'color_id' => $id,
                        'locale'   => $language->language_code,
                        'name'     => $request->input($language->language_code . '_name'),
                    ]);

            }
            return response()->json(['success' => true, 'message' => trans('adminMessages.color_updated')]);
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
     * @return \Illuminate\Contracts\Foundation\App\Modelslication|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $color = Color::find($id);
        if($color){
            $languages = Language::all();
            return view('admin.color.edit', ['color' => $color, 'languages' => $languages]);
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
        $rydes = Ryde::where('color_id', $id)->get();
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
        Ryde::where('color_id', $id)->delete();
        Color::where('id', $id)->delete();
        return response()->json(['success' => true, 'message' => trans('adminMessages.color_deleted')]);
    }
}
