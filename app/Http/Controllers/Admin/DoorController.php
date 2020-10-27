<?php

namespace App\Http\Controllers\Admin;

use App\Models\Booking;
use App\Models\CategoryVehicle;
use App\Models\Door;
use App\Models\DoorTranslation;
use App\Models\Language;
use App\Models\Ryde;
use App\Models\RydeInstance;
use App\Models\Vehicle;
use App\Models\VehicleExtra;
use App\Models\VehicleFeature;
use App\Models\VehicleNotAvailable;
use App\Models\VehicleOption;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class DoorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\App\Modelslication|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $doors = Door::listsTranslations('name')
                ->select('doors.id')
                ->get();
            return Datatables::of($doors)
                ->addColumn('action', function($doors){
                    $edit_button = '<a href="' . route('admin::door.edit', [$doors->id]) . '" class="btn btn-info btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.edit') . '"><i class="bx bx-pencil font-size-16 align-middle"></i></a>';
                    $delete_button = '<button data-id="' . $doors->id . '" class="delete-single btn btn-danger btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.delete') . '"><i class="bx bx-trash font-size-16 align-middle"></i></button>';
                    return '<div class="btn-icon-list">' . $edit_button . ' ' . $delete_button . '</div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.door.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\App\Modelslication|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $languages = Language::all();
        return view('admin.door.create', ['languages' => $languages]);
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
            $door_order = Door::max('id');
            $insert_id = Door::create([
                'vehicle_type_id' => 1,
                'door_order'      => $door_order + 1,
            ]);
            $languages = Language::all();
            foreach($languages as $language){
                DoorTranslation::create([
                    'name'    => $request->input($language->language_code . '_name'),
                    'door_id' => $insert_id->id,
                    'locale'  => $language->language_code,
                ]);
            }
            return response()->json(['success' => true, 'message' => trans('adminMessages.door_inserted')]);
        } else{
            $languages = Language::all();
            foreach($languages as $language){
                DoorTranslation::updateOrCreate([
                    'door_id' => $id,
                    'locale'  => $language->language_code,
                ],
                    [
                        'door_id' => $id,
                        'locale'  => $language->language_code,
                        'name'    => $request->input($language->language_code . '_name'),
                    ]);

            }
            return response()->json(['success' => true, 'message' => trans('adminMessages.door_updated')]);
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\App\Modelslication|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $door = Door::find($id);
        if($door){
            $languages = Language::all();
            return view('admin.door.edit', ['door' => $door, 'languages' => $languages]);
        } else{
            abort(404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $rydeInstances = RydeInstance::where('door_id', $id)->get();
        foreach($rydeInstances as $rydeInstance){
            $rydes = Ryde::where('id', $rydeInstance->ryde_id)->get();

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
            Ryde::where('id', $rydeInstance->ryde_id)->delete();
        }
        RydeInstance::where('door_id', $id)->delete();
        Door::where('id', $id)->delete();

        return response()->json(['success' => true, 'message' => trans('adminMessages.door_deleted')]);
    }
}
