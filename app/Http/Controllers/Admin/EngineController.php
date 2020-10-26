<?php

namespace App\Http\Controllers\Admin;


use App\Booking;
use App\CategoryVehicle;
use App\Engine;
use App\EngineTranslation;
use App\Language;
use App\Ryde;
use App\Vehicle;
use App\VehicleExtra;
use App\VehicleFeature;
use App\VehicleNotAvailable;
use App\VehicleOption;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class EngineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if($request->ajax()){

            $engines = Engine::listsTranslations('name')
                ->select('engines.id')
                ->get();

            return Datatables::of($engines)
                ->addColumn('action', function($engines){
                    $edit_button = '<a href="' . route('admin::engine.edit', [$engines->id]) . '" class="btn btn-info btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.edit') . '"><i class="bx bx-pencil font-size-16 align-middle"></i></a>';
                    $delete_button = '<button data-id="' . $engines->id . '" class="delete-single btn btn-danger btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.delete') . '"><i class="bx bx-trash font-size-16 align-middle"></i></button>';
                    return '<div class="btn-icon-list">' . $edit_button . ' ' . $delete_button . '</div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.engine.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $languages = Language::all();
        return view('admin.engine.create', ['languages' => $languages]);
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
            $engine_order = Engine::max('id');
            $insert_id = Engine::create([
                'vehicle_type_id' => 1,
                'engine_order'    => $engine_order + 1,
            ]);
            $languages = Language::all();
            foreach($languages as $language){
                EngineTranslation::create([
                    'name'      => $request->input($language->language_code . '_name'),
                    'engine_id' => $insert_id->id,
                    'locale'    => $language->language_code,
                ]);
            }
            return response()->json(['success' => true, 'message' => trans('adminMessages.engine_inserted')]);
        } else{
            $languages = Language::all();
            foreach($languages as $language){
                EngineTranslation::updateOrCreate([
                    'engine_id' => $id,
                    'locale'    => $language->language_code,
                ],
                    [
                        'engine_id' => $id,
                        'locale'    => $language->language_code,
                        'name'      => $request->input($language->language_code . '_name'),
                    ]);

            }
            return response()->json(['success' => true, 'message' => trans('adminMessages.engine_updated')]);
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
        $engine = Engine::find($id);
        if($engine){
            $languages = Language::all();
            return view('admin.engine.edit', ['engine' => $engine, 'languages' => $languages]);
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
        Engine::where('id', $id)->delete();
        $vehicles = Vehicle::where('engine_id', $id)->get();
        foreach($vehicles as $vehicle){
            VehicleExtra::where('vehicle_id', $vehicle->id)->delete();
            VehicleFeature::where('vehicle_id', $vehicle->id)->delete();
            VehicleOption::where('vehicles_id', $vehicle->id)->delete();
            CategoryVehicle::where('vehicle_id', $vehicle->id)->delete();
            VehicleNotAvailable::where('vehicle_id', $vehicle->id)->delete();
            Booking::where('vehicle_id', $vehicle->id)->delete();
        }
        Vehicle::where('engine_id', $id)->delete();

        return response()->json(['success' => true, 'message' => trans('adminMessages.engine_deleted')]);
    }
}
