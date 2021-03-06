<?php

namespace App\Http\Controllers\Admin;


use App\Models\Booking;
use App\Models\CategoryVehicle;
use App\Models\Insurance;
use App\Models\InsuranceTranslation;
use App\Models\Language;
use App\Models\Vehicle;
use App\Models\VehicleExtra;
use App\Models\VehicleFeature;
use App\Models\VehicleNotAvailable;
use App\Models\VehicleOption;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class InsuranceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\App\Modelslication|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $insurances = Insurance::listsTranslations('name')
                ->select('insurances.id')
                ->get();
            return Datatables::of($insurances)
                ->addColumn('action', function ($insurances) {
                    $edit_button = '<a href="' . route('admin::insurance.edit', [$insurances->id]) . '" class="btn btn-info btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.edit') . '"><i class="bx bx-pencil font-size-16 align-middle"></i></a>';
                    $delete_button = '<button data-id="' . $insurances->id . '" class="delete-single btn btn-danger btn-icon" data-toggle="tooltip" data-placement="top" title="' . config('languageString.delete') . '"><i class="bx bx-trash font-size-16 align-middle"></i></button>';
                    return '<div class="btn-icon-list">' . $edit_button . ' ' . $delete_button. '</div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.insurance.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\App\Modelslication|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $languages = Language::all();
        return view('admin.insurance.create', ['languages' => $languages]);
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

        if ($id == NULL) {
            $insurance_order = Insurance::max('id');
            $insert_id = Insurance::create([
                'insurance_order' => $insurance_order + 1,
            ]);
            $languages = Language::all();
            foreach ($languages as $language) {
                InsuranceTranslation::create([
                    'name' => $request->input($language->language_code . '_name'),
                    'insurance_id' => $insert_id->id,
                    'locale' => $language->language_code,
                ]);
            }
            return response()->json(['success' => true, 'message' => trans('adminMessages.insurance_inserted')]);
        } else {
            $languages = Language::all();
            foreach ($languages as $language) {
                InsuranceTranslation::updateOrCreate([
                    'insurance_id' => $id,
                    'locale' => $language->language_code,
                ],
                    [
                        'insurance_id' => $id,
                        'locale' => $language->language_code,
                        'name' => $request->input($language->language_code . '_name')
                    ]);

            }
            return response()->json(['success' => true, 'message' => trans('adminMessages.insurance_updated')]);
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
        $insurance = Insurance::find($id);
        if ($insurance) {
            $languages = Language::all();
            return view('admin.insurance.edit', ['insurance' => $insurance, 'languages' => $languages]);
        } else {
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
        Insurance::where('id', $id)->delete();
        $vehicles = Vehicle::where('insurance_id', $id)->get();
        foreach($vehicles as $vehicle){
            VehicleExtra::where('vehicle_id', $vehicle->id)->delete();
            VehicleFeature::where('vehicle_id', $vehicle->id)->delete();
            VehicleOption::where('vehicles_id', $vehicle->id)->delete();
            CategoryVehicle::where('vehicle_id', $vehicle->id)->delete();
            VehicleNotAvailable::where('vehicle_id', $vehicle->id)->delete();
            Booking::where('vehicle_id', $vehicle->id)->delete();
        }
        Vehicle::where('insurance_id', $id)->delete();
        return response()->json(['success' => true, 'message' => trans('adminMessages.insurance_deleted')]);
    }
}
